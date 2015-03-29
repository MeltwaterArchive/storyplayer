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
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use stdClass;
use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use Phix_Project\ExceptionsLib1\Legacy_ErrorHandler;
use Phix_Project\ExceptionsLib1\Legacy_ErrorException;
use DataSift\Stone\ConfigLib\E5xx_ConfigFileNotFound;
use DataSift\Stone\ConfigLib\E5xx_InvalidConfigFile;
use DataSift\Storyplayer\PlayerLib\E4xx_NoSuchReport;
use DataSift\Storyplayer\PlayerLib\PhaseGroup_Player;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\Story_Player;
use DataSift\Storyplayer\PlayerLib\Tale_Player;
use DataSift\Storyplayer\PlayerLib\TestEnvironment_Player;
use DataSift\Storyplayer\Console\DevModeConsole;
use DataSift\Storyplayer\Injectables;

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
class PlayStory_Command extends BaseCommand implements CliSignalHandler
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

    // our list of players to execute
    protected $playerList;

    // our injected data / services
    // needed for when user presses CTRL+C
    protected $injectables;

    /**
     * the environment that we have loaded
     *
     * @var string
     */
    protected $envName;

    public function __construct($injectables)
    {
        // call our parent
        parent::__construct($injectables);

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
            new PlayStory_LogJsonSwitch(),
            new PlayStory_LogJUnitSwitch(),
            new PlayStory_LogTapSwitch(),
        ));

        // add in the features that this command relies on
        $this->addFeature(new Feature_VerboseSupport);
        $this->addFeature(new Feature_ConsoleSupport);
        $this->addFeature(new Feature_ColorSupport);
        $this->addFeature(new Feature_DeviceSupport);
        $this->addFeature(new Feature_TestEnvironmentConfigSupport);
        $this->addFeature(new Feature_SystemUnderTestConfigSupport);
        $this->addFeature(new Feature_LocalhostSupport);
        $this->addFeature(new Feature_ActiveConfigSupport);
        $this->addFeature(new Feature_DefinesSupport);
        $this->addFeature(new Feature_PhaseLoaderSupport);
        $this->addFeature(new Feature_ProseLoaderSupport);
        $this->addFeature(new Feature_PersistReuseTargetSupport);
        $this->addFeature(new Feature_PersistDeviceSupport);
        $this->addFeature(new Feature_PersistProcessesSupport);
        $this->addFeature(new Feature_TestUsersSupport);

        // now setup all of the switches that we support
        $this->addFeatureSwitches();
    }

    /**
     *
     * @param  CliEngine $engine
     * @param  array     $params
     * @param  Injectables|null $injectables
     * @return integer
     */
    public function processCommand(CliEngine $engine, $params = array(), $injectables = null)
    {
        // we need to wrap our code to catch old-style PHP errors
        $legacyHandler = new Legacy_ErrorHandler();

        // run our code
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

            // stop the browser if available
            if (isset($this->st)) {
                $this->st->stopDevice();
            }

            // tell the calling process that things did not end well
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
        $this->initFeaturesBeforeModulesAvailable($engine);

        // now it is safe to create our shorthand
        $runtimeConfig        = $injectables->getRuntimeConfig();
        $runtimeConfigManager = $injectables->getRuntimeConfigManager();
        $output               = $injectables->output;

        // save the output for use in other methods
        $this->output = $output;

        // setup reporting modules
        $this->initReporting($engine, $injectables);

        // at this point, all of the services / data held in $injectables
        // has been initialised and is ready for use
        //
        // what's left is the stuff that needs initialising in phases
        // or $st

        // create a new StoryTeller object
        $st = new StoryTeller($injectables);

        // remember our $st object, as we'll need it for our
        // shutdown function
        $this->st = $st;

        // now that we have $st, we can initialise any feature that
        // wants to use our modules
        $this->initFeaturesAfterModulesAvailable($st, $engine, $injectables);

        // install signal handling, now that $this->st is defined
        //
        // we wouldn't want signal handling called out of order :)
        $this->initSignalHandling($injectables);

        // build our list of players to run
        $this->initPlayerList($engine, $injectables, $params);

        // let's keep score :)
        $startTime = microtime(true);

        // and we're ready to tell the world that we're here
        $output->startStoryplayer(
            $engine->getAppVersion(),
            $engine->getAppUrl(),
            $engine->getAppCopyright(),
            $engine->getAppLicense()
        );

        // $this->playerList contains one or more things to play
        //
        // let's play each of them in order
        foreach ($this->playerList as $player)
        {
            // execute each player in turn
            //
            // they may also have their own list of nested players
            $player->play($st, $injectables);

            // make sure the test device has been stopped
            // (it may have been persisted by the story)
            //
            // we do not allow the test device to persist between
            // top-level players
            $st->stopDevice();
        }

        // write out any changed runtime config to disk
        $runtimeConfigManager->saveRuntimeConfig($runtimeConfig, $output);

        // how long did that take?
        $duration = microtime(true) - $startTime;

        // tell the output plugins that we're all done
        $output->endStoryplayer($duration);

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
    protected function initReporting(CliEngine $engine, Injectables $injectables)
    {
        // are there any reporting modules to be loaded?
        if (!isset($engine->options->reports)) {
            // no
            return;
        }

        // setup the reports that have been requested
        $injectables->initReportLoaderSupport($injectables);
        foreach ($engine->options->reports as $reportName => $reportFilename)
        {
            try {
                $report = $injectables->reportLoader->loadReport($reportName, [ 'filename' => $reportFilename]);
            }
            catch (E4xx_NoSuchReport $e) {
                $injectables->output->logCliError("no such report '{$reportName}'");
                exit(1);
            }
            $injectables->output->usePluginInSlot($report, $reportName);
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
     * @param  CliEngine   $cliEngine
     * @param  Injectables $injectables
     * @param  array       $cliParams
     * @return void
     */
    protected function initPlayerList(CliEngine $cliEngine, Injectables $injectables, $cliParams)
    {
        // our list of stories to play
        $this->playerList = [];

        // do we have any parameters at this point?
        if (empty($cliParams)) {
            $msg = "no stories listed on the command-line." . PHP_EOL . PHP_EOL
                 . "see 'storyplayer help play-story' for required params" . PHP_EOL;
            $this->output->logCliError($msg);
            exit(1);
        }

        // keep track of the stories to play
        $storiesToPlay = [];

        foreach ($cliParams as $cliParam) {
            // figure out what to do?
            if (is_dir($cliParam)) {
                $storiesToPlay = array_merge($storiesToPlay, $this->addStoriesFromFolder($cliEngine, $injectables, $cliParam));
            }
            else if (is_file($cliParam)) {
                // are we loading a story, or a list of stories?
                $paramParts  = explode('.', $cliParams[0]);
                $paramSuffix = end($paramParts);

                switch ($paramSuffix) {
                    case 'php':
                        $storiesToPlay = array_merge($storiesToPlay, $this->addStoryFromFile($cliEngine, $injectables, $cliParam));
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

        // did we find any stories to play?
        if (count($storiesToPlay) == 0) {
            $this->output->logCliError("no stories to play :(");
            exit(1);
        }

        // wrap all of the stories in a TestEnvironment
        $this->playerList[] = new TestEnvironment_Player($storiesToPlay, $injectables);
    }

    // ==================================================================
    //
    // Story loading
    //
    // ------------------------------------------------------------------

    protected function addStoryFromFile(CliEngine $engine, Injectables $injectables, $storyFile)
    {
        // warn the user if the story file doesn't end in 'Story.php'
        //
        // this is because Storyplayer will ignore the file if you
        // point Storyplayer at a folder instead of a specific file
        if (substr($storyFile, -9) != 'Story.php') {
            $msg = "your story should end in 'Story.php', but it does not" . PHP_EOL;
            $this->output->logCliWarning($msg);
        }

        // these are the players we want to execute for the story
        $return = [
            new Story_Player($storyFile, $injectables),
        ];

        // all done
        return $return;
    }

    protected function addStoriesFromFolder(CliEngine $engine, Injectables $injectables, $folder)
    {
        // find everything under the folder
        $filenames = $this->findStoriesInFolder($folder);

        // did we find anything?
        if (!count($filenames)) {
            $msg = "no stories found in '{$folder}'" . PHP_EOL . PHP_EOL
                 . "do your stories' filenames end in 'Story.php'?";
            $this->output->logCliError($msg);
            exit(1);
        }

        // create a set of story players
        $storiesToPlay = [];
        foreach ($filenames as $filename) {
            $storiesToPlay[] = new Story_Player($filename, $injectables);
        }

        // all done
        return $storiesToPlay;
    }

    protected function findStoriesInFolder($folder)
    {
        // use the SPL to do the heavy lifting
        $dirIter = new RecursiveDirectoryIterator($folder);
        $recIter = new RecursiveIteratorIterator($dirIter);
        $regIter = new RegexIterator($recIter, '/^.+Story\.php$/i', RegexIterator::GET_MATCH);

        // what happened?
        $filenames = [];
        foreach ($regIter as $match) {
            $filenames[] = $match[0];
        }

        // let's get the list into some semblance of order
        sort($filenames);

        // all done
        return $filenames;
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
        // tell the user what is happening
        echo PHP_EOL;
        echo "============================================================" . PHP_EOL;
        echo "USER ABORT!!" . PHP_EOL;

        // do we skip destroying the test environment?
        if ($this->st->getPersistTestEnvironment()) {
            echo PHP_EOL . "* Warning: NOT destroying test environment" . PHP_EOL
                 .         "           --reuse-target flag is set" . PHP_EOL;
        }

        // cleanup
        echo PHP_EOL . "Cleaning up: ";
        $phasesPlayer = new PhaseGroup_Player();
        $phasesPlayer->playPhases(
            "user abort",
            $this->st,
            $this->injectables,
            $this->injectables->activeConfig->getData('storyplayer.phases.userAbort'),
            null
        );

        echo " done" . PHP_EOL . "============================================================" . PHP_EOL . PHP_EOL;

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
            echo Story_Player::$outcomeToText[$result->resultCode] . " :: " . $result->story->getName() . "\n";
        }
    }

}
