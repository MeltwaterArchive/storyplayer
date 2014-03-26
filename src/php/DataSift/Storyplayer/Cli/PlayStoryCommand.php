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

use stdClass;

use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use Phix_Project\CliEngine\CliEngineSwitch;
use Phix_Project\CliEngine\CliResult;

use DataSift\Stone\ConfigLib\E5xx_ConfigFileNotFound;
use DataSift\Stone\ConfigLib\E5xx_InvalidConfigFile;
use DataSift\Stone\LogLib\Log;

use DataSift\Storyplayer\PlayerLib\PhasesPlayer;
use DataSift\Storyplayer\PlayerLib\StoryContext;
use DataSift\Storyplayer\PlayerLib\StoryPlayer;
use DataSift\Storyplayer\PlayerLib\StoryResult;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\StoryLib\StoryLoader;
use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\StoryListLib\StoryListLoader;
use DataSift\Storyplayer\UserLib\User;
use DataSift\Storyplayer\UserLib\ConfigUserLoader;
use DataSift\Storyplayer\Prose\E5xx_NoMatchingActions;
use DataSift\Storyplayer\OutputLib\DevModeConsolePlugin;

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
class PlayStoryCommand extends CliCommand
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

    public function __construct($additionalContext)
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

        // for convenience, the current computer's hostname will be the
        // default environment
        $defaultEnvName = EnvironmentHelper::getDefaultEnvironmentName($additionalContext->envList);

        // the switches that this command supports
        $this->setSwitches(array(
            new LogLevelSwitch(),
            new ColorSwitch(),
            new DevModeSwitch(),
            new EnvironmentSwitch($additionalContext->envList, $defaultEnvName),
            new DefineSwitch(),
            new DeviceSwitch($additionalContext->deviceList),
            new PersistProcessesSwitch(),
            new PlatformSwitch(),
        ));
    }

    public function processCommand(CliEngine $engine, $params = array(), $injectables = null)
    {
        // shorthand
        $envList              = $injectables->envList;
        $runtimeConfig        = $injectables->runtimeConfig;
        $runtimeConfigManager = $injectables->runtimeConfigManager;
        $staticConfig         = $injectables->staticConfig;
        $staticConfigManager  = $injectables->staticConfigManager;
        $output               = $injectables->output;

        // save the output for use in other methods
        $this->output = $output;

        // switch output plugins first, before we do anything else at all
        if (isset($engine->options->dev) && $engine->options->dev) {
            // switch our main output to 'dev mode'
            $output->usePlugin('console', new DevModeConsolePlugin());

            // dev mode means 'show me everything'
            $engine->options->verbosity = 2;
        }

        // which environment are we using?
        //
        // special case - only one environment defined, so always use that
        if (count($envList) == 1) {
            // we will use the first (and only) environment in the list
            $envName = $envList[0];
        }
        else if (!isset($engine->options->environment)) {
            // this switch is optional ... *if* there is only one environment
            // in the list
            $msg = "there is more than one test environment defined" . PHP_EOL . PHP_EOL
                   . "use 'storyplayer list-environments' to see the list" . PHP_EOL
                   . "use '-e <environment>' to select one" . PHP_EOL;
            $output->logCliError($msg);
            return 1;
        }
        else {
            // use the environment that the user has provided
            $envName = $engine->options->environment;
        }

        // are we persisting processes?
        if (isset($engine->options->persistProcesses) && $engine->options->persistProcesses) {
            $this->persistProcesses = true;
        }

        // do we have a story, or list of stories?
        if (!isset($params[0])) {
            $msg = "you must specify which story to play" . PHP_EOL;
            $output->logCliError($msg);
            return 1;
        }

        // setup logging
        //
        // by default, we go with what is in the config, and use the
        // command-line switch(es) to override it
        $loggingConfig = $staticConfig->logger;
        if (isset($engine->options->logLevels)) {
            $loggingConfig->levels = $engine->options->logLevels;
        }

        // allow the command-line to override the config
        $verbosity = $engine->options->verbosity;
        $output->setVerbosity($engine->options->verbosity);
        if ($verbosity > 0) {
            $loggingConfig->levels->LOG_DEBUG = true;
        }
        if ($verbosity > 1 ) {
            $loggingConfig->levels->LOG_TRACE = true;
        }

        // we're ready to switch logging on now
        Log::init("storyplayer", $loggingConfig);

        // and we're ready to tell the world that we're here
        $output->startStoryplayer(
            $engine->getAppVersion(),
            $engine->getAppUrl(),
            $engine->getAppCopyright(),
            $engine->getAppLicense()
        );

        // setup signal handling
        pcntl_signal(SIGTERM, array($this, 'sigtermHandler'));
        pcntl_signal(SIGINT , array($this, 'sigtermHandler'));

        // let's go and get our environment
        try
        {
            // load our environment-specific config
            //
            // this will be merged in with the default config
            $staticConfigManager->loadAdditionalConfig($staticConfig, $envName);
        }
        catch (E5xx_ConfigFileNotFound $e) {
            // do we already have this device?
            if (!isset($staticConfig->environments->$envName)) {
                // no we don't ... report an error
                $msg = "no config file '{$envName}.json' found" . PHP_EOL;
                $output->logCliError($msg);
                exit(1);
            }
        }
        catch (E5xx_InvalidConfigFile $e) {
            $msg = "unable to load config file '{$envName}.json'" . PHP_EOL . PHP_EOL
                   . $e->getMessage();
            $output->logCliError($msg);
            exit(1);
        }

        // do we have a defaults environment section?
        if (!isset($staticConfig->environments->defaults)) {
            // create an empty one to keep PlayerLib happy
            $staticConfig->environments->defaults = new stdClass;
        }

        // do we need to load device-specific config?
        //
        // we do this AFTER loading environments, because that's the order
        // we've told users it will happen in
        if (isset($engine->options->device)) {
            $deviceName = $engine->options->device;
        }
        else {
            $deviceName = null;
        }

        if ($deviceName)
        {
            try {
                // load our device-specific config
                //
                // this will be merged in with the default config
                $staticConfigManager->loadAdditionalConfig($staticConfig, $deviceName);
            }
            catch (E5xx_ConfigFileNotFound $e) {
                // do we already have this device?
                if (!isset($staticConfig->devices->$deviceName)) {
                    // no we don't ... report an error
                    $msg = "no config file '{$deviceName}.json' found" . PHP_EOL;
                    $output->logCliError($msg);
                    exit(1);
                }
            }
            catch (E5xx_InvalidConfigFile $e) {
                $msg = "unable to load config file '{$deviceName}.json'" . PHP_EOL . PHP_EOL
                       . $e->getMessage();
                $output->logCliError($msg);
                exit(1);
            }
        }

        // do we have any defines from the command-line to merge in?
        //
        // this must be done AFTER all config files have been loaded!
        if (isset($engine->options->defines)) {
            // merge into the default + what was loaded from config files
            $staticConfig->defines->mergeFrom($engine->options->defines);
        }

        // are we loading a story, or a list of stories?
        $arg2parts  = explode('.', $params[0]);
        $arg2suffix = end($arg2parts);

        // create a new StoryTeller object
        $teller = new StoryTeller($injectables);

        // remember our $st object, as we'll need it for our
        // shutdown function
        $this->st = $teller;

        // create something to play this story
        $storyPlayer = new StoryPlayer();

        // create the supporting context for this test run
        $context = new StoryContext($staticConfig, $runtimeConfig, $envName, $deviceName);
        $teller->setStoryContext($context);

        // we're going to use this to play our setup and teardown phases
        $phasesPlayer = new PhasesPlayer();

        switch($arg2suffix)
        {
            case "php":
                // we are running an individual story

                // load our story
                $story = StoryLoader::loadStory($params[0]);
                $teller->setStory($story);

                // make sure we've loaded the user
                $context->initUser($staticConfig, $runtimeConfig, $story);

                // make the story happen
                $phasesPlayer->playPhases($teller, 'startup');
                $storyPlayer->playStory($teller);
                $phasesPlayer->playPhases($teller, 'shutdown');

                // all done
                break;

            case "json":
                // we are running a list of stories

                // load the list of stories
                $storyList = StoryListLoader::loadList($params[0]);

                // keep track of the results
                $results = array();

                // run through our list of stories
                foreach ($storyList->stories as $storyFile)
                {
                    // load our story
                    $story = StoryLoader::loadStory($storyFile);
                    $teller->setStory($story);

                    // make sure we've loaded the user
                    $context->initUser($staticConfig, $runtimeConfig, $story);

                    // special case - reusable environments
                    if ($storyList->options->reuseTestEnvironment) {
                        // we need to remember the staticConfig, as we are
                        // probably about to override it
                        $origStaticConfig = clone $staticConfig;

                        // story #1 - keep the test environment around
                        if ($storyFile == $storyList->stories[0]) {
                            // we do not override the user's preference for
                            // the TestEnvironmentStartup

                            // do not shutdown the TestEnvironment;
                            // we want to re-use it in the other stories
                            $staticConfig->phases->story->TestEnvironmentTeardown = false;
                        }
                        else if ($storyFile == end($storyList->stories)) {
                            // do nothing - we do not want to override
                            // the user's config file settings here
                        }
                        else {
                            // we are running a story in the middle of the list
                            //
                            // do not re-create the test environment
                            // do not destroy it afterwards
                            $staticConfig->phases->story->TestEnvironmentSetup = false;
                            $staticConfig->phases->story->TestEnvironmentTeardown = false;
                        }
                    }

                    // make the story happen
                    $phasesPlayer->playPhases($teller, 'startup');
                    $results[] = $storyPlayer->playStory($teller);
                    $phasesPlayer->playPhases($teller, 'shutdown');

                    // special case - reusable environments
                    if ($storyList->options->reuseTestEnvironment) {
                        // restore the original config
                        $staticConfig = clone $origStaticConfig;
                    }
                }

                // report on the final results
                $this->summariseStoryList($results);

                // all done
                break;

            default:
                // unsupported!
        }

        // write out any changed runtime config to disk
        $runtimeConfigManager->saveRuntimeConfig($runtimeConfig);

        // all done
        return 0;
    }

    protected function summariseStoryList($storyResults)
    {
        // we need to make a pronouncement about the whole list of stories

        echo "\n";
        echo "============================================================\n";
        echo "FINAL RESULTS\n";
        echo "\n";

        foreach ($storyResults as $result)
        {
            echo StoryPlayer::$outcomeToText[$result->storyResult] . " :: " . $result->story->getName() . "\n";
        }
    }

    public function sigtermHandler($signo)
    {
        echo "\n";
        echo "============================================================\n";
        echo "USER ABORT!!\n";
        echo "============================================================\n";
        echo "\n";

        // cleanup
        $phasesPlayer = new PhasesPlayer();
        $phasesPlayer->playPhases($this->st, 'shutdown');

        // force a clean shutdown
        exit(1);
    }
}
