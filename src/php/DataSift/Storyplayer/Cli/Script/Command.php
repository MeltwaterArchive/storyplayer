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
use DataSift\Storyplayer\PlayerLib\E4xx_NoSuchReport;
use DataSift\Storyplayer\PlayerLib\PhaseGroup_Player;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\Script_Player;
use DataSift\Storyplayer\Console\ScriptConsole;
use DataSift\Storyplayer\Injectables;

/**
 * A command to play a script
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Script_Command extends BaseCommand implements CliSignalHandler
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

    public function __construct($injectables)
    {
        parent::__construct($injectables);

        // define the command
        $this->setName('script');
        $this->setShortDescription('run an automation script');
        $this->setLongDescription(
            "Use this command to play an automation script."
            .PHP_EOL
        );
        $this->setArgsList(array(
            "[<script.php>]" => "run a script"
        ));

        // the switches that this command supports
        $this->addFeature(new Feature_ConsoleSupport);
        $this->addFeature(new Feature_DeviceSupport);
        $this->addFeature(new Feature_TestEnvironmentConfigSupport);
        $this->addFeature(new Feature_SystemUnderTestConfigSupport);
        $this->addFeature(new Feature_LocalhostSupport);
        $this->addFeature(new Feature_ActiveConfigSupport);
        $this->addFeature(new Feature_DefinesSupport);
        $this->addFeature(new Feature_PhaseLoaderSupport);
        $this->addFeature(new Feature_ProseLoaderSupport);
        $this->addFeature(new Feature_TestUsersSupport);

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
        // make sure we're using the ScriptConsole by default
        $injectables->output->usePluginInSlot(new ScriptConsole, 'console');

        // process the common functionality
        $this->initFeaturesBeforeModulesAvailable($engine);

        // now it is safe to create our shorthand
        $runtimeConfig        = $injectables->getRuntimeConfig();
        $runtimeConfigManager = $injectables->getRuntimeConfigManager();
        $output               = $injectables->output;

        // save the output for use in other methods
        $this->output = $output;

        // at this point, all of the services / data held in $injectables
        // has been initialised and is ready for use

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

        // build our list of stories to run
        $this->initScriptList($engine, $injectables, $params);

        // and we're ready to tell the world that we're here
        $output->startStoryplayer(
            $engine->getAppVersion(),
            $engine->getAppUrl(),
            $engine->getAppCopyright(),
            $engine->getAppLicense()
        );

        // $this->scriptList contains one or more things to run
        //
        // let's play each of them in order
        foreach ($this->scriptList as $player)
        {
            // play the story(ies)
            $player->play($st, $injectables);
        }

        // write out any changed runtime config to disk
        $runtimeConfigManager->saveRuntimeConfig($runtimeConfig, $output);

        // tell the output plugins that we're all done
        $output->endStoryplayer(0);

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
     * @param  CliEngine   $engine
     * @param  Injectables $injectables
     * @param  array       $cliParams
     * @return void
     */
    protected function initScriptList(CliEngine $cliEngine, Injectables $injectables, $cliParams)
    {
        // our list of stories to play
        $this->scriptList = [];

        foreach ($cliParams as $cliParam) {
            // figure out what to do?
            if (is_dir($cliParam)) {
                $this->scriptList = $this->scriptList + $this->addScriptsFromFolder($cliEngine, $injectables, $cliParam);
            }
            else if (is_file($cliParam)) {
                // are we loading a story, or a list of stories?
                $paramParts  = explode('.', $cliParams[0]);
                $paramSuffix = end($paramParts);

                switch ($paramSuffix) {
                    case 'php':
                        $this->scriptList = $this->scriptList + $this->addScriptFromFile($cliEngine, $injectables, $cliParam);
                        break;

                    case 'json':
                        $this->scriptList = $this->scriptList + $this->addScriptsFromFile($cliEgine, $injectables, $cliParam);
                        break;

                    default:
                        $this->output->logCliError("unsupported script file '{$cliParam}'");
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

    protected function addScriptFromFile(CliEngine $engine, Injectables $injectables, $storyFile)
    {
        // these are the players we want to execute for the story
        $return = [
            new Script_Player($storyFile, $injectables),
        ];

        // all done
        return $return;
    }

    protected function addScriptsFromFolder(CliEngine $engine, Injectables $injectables, $folder)
    {
        // tbd
    }

    protected function addScriptsFromFile(CliEngine $engine, Injectables $injectables, $file)
    {
        // tbd
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
            $this->injectables->activeConfig->getData('storyplayer.phases.userAbort'),
            null
        );

        // force a clean shutdown
        exit(1);
    }
}