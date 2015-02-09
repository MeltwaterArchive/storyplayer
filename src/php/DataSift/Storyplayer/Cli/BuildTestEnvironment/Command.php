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
use DataSift\Storyplayer\PlayerLib\TestEnvironment_Player;
use DataSift\Storyplayer\Console\DevModeConsole;
use DataSift\Storyplayer\Injectables;

use DataSift\Storyplayer\Cli\Feature\ActiveConfigSupport;
use DataSift\Storyplayer\Cli\Feature\ColorSupport;
use DataSift\Storyplayer\Cli\Feature\ConsoleSupport;
use DataSift\Storyplayer\Cli\Feature\DefinesSupport;
use DataSift\Storyplayer\Cli\Feature\DeviceSupport;
use DataSift\Storyplayer\Cli\Feature\LocalhostSupport;
use DataSift\Storyplayer\Cli\Feature\PersistDeviceSupport;
use DataSift\Storyplayer\Cli\Feature\PersistProcessesSupport;
use DataSift\Storyplayer\Cli\Feature\PersistReuseTargetSupport;
use DataSift\Storyplayer\Cli\Feature\PhaseLoaderSupport;
use DataSift\Storyplayer\Cli\Feature\ProseLoaderSupport;
use DataSift\Storyplayer\Cli\Feature\SystemUnderTestSupport;
use DataSift\Storyplayer\Cli\Feature\TestEnvironmentSupport;
use DataSift\Storyplayer\Cli\Feature\VerboseSupport;

/**
 * A command to build a test environment
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class BuildTestEnvironment_Command extends BaseCommand
{
    // we need to track this for handling CTRL-C
    protected $st;

    // we track this for convenience
    protected $output;

    // our list of players to execute
    protected $playerList;

    // our injected data / services
    // needed for when user presses CTRL+C
    protected $injectables;

    public function __construct($injectables)
    {
        parent::__construct($injectables);

        // define the command
        $this->setName('build-test-environment');
        $this->setShortDescription('build a test environment');
        $this->setLongDescription(
            "Use this command to build a test environment. Handy for testing that your test environment "
            ."provisions correctly."
            .PHP_EOL
        );

        // add in the features that this command relies on
        $this->addFeature(new Feature_VerboseSupport);
        $this->addFeature(new Feature_ConsoleSupport);
        $this->addFeature(new Feature_ColorSupport);
        $this->addFeature(new Feature_TestEnvironmentConfigSupport);
        $this->addFeature(new Feature_LocalhostSupport);
        $this->addFeature(new Feature_ActiveConfigSupport);
        $this->addFeature(new Feature_DefinesSupport);
        $this->addFeature(new Feature_PhaseLoaderSupport);
        $this->addFeature(new Feature_ProseLoaderSupport);
        $this->addFeature(new Feature_PersistReuseTargetSupport);

        // now setup all of the switches that we support
        $this->addFeatureSwitches();
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
        // process the common functionality
        $this->initFeaturesBeforeModulesAvailable($engine);

        // now it is safe to create our shorthand
        $runtimeConfig        = $injectables->runtimeConfig;
        $runtimeConfigManager = $injectables->runtimeConfigManager;
        $output               = $injectables->output;

        // save the output for use in other methods
        $this->output = $output;

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
        // we just want a TestEnvironment
        $this->playerList[] = new TestEnvironment_Player([], $injectables);
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
}
