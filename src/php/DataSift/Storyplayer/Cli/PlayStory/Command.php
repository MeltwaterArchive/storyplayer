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
use DataSift\Storyplayer\PlayerLib\Story_Context;
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
class PlayStory_Command extends CliCommand
{
    /**
     * should we let the test device (ie the web browser) survive between
     * phases?
     *
     * @var boolean
     */
    protected $persistDevice = false;

    /**
     * should we let background processes survive when we shutdown?
     * @var boolean
     */
    protected $persistProcesses = false;

    /**
     * should we skip the TestEnvironmentDestruction phase?
     *
     * @var boolean
     */
    protected $peristTarget = false;

    /**
     * should we skip the TestEnvironmentConstruction phase?
     *
     * @var boolean
     */
    protected $reuseTarget = false;

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
            new PlayStory_PersistDeviceSwitch(),
            new PlayStory_PersistProcessesSwitch(),
            new PlayStory_PersistTargetSwitch(),
            new PlayStory_ReuseTargetSwitch(),
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
        $this->applyCommonFunctionalitySupport($engine, $this, $injectables);

        // now it is safe to create our shorthand
        $runtimeConfig        = $injectables->runtimeConfig;
        $runtimeConfigManager = $injectables->runtimeConfigManager;
        $output               = $injectables->output;

        // save the output for use in other methods
        $this->output = $output;

        // build our list of players to run
        $this->initPlayerList($engine, $injectables, $params);

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

        // initialise device persistence
        $this->initDevicePersistence($st, $engine, $injectables);

        // initialise process persistence
        $this->initProcessPersistence($st, $engine, $injectables);

        // initialise target persistence / reuse
        $this->initTargetPersistence($st, $engine, $injectables);

        // are we persisting the test device?
        if ($this->persistDevice) {
            $st->setPersistDevice();
        }

        // are we persisting the test environment?
        // if ($this->persistTarget) {
        //     $st->setPersistTarget();
        // }

        // are we reusing the test environment?
        // if ($this->reuseTarget) {
        //     $st->setReuseTarget();
        // }

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
     * @param  CliEngine   $engine
     * @param  Injectables $injectables
     * @param  array       $cliParams
     * @return void
     */
    protected function initPlayerList(CliEngine $cliEngine, Injectables $injectables, $cliParams)
    {
        // our list of stories to play
        $this->playerList = [];

        foreach ($cliParams as $cliParam) {
            // figure out what to do?
            if (is_dir($cliParam)) {
                $this->playerList = $this->playerList + $this->addStoriesFromFolder($cliEngine, $injectables, $cliParam);
            }
            else if (is_file($cliParam)) {
                // are we loading a story, or a list of stories?
                $paramParts  = explode('.', $cliParams[0]);
                $paramSuffix = end($paramParts);

                switch ($paramSuffix) {
                    case 'php':
                        $this->playerList = $this->playerList + $this->addStoryFromFile($cliEngine, $injectables, $cliParam);
                        break;

                    case 'json':
                        $this->playerList = $this->playerList + $this->addStoriesFromTale($cliEngine, $injectables, $cliParam);
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
            new TestEnvironment_Player([
                new Story_Player($storyFile, $injectables),
            ], $injectables)
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

        // wrap them in a test environment
        $return = [
            new TestEnvironment_Player($storiesToPlay, $injectables)
        ];

        // all done
        return $return;
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

    protected function addStoriesFromTale(CliEngine $engine, Injectables $injectables, $taleFile)
    {
        // the list of actions we're building up
        $return = [];

        // let's get our tale
        //
        // this gives us a stdClass of the tale file, with defaults
        // added to the options section if required
        $tale = Tale_Loader::loadTale($taleFile);

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
            $return[] = new StoryPlayer($storyFile);
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
    // Phase-related initialisation
    //
    // ------------------------------------------------------------------

    /**
     *
     * @param  CliEngine   $engine
     * @param  Injectables $injectables
     * @return void
     */
    protected function initDevicePersistence(StoryTeller $st, CliEngine $engine, Injectables $injectables)
    {
        // by default, no persistence
        $this->persistDevice = false;

        // are we persisting the device?
        if (isset($engine->options->persistDevice) && $engine->options->persistDevice) {
            $this->persistDevice = true;
            $st->setPersistDevice();
        }
    }

    /**
     *
     * @param  CliEngine   $engine
     * @param  Injectables $injectables
     * @return void
     */
    protected function initProcessPersistence(StoryTeller $st, CliEngine $engine, Injectables $injectables)
    {
        // by default, no persistence
        $this->persistProcesses = false;

        // are we persisting processes?
        if (isset($engine->options->persistProcesses) && $engine->options->persistProcesses) {
            $this->persistProcesses = true;
        }
    }

    protected function initTargetPersistence(StoryTeller $st, CliEngine $engine, Injectables $injectables)
    {
        // are we keeping the test environment hanging around afterwards?
        if (isset($engine->options->persistTarget) && $engine->options->persistTarget)
        {
            $injectables->activeConfig->storyplayer->phases->testEnvShutdown->TestEnvironmentDestruction = false;
            $injectables->activeConfig->storyplayer->phases->story->TestEnvironmentTeardown = false;
        }

        // are we trying to use a test environment that has previously
        // been persisted?
        if (isset($engine->options->reuseTarget) && $engine->options->reuseTarget)
        {
            // does the target exist to be reused?
            $this->output->setSilentMode();
            $hasTarget = $st->fromTargetsTable()->hasCurrentTestEnvironment();
            $this->output->resetSilentMode();

            if (!$hasTarget) {
                $this->output->logCliWarning("target environment '" . $st->getTestEnvironmentName() . "' does not exist; ignoring --reuse-target switch");
                return;
            }

            // okay, so we have the test environment in the targets table,
            // but has the test environment been changed at all?
            $this->output->setSilentMode();
            $origSig = $st->fromTargetsTable()->getCurrentTestEnvironmentSignature();
            $currentSig = $st->getTestEnvironmentSignature();
            $this->output->resetSilentMode();

            if ($origSig != $currentSig) {
                // our test environment entry isn't valid, so remove it
                $this->output->setSilentMode();
                $st->usingTargetsTable()->removeCurrentTestEnvironment();
                $this->output->resetSilentMode();

                $this->output->logCliWarning("target environment '" . $st->getTestEnvironmentName() . "' has changed; ignoring --reuse-target switch");
                return;
            }

            // if we get here, then we do not need to create the test environment
            $injectables->activeConfig->storyplayer->phases->testEnvStartup->TestEnvironmentConstruction = false;
            $injectables->activeConfig->storyplayer->phases->story->TestEnvironmentSetup = false;
        }
        else
        {
            // do we already have this target?
            //
            // this can happen when the test environment was previously
            // persisted, and this time we're being run without the
            // --reuse-target flag

            // does the target exist to be reused?
            $this->output->setSilentMode();
            $hasTarget = $st->fromTargetsTable()->hasCurrentTestEnvironment();
            $this->output->resetSilentMode();

            if ($hasTarget) {
                // remove this target from the table
                $this->output->setSilentMode();
                $st->usingTargetsTable()->removeCurrentTestEnvironment();
                $this->output->resetSilentMode();
            }
        }
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
        $phasesPlayer = new PhaseGroup_Player();
        $phasesPlayer->playPhases(
            $this->st,
            $this->injectables,
            $this->injectables->activeConfig->storyplayer->phases->shutdown,
            null
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
            echo Story_Player::$outcomeToText[$result->resultCode] . " :: " . $result->story->getName() . "\n";
        }
    }

}
