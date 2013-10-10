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

use DataSift\Stone\ConfigLib\LoadedConfig;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * Storyplayer's default config - the config that is active before we
 * load any config files
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class DefaultStaticConfig extends LoadedConfig
{
	public function __construct()
	{
		// defaults for LogLib
		$this->logger = new BaseObject();
		$this->logger->writer = "StdErrWriter";

        $this->environments = new BaseObject();
        $this->environments->defaults = new BaseObject();

        $levels = new BaseObject();
        $levels->LOG_EMERGENCY = true;
        $levels->LOG_ALERT = true;
        $levels->LOG_CRITICAL = true;
        $levels->LOG_ERROR = true;
        $levels->LOG_WARNING = true;
        $levels->LOG_NOTICE = true;
        $levels->LOG_INFO = true;
        $levels->LOG_DEBUG = true;
        $levels->LOG_TRACE = true;

        $this->logger->levels = $levels;

        // defaults for phases
        $phases = new BaseObject();
        $phases->TestEnvironmentSetup = true;
        $phases->TestSetup = true;
        $phases->PreTestPrediction = true;
        $phases->PreTestInspection = true;
        $phases->Action = true;
        $phases->PostTestInspection = true;
        $phases->TestTeardown = true;
        $phases->TestEnvironmentTeardown = true;

        $this->phases = $phases;

        // defaults for defines
        $this->defines = new BaseObject();

        // defaults for devices
        $this->devices = new BaseObject();
        $this->devices->defaults = new BaseObject();

        // defaults for Chrome, running locally
        $this->devices->chrome = new BaseObject;
        $this->devices->chrome->provider = 'LocalWebDriver';
        $this->devices->chrome->browser  = 'chrome';

        // defaults for Firefox, running locally
        $this->devices->firefox = new BaseObject;
        $this->devices->firefox->provider = 'LocalWebDriver';
        $this->devices->firefox->browser  = 'firefox';

        // defaults for Safari, running locally
        $this->devices->safari = new BaseObject;
        $this->devices->safari->provider = 'LocalWebDriver';
        $this->devices->safari->browser  = 'safari';

        // all done
    }
}
