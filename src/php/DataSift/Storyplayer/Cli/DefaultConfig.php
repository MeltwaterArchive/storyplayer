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

use DataSift\Stone\ObjectLib\E5xx_NoSuchProperty;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * The config we use when we run stories
 *
 * 1: the default config is defined in here
 * 2: we merge in config from config files
 * 3: we override config with command-line params
 *
 * The StaticConfigManager class is where you'll find all of the logic
 * for loading and merging data.
 *
 * ALL of the public properties on this object are data bags of one kind
 * or another.
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class DefaultConfig extends BaseObject
{
    public $appSettings;
    public $defines;
    public $logger;
    public $phases;
    public $prose;
    public $reports;

    public function __construct()
    {
        $this->initDefaultConfig();
    }

    /**
     *
     * @return void
     */
    public function initDefaultConfig()
    {
        // the default list of places to search for config files
        $this->configs = new BaseObject();
        $this->configs->devices = [
            getcwd() . DIRECTORY_SEPARATOR . '.storyplayer/devices',
        ];
        $this->configs->localEnvironments = [
            getcwd() . DIRECTORY_SEPARATOR . '.storyplayer/local-environments',
        ];
        $this->configs->testEnvironments = [
            getcwd() . DIRECTORY_SEPARATOR . '.storyplayer/test-environments'
        ];

        // defaults for LogLib
        $this->logger = new BaseObject();
        $this->logger->writer = "StdErrWriter";

        $levels = new BaseObject();
        $levels->LOG_EMERGENCY = true;
        $levels->LOG_ALERT = true;
        $levels->LOG_CRITICAL = true;
        $levels->LOG_ERROR = true;
        $levels->LOG_WARNING = true;
        $levels->LOG_NOTICE = true;
        $levels->LOG_INFO = true;
        $levels->LOG_DEBUG = false;
        $levels->LOG_TRACE = false;

        $this->logger->levels = $levels;

        // defaults for phases
        $phases = new BaseObject();
        $phases->startup = new BaseObject();
        $phases->startup->StartupHandlers = true;
        $phases->story = new BaseObject();
        $phases->story->CheckBlacklisted = true;
        $phases->story->TestEnvironmentSetup = true;
        $phases->story->TestSetup = true;
        $phases->story->PreTestPrediction = true;
        $phases->story->PreTestInspection = true;
        $phases->story->Action = true;
        $phases->story->PostTestInspection = true;
        $phases->story->TestTeardown = true;
        $phases->story->TestEnvironmentTeardown = true;
        $phases->story->ApplyRoleChanges = true;
        $phases->shutdown = new BaseObject();
        $phases->shutdown->ShutdownHandlers = true;

        $this->phases = $phases;

        // defaults for defines
        $this->defines = new BaseObject();
    }

    public function checkPhases()
    {
        // make sure that phases.namespaces is correctly defined
        if (isset($this->phases->namespaces)) {
            if (!is_array($this->phases->namespaces)) {
                throw new E5xx_InvalidConfig("the 'phases.namespaces' section of the config must either be an array, or it must be left out");
            }
        }
    }

    public function checkProse()
    {
        // make sure that prose.namespaces is correctly defined
        if (isset($this->prose, $this->prose->namespaces)) {
            if (!is_array($this->prose->namespaces)) {
                throw new E5xx_InvalidConfig("the 'prose.namespaces' section of the config must either be an array, or it must be left out");
            }
        }
    }

    public function checkReports()
    {
        // where are we looking?
        if (isset($this->reports, $this->reports->namespaces)) {
            if (!is_array($this->reports->namespaces)) {
                throw new E5xx_InvalidConfig("the 'reports.namespaces' section of the config must either be an array, or it must be left out");
            }
        }
    }
}
