<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use Exception;
use stdClass;
use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use Phix_Project\ExceptionsLib1\Legacy_ErrorHandler;
use Phix_Project\ExceptionsLib1\Legacy_ErrorException;
use DataSift\Stone\ConfigLib\E5xx_ConfigFileNotFound;
use DataSift\Stone\ConfigLib\E5xx_InvalidConfigFile;
use DataSift\Stone\LogLib\Log;
use DataSift\Storyplayer\PlayerLib\E4xx_NoSuchReport;
use DataSift\Storyplayer\PlayerLib\PhasesPlayer;
use DataSift\Storyplayer\PlayerLib\StoryContext;
use DataSift\Storyplayer\PlayerLib\StoryPlayer;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\TalePlayer;
use DataSift\Storyplayer\Console\DevModeConsole;

/**
 * A command to play a story, or a list of stories
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class PlayStory_Command extends CliCommand
{
    /**
     * should we let background processes survive when we shutdown?
     * @var boolean
     */
    protected $persistProcesses = false;

    // we need to track this for handling CTRL-C
    protected $st;

    // we track this for convenience
    protected $output;

    // our list of stories to execute
    protected $storyList;

    // our injected data / services
    // needed for when user presses CTRL+C
    protected $injectables;

    /**
     * the environment that we have loaded
     *
     * @var string
     */
    protected $envName;

    // common features
    use CommonFunctionalitySupport;

    public function __construct($injectables)
    {
        // define the command
        $this->setName('play-story');
        $this->setShortDescription('play a story, or a list of stories');
        $this->setLongDescription(
            "Use this command to play a single story, or a list of stories defined in a JSON file."
            .PHP_EOL
        );
        $this->setArgsList(array(
            "[<story.php|list.json>]" => "run a story, or a list of stories"
        ));

        // the switches that this command supports
        $this->setSwitches(array(
            new PlayStory_PersistProcessesSwitch(),
            new PlayStory_LogJsonSwitch(),
            new PlayStory_LogJUnitSwitch(),
            new PlayStory_LogTapSwitch(),
        ));

        // add in the common features
        $this->initCommonFunctionalitySupport($this, $injectables);
    }

    /**
     *
     * @param  CliEngine $engine
     * @param  array     $params
     * @param  Injectables $injectables
     * @return integer
     */
    public function processCommand(CliEngine $engine, $params = array(), $injectables = null)
    {
        // we need to wrap our code to catch old-style PHP errors
        $legacyHandler = new Legacy_ErrorHandler();
        try {
            $returnCode = $legacyHandler->run([$this, 'processInsideLegacyHandler'], [$engine, $params, $injectables]);
            return $returnCode;
        }
        catch (Exception $e) {
            $injectables->output->logCliError($e->getMessage());
            $engine->options->dev = true;
            if (isset($engine->options->dev) && $engine->options->dev) {
                $injectables->output->logCliError("Stack trace is:\n\n" . $e->getTraceAsString());
            }
            exit(1);
        }
    }

    public function processInsideLegacyHandler(CliEngine $engine, $params = array(), $injectables = null)
    {
        // the order we do things:
        //
        // 1. build up the config we're going to use
        //    a. storyplayer.json (already done)
        //    c. any additional config file
        //    c. test-environment config file
        //    d. per-device config file
        //
        // 2. override from the command-line
        //    a. -D switches
        //    b. persistent processes
        //
        // 3. build up the list of stories to run
        //    a. test environment setup
        //    b. one or more stories
        //    c. test environment teardown
        //
        // 4. setup any remaining services
        //    a. phase loading
        //    b. prose loading
        //    c. report loader
        //
        // 5. setup the output channels
        //    a. the console (i.e. --dev mode)
        //    b. report-to-file plugins

        // process the common functionality
        $this->applyCommonFunctionalitySupport($engine, $this, $injectables);

        // now it is safe to create our shorthand
        $runtimeConfig        = $injectables->runtimeConfig;
        $runtimeConfigManager = $injectables->runtimeConfigManager;
        $output               = $injectables->output;

        // save the output for use in other methods
        $this->output = $output;

        // initialise process persistence
        $this->initProcessPersistence($engine, $injectables);

        // build our list of stories to run
        $this->initStoryList($engine, $injectables, $params);

        // setup our Reports loader
        $this->initReportLoader($injectables);

        // setup reporting modules
        $this->initReporting($engine, $injectables);

        // at this point, all of the services / data held in $injectables
        // has been initialised and is ready for use

        // create a new StoryTeller object
        $st = new StoryTeller($injectables);

        // remember our $st object, as we'll need it for our
        // shutdown function
        $this->st = $st;

        // install signal handling, now that $this->st is defined
        //
        // we wouldn't want signal handling called out of order :)
        $this->initSignalHandling($injectables);

        // and we're ready to tell the world that we're here
        $output->startStoryplayer(
            $engine->getAppVersion(),
            $engine->getAppUrl(),
            $engine->getAppCopyright(),
            $engine->getAppLicense()
        );

        // $this->storyList contains one or more things to play
        //
        // let's play each of them in order
        foreach ($this->storyList as $player)
        {
            // create the supporting context for this test run
            $context = new StoryContext($injectables);

            // track the context
            $st->setStoryContext($context);

            // play the story(ies)
            $player->play($st, $injectables);
        }

        // write out any changed runtime config to disk
        $runtimeConfigManager->saveRuntimeConfig($runtimeConfig);

        // tell the output plugins that we're all done
        $output->endStoryplayer();

        // all done
        return 0;
    }

    // ==================================================================
    //
    // the individual initX() methods
    //
    // these are processed *after* the objects defined in the
    // CommonFunctionalitySupport trait have been initialised
    //
    // ------------------------------------------------------------------

    /**
     *
     * @param  CliEngine   $engine
     * @param  Injectables $injectables
     * @return void
     */
    protected function initProcessPersistence(CliEngine $engine, Injectables $injectables)
    {
        // by default, no persistence
        $this->persistProcesses = false;

        // are we persisting processes?
        if (isset($engine->options->persistProcesses) && $engine->options->persistProcesses) {
            $this->persistProcesses = true;
        }
    }

    /**
     *
     * @param  Injectables $injectables
     * @return void
     */
    protected function initReportLoader(Injectables $injectables)
    {
        $injectables->initReportLoaderSupport($injectables);
    }

    /**
     *
     * @param  CliEngine   $engine
     * @param  Injectables $injectables
     * @return void
     */
    protected function initReporting(CliEngine $engine, Injectables $injectables)
    {
        // are there any reporting modules to be loaded?
        if (!isset($engine->options->reports)) {
            // no
            return;
        }

        // setup the reports that have been requested
        foreach ($engine->options->reports as $reportName => $reportFilename)
        {
            try {
                $report = $injectables->reportLoader->loadReport($reportName, [ 'filename' => $reportFilename]);
            }
            catch (E4xx_NoSuchReport $e) {
                $injectables->output->logCliError("no such report '{$reportName}'");
                exit(1);
            }
            $injectables->output->usePlugin($reportName, $report);
        }

        // all done
    }

    /**
     *
     * @param  Injectables $injectables
     * @return void
     */
    protected function initSignalHandling(Injectables $injectables)
    {
        // we need to remember the injectables, for when we handle CTRL+C
        $this->injectables = $injectables;

        // setup signal handling
        pcntl_signal(SIGTERM, array($this, 'sigtermHandler'));
        pcntl_signal(SIGINT , array($this, 'sigtermHandler'));
    }

    /**
     *
     * @param  CliEngine   $engine
     * @param  Injectables $injectables
     * @param  array       $cliParams
     * @return void
     */
    protected function initStoryList(CliEngine $cliEngine, Injectables $injectables, $cliParams)
    {
        // our list of stories to play
        $this->storyList = [];

        foreach ($cliParams as $cliParam) {
            // figure out what to do?
            if (is_dir($cliParam)) {
                $this->storyList = $this->storyList + $this->addStoriesFromFolder($cliEngine, $injectables, $cliParam);
            }
            else if (is_file($cliParam)) {
                // are we loading a story, or a list of stories?
                $paramParts  = explode('.', $cliParams[0]);
                $paramSuffix = end($paramParts);

                switch ($paramSuffix) {
                    case 'php':
                        $this->storyList = $this->storyList + $this->addStoryFromFile($cliEngine, $injectables, $cliParam);
                        break;

                    case 'json':
                        $this->storyList = $this->storyList + $this->addStoriesFromTale($cliEgine, $injectables, $cliParam);
                        break;

                    default:
                        $this->output->logCliError("unsupported story file '{$cliParam}'");
                        exit(1);
                }
            }
            else {
                // if we get here, we've no idea what to do
                $this->output->logCliError("no such file: '{$cliParam}'");
                exit(1);
            }
        }
    }

    // ==================================================================
    //
    // Story loading
    //
    // ------------------------------------------------------------------

    protected function addStoryFromFile(CliEngine $engine, Injectables $injectables, $storyFile)
    {
        // these are the players we want to execute for the story
        $return = [
            new StoryPlayer(
                $storyFile,
                $injectables->activeConfig->storyplayer->phases->startup,
                $injectables->activeConfig->storyplayer->phases->story,
                $injectables->activeConfig->storyplayer->phases->shutdown
            )
        ];

        // all done
        return $return;
    }

    protected function addStoriesFromFolder(CliEngine $engine, Injectables $injectables, $folder)
    {
        // tbd
    }

    protected function addStoriesFromTale(CliEngine $engine, Injectables $injectables, $taleFile)
    {
        // the list of actions we're building up
        $return = [];

        // let's get our tale
        //
        // this gives us a stdClass of the tale file, with defaults
        // added to the options section if required
        $tale = TaleLoader::loadTale($taleFile);

        // support for reusing test environments
        if ($tale->options->reuseTestEnvironment) {
            $injectables->activeConfig->phases->story->testEnvironmentSetup = false;
            $injectables->activeConfig->phases->story->testEnvironmentTeardown = false;
        }
        else {
            // we are not reusing test environments, so ALWAYS create
            // and destroy
            $injectables->activeConfig->phases->story->testEnvironmentSetup = true;
            $injectables->activeConfig->phases->story->testEnvironmentTeardown = true;
        }
        // our first story ALWAYS needs to create the test environment
        $firstStoryPhases = clone $injectables->activeConfig->phases->story;
        $firstStoryPhases->testEnvironmentSetup = true;

        // our last story ALWAYS needs to destroy the test environment
        $lastStoryPhases  = clone $injectables->activeConfig->phases->story;
        $lastStoryPhases->testEnvironmentTeardown = true;

        foreach ($tale->stories as $storyFile) {
            $return[] = new StoryPlayer(
                $storyFile,
                $injectables->activeConfig->phases->startup,
                $injectables->activeConfig->phases->story,
                $injectables->activeConfig->phases->shutdown
            );
        }

        if ($tale->options->reuseTestEnvironment) {
            // clean up after ourselves at the end
            $return[] = new TestEnvironmentTeardownPlayer($tale->stories[0]);
        }

        // all done
        return $return;
    }

    // ==================================================================
    //
    // SIGNAL handling
    //
    // ------------------------------------------------------------------

    /**
     *
     * @param  integer $signo
     * @return void
     */
    public function sigtermHandler($signo)
    {
        echo "\n";
        echo "============================================================\n";
        echo "USER ABORT!!\n";
        echo "============================================================\n";
        echo "\n";

        // cleanup
        $phasesPlayer = new PhasesPlayer();
        $phasesPlayer->playPhases(
            $this->st,
            $this->injectables,
            $this->injectables->staticConfig->phases->shutdown
        );

        // force a clean shutdown
        exit(1);
    }

    // ==================================================================
    //
    // legacy code goes here
    //
    // everything below here is old code that needs stripping out
    // before we release v1.6
    //
    // ------------------------------------------------------------------

    protected function summariseStoryList($storyResults)
    {
        // we need to make a pronouncement about the whole list of stories

        echo "\n";
        echo "============================================================\n";
        echo "FINAL RESULTS\n";
        echo "\n";

        foreach ($storyResults as $result)
        {
            echo StoryPlayer::$outcomeToText[$result->resultCode] . " :: " . $result->story->getName() . "\n";
        }
    }

}
