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
use Datasift\Os;
use Datasift\IfconfigParser;
use Datasift\netifaces;
use Datasift\netifaces\NetifacesException;

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
class StaticConfig extends LoadedConfig
{
    public $environments;
    public $defines;
    public $devices;
    public $device;
    public $deviceName;
    public $env;
    public $envName;
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

        // defaults for devices
        $this->devices = new BaseObject();
        $this->devices->defaults = new BaseObject();

        // defaults for Chrome, running locally
        $this->devices->chrome = new BaseObject;
        $this->devices->chrome->adapter = 'LocalWebDriver';
        $this->devices->chrome->browser  = 'chrome';

        // defaults for Firefox, running locally
        $this->devices->firefox = new BaseObject;
        $this->devices->firefox->adapter = 'LocalWebDriver';
        $this->devices->firefox->browser  = 'firefox';

        // defaults for Safari, running locally
        $this->devices->safari = new BaseObject;
        $this->devices->safari->adapter = 'LocalWebDriver';
        $this->devices->safari->browser  = 'safari';

        // ----------------------------------------------------------------
        //
        // Sauce Labs browsers

        # Windows 8.1
        $this->devices->sl_firefox25_win8_1 = new BaseObject;
        $this->devices->sl_firefox25_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox25_win8_1->browser = 'firefox';
        $this->devices->sl_firefox25_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox25_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox25_win8_1->desiredCapabilities['version'] = "25";

        $this->devices->sl_firefox24_win8_1 = new BaseObject;
        $this->devices->sl_firefox24_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox24_win8_1->browser = 'firefox';
        $this->devices->sl_firefox24_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox24_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox24_win8_1->desiredCapabilities['version'] = "24";

        $this->devices->sl_firefox23_win8_1 = new BaseObject;
        $this->devices->sl_firefox23_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox23_win8_1->browser = 'firefox';
        $this->devices->sl_firefox23_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox23_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox23_win8_1->desiredCapabilities['version'] = "23";

        $this->devices->sl_firefox22_win8_1 = new BaseObject;
        $this->devices->sl_firefox22_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox22_win8_1->browser = 'firefox';
        $this->devices->sl_firefox22_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox22_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox22_win8_1->desiredCapabilities['version'] = "22";

        $this->devices->sl_firefox21_win8_1 = new BaseObject;
        $this->devices->sl_firefox21_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox21_win8_1->browser = 'firefox';
        $this->devices->sl_firefox21_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox21_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox21_win8_1->desiredCapabilities['version'] = "21";

        $this->devices->sl_firefox20_win8_1 = new BaseObject;
        $this->devices->sl_firefox20_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox20_win8_1->browser = 'firefox';
        $this->devices->sl_firefox20_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox20_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox20_win8_1->desiredCapabilities['version'] = "20";

        $this->devices->sl_firefox19_win8_1 = new BaseObject;
        $this->devices->sl_firefox19_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox19_win8_1->browser = 'firefox';
        $this->devices->sl_firefox19_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox19_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox19_win8_1->desiredCapabilities['version'] = "19";

        $this->devices->sl_firefox18_win8_1 = new BaseObject;
        $this->devices->sl_firefox18_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox18_win8_1->browser = 'firefox';
        $this->devices->sl_firefox18_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox18_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox18_win8_1->desiredCapabilities['version'] = "18";

        $this->devices->sl_firefox17_win8_1 = new BaseObject;
        $this->devices->sl_firefox17_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox17_win8_1->browser = 'firefox';
        $this->devices->sl_firefox17_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox17_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox17_win8_1->desiredCapabilities['version'] = "17";

        $this->devices->sl_firefox16_win8_1 = new BaseObject;
        $this->devices->sl_firefox16_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox16_win8_1->browser = 'firefox';
        $this->devices->sl_firefox16_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox16_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox16_win8_1->desiredCapabilities['version'] = "16";

        $this->devices->sl_firefox15_win8_1 = new BaseObject;
        $this->devices->sl_firefox15_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox15_win8_1->browser = 'firefox';
        $this->devices->sl_firefox15_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox15_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox15_win8_1->desiredCapabilities['version'] = "15";

        $this->devices->sl_firefox14_win8_1 = new BaseObject;
        $this->devices->sl_firefox14_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox14_win8_1->browser = 'firefox';
        $this->devices->sl_firefox14_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox14_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox14_win8_1->desiredCapabilities['version'] = "14";

        $this->devices->sl_firefox13_win8_1 = new BaseObject;
        $this->devices->sl_firefox13_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox13_win8_1->browser = 'firefox';
        $this->devices->sl_firefox13_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox13_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox13_win8_1->desiredCapabilities['version'] = "13";

        $this->devices->sl_firefox12_win8_1 = new BaseObject;
        $this->devices->sl_firefox12_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox12_win8_1->browser = 'firefox';
        $this->devices->sl_firefox12_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox12_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox12_win8_1->desiredCapabilities['version'] = "12";

        $this->devices->sl_firefox11_win8_1 = new BaseObject;
        $this->devices->sl_firefox11_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox11_win8_1->browser = 'firefox';
        $this->devices->sl_firefox11_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox11_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox11_win8_1->desiredCapabilities['version'] = "11";

        $this->devices->sl_firefox10_win8_1 = new BaseObject;
        $this->devices->sl_firefox10_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox10_win8_1->browser = 'firefox';
        $this->devices->sl_firefox10_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox10_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox10_win8_1->desiredCapabilities['version'] = "10";

        $this->devices->sl_firefox9_win8_1 = new BaseObject;
        $this->devices->sl_firefox9_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox9_win8_1->browser = 'firefox';
        $this->devices->sl_firefox9_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox9_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox9_win8_1->desiredCapabilities['version'] = "9";

        $this->devices->sl_firefox8_win8_1 = new BaseObject;
        $this->devices->sl_firefox8_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox8_win8_1->browser = 'firefox';
        $this->devices->sl_firefox8_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox8_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox8_win8_1->desiredCapabilities['version'] = "8";

        $this->devices->sl_firefox7_win8_1 = new BaseObject;
        $this->devices->sl_firefox7_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox7_win8_1->browser = 'firefox';
        $this->devices->sl_firefox7_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox7_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox7_win8_1->desiredCapabilities['version'] = "7";

        $this->devices->sl_firefox6_win8_1 = new BaseObject;
        $this->devices->sl_firefox6_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox6_win8_1->browser = 'firefox';
        $this->devices->sl_firefox6_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox6_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox6_win8_1->desiredCapabilities['version'] = "6";

        $this->devices->sl_firefox5_win8_1 = new BaseObject;
        $this->devices->sl_firefox5_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox5_win8_1->browser = 'firefox';
        $this->devices->sl_firefox5_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox5_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox5_win8_1->desiredCapabilities['version'] = "5";

        $this->devices->sl_firefox4_win8_1 = new BaseObject;
        $this->devices->sl_firefox4_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox4_win8_1->browser = 'firefox';
        $this->devices->sl_firefox4_win8_1->desiredCapabilities = array();
        $this->devices->sl_firefox4_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_firefox4_win8_1->desiredCapabilities['version'] = "4";

        $this->devices->sl_chrome31_win8_1 = new BaseObject;
        $this->devices->sl_chrome31_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome31_win8_1->browser = 'chrome';
        $this->devices->sl_chrome31_win8_1->desiredCapabilities = array();
        $this->devices->sl_chrome31_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_chrome31_win8_1->desiredCapabilities['version'] = "31";

        $this->devices->sl_chrome30_win8_1 = new BaseObject;
        $this->devices->sl_chrome30_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome30_win8_1->browser = 'chrome';
        $this->devices->sl_chrome30_win8_1->desiredCapabilities = array();
        $this->devices->sl_chrome30_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_chrome30_win8_1->desiredCapabilities['version'] = "30";

        $this->devices->sl_chrome29_win8_1 = new BaseObject;
        $this->devices->sl_chrome29_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome29_win8_1->browser = 'chrome';
        $this->devices->sl_chrome29_win8_1->desiredCapabilities = array();
        $this->devices->sl_chrome29_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_chrome29_win8_1->desiredCapabilities['version'] = "29";

        $this->devices->sl_chrome28_win8_1 = new BaseObject;
        $this->devices->sl_chrome28_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome28_win8_1->browser = 'chrome';
        $this->devices->sl_chrome28_win8_1->desiredCapabilities = array();
        $this->devices->sl_chrome28_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_chrome28_win8_1->desiredCapabilities['version'] = "28";

        $this->devices->sl_chrome27_win8_1 = new BaseObject;
        $this->devices->sl_chrome27_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome27_win8_1->browser = 'chrome';
        $this->devices->sl_chrome27_win8_1->desiredCapabilities = array();
        $this->devices->sl_chrome27_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_chrome27_win8_1->desiredCapabilities['version'] = "27";

        $this->devices->sl_chrome26_win8_1 = new BaseObject;
        $this->devices->sl_chrome26_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome26_win8_1->browser = 'chrome';
        $this->devices->sl_chrome26_win8_1->desiredCapabilities = array();
        $this->devices->sl_chrome26_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_chrome26_win8_1->desiredCapabilities['version'] = "26";

        $this->devices->sl_ie11_win8_1 = new BaseObject;
        $this->devices->sl_ie11_win8_1->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_ie11_win8_1->browser = 'internet explorer';
        $this->devices->sl_ie11_win8_1->desiredCapabilities = array();
        $this->devices->sl_ie11_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->devices->sl_ie11_win8_1->desiredCapabilities['version'] = "11";

        # Windows 8.0
        $this->devices->sl_firefox25_win8 = new BaseObject;
        $this->devices->sl_firefox25_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox25_win8->browser = 'firefox';
        $this->devices->sl_firefox25_win8->desiredCapabilities = array();
        $this->devices->sl_firefox25_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox25_win8->desiredCapabilities['version'] = "25";

        $this->devices->sl_firefox24_win8 = new BaseObject;
        $this->devices->sl_firefox24_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox24_win8->browser = 'firefox';
        $this->devices->sl_firefox24_win8->desiredCapabilities = array();
        $this->devices->sl_firefox24_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox24_win8->desiredCapabilities['version'] = "24";

        $this->devices->sl_firefox23_win8 = new BaseObject;
        $this->devices->sl_firefox23_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox23_win8->browser = 'firefox';
        $this->devices->sl_firefox23_win8->desiredCapabilities = array();
        $this->devices->sl_firefox23_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox23_win8->desiredCapabilities['version'] = "23";

        $this->devices->sl_firefox22_win8 = new BaseObject;
        $this->devices->sl_firefox22_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox22_win8->browser = 'firefox';
        $this->devices->sl_firefox22_win8->desiredCapabilities = array();
        $this->devices->sl_firefox22_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox22_win8->desiredCapabilities['version'] = "22";

        $this->devices->sl_firefox21_win8 = new BaseObject;
        $this->devices->sl_firefox21_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox21_win8->browser = 'firefox';
        $this->devices->sl_firefox21_win8->desiredCapabilities = array();
        $this->devices->sl_firefox21_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox21_win8->desiredCapabilities['version'] = "21";

        $this->devices->sl_firefox20_win8 = new BaseObject;
        $this->devices->sl_firefox20_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox20_win8->browser = 'firefox';
        $this->devices->sl_firefox20_win8->desiredCapabilities = array();
        $this->devices->sl_firefox20_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox20_win8->desiredCapabilities['version'] = "20";

        $this->devices->sl_firefox19_win8 = new BaseObject;
        $this->devices->sl_firefox19_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox19_win8->browser = 'firefox';
        $this->devices->sl_firefox19_win8->desiredCapabilities = array();
        $this->devices->sl_firefox19_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox19_win8->desiredCapabilities['version'] = "19";

        $this->devices->sl_firefox18_win8 = new BaseObject;
        $this->devices->sl_firefox18_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox18_win8->browser = 'firefox';
        $this->devices->sl_firefox18_win8->desiredCapabilities = array();
        $this->devices->sl_firefox18_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox18_win8->desiredCapabilities['version'] = "18";

        $this->devices->sl_firefox17_win8 = new BaseObject;
        $this->devices->sl_firefox17_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox17_win8->browser = 'firefox';
        $this->devices->sl_firefox17_win8->desiredCapabilities = array();
        $this->devices->sl_firefox17_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox17_win8->desiredCapabilities['version'] = "17";

        $this->devices->sl_firefox16_win8 = new BaseObject;
        $this->devices->sl_firefox16_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox16_win8->browser = 'firefox';
        $this->devices->sl_firefox16_win8->desiredCapabilities = array();
        $this->devices->sl_firefox16_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox16_win8->desiredCapabilities['version'] = "16";

        $this->devices->sl_firefox15_win8 = new BaseObject;
        $this->devices->sl_firefox15_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox15_win8->browser = 'firefox';
        $this->devices->sl_firefox15_win8->desiredCapabilities = array();
        $this->devices->sl_firefox15_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox15_win8->desiredCapabilities['version'] = "15";

        $this->devices->sl_firefox14_win8 = new BaseObject;
        $this->devices->sl_firefox14_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox14_win8->browser = 'firefox';
        $this->devices->sl_firefox14_win8->desiredCapabilities = array();
        $this->devices->sl_firefox14_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox14_win8->desiredCapabilities['version'] = "14";

        $this->devices->sl_firefox13_win8 = new BaseObject;
        $this->devices->sl_firefox13_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox13_win8->browser = 'firefox';
        $this->devices->sl_firefox13_win8->desiredCapabilities = array();
        $this->devices->sl_firefox13_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox13_win8->desiredCapabilities['version'] = "13";

        $this->devices->sl_firefox12_win8 = new BaseObject;
        $this->devices->sl_firefox12_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox12_win8->browser = 'firefox';
        $this->devices->sl_firefox12_win8->desiredCapabilities = array();
        $this->devices->sl_firefox12_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox12_win8->desiredCapabilities['version'] = "12";

        $this->devices->sl_firefox11_win8 = new BaseObject;
        $this->devices->sl_firefox11_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox11_win8->browser = 'firefox';
        $this->devices->sl_firefox11_win8->desiredCapabilities = array();
        $this->devices->sl_firefox11_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox11_win8->desiredCapabilities['version'] = "11";

        $this->devices->sl_firefox10_win8 = new BaseObject;
        $this->devices->sl_firefox10_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox10_win8->browser = 'firefox';
        $this->devices->sl_firefox10_win8->desiredCapabilities = array();
        $this->devices->sl_firefox10_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox10_win8->desiredCapabilities['version'] = "10";

        $this->devices->sl_firefox9_win8 = new BaseObject;
        $this->devices->sl_firefox9_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox9_win8->browser = 'firefox';
        $this->devices->sl_firefox9_win8->desiredCapabilities = array();
        $this->devices->sl_firefox9_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox9_win8->desiredCapabilities['version'] = "9";

        $this->devices->sl_firefox8_win8 = new BaseObject;
        $this->devices->sl_firefox8_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox8_win8->browser = 'firefox';
        $this->devices->sl_firefox8_win8->desiredCapabilities = array();
        $this->devices->sl_firefox8_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox8_win8->desiredCapabilities['version'] = "8";

        $this->devices->sl_firefox7_win8 = new BaseObject;
        $this->devices->sl_firefox7_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox7_win8->browser = 'firefox';
        $this->devices->sl_firefox7_win8->desiredCapabilities = array();
        $this->devices->sl_firefox7_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox7_win8->desiredCapabilities['version'] = "7";

        $this->devices->sl_firefox6_win8 = new BaseObject;
        $this->devices->sl_firefox6_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox6_win8->browser = 'firefox';
        $this->devices->sl_firefox6_win8->desiredCapabilities = array();
        $this->devices->sl_firefox6_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox6_win8->desiredCapabilities['version'] = "6";

        $this->devices->sl_firefox5_win8 = new BaseObject;
        $this->devices->sl_firefox5_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox5_win8->browser = 'firefox';
        $this->devices->sl_firefox5_win8->desiredCapabilities = array();
        $this->devices->sl_firefox5_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox5_win8->desiredCapabilities['version'] = "5";

        $this->devices->sl_firefox4_win8 = new BaseObject;
        $this->devices->sl_firefox4_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox4_win8->browser = 'firefox';
        $this->devices->sl_firefox4_win8->desiredCapabilities = array();
        $this->devices->sl_firefox4_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_firefox4_win8->desiredCapabilities['version'] = "4";

        $this->devices->sl_chrome31_win8 = new BaseObject;
        $this->devices->sl_chrome31_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome31_win8->browser = 'chrome';
        $this->devices->sl_chrome31_win8->desiredCapabilities = array();
        $this->devices->sl_chrome31_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_chrome31_win8->desiredCapabilities['version'] = "31";

        $this->devices->sl_chrome30_win8 = new BaseObject;
        $this->devices->sl_chrome30_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome30_win8->browser = 'chrome';
        $this->devices->sl_chrome30_win8->desiredCapabilities = array();
        $this->devices->sl_chrome30_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_chrome30_win8->desiredCapabilities['version'] = "30";

        $this->devices->sl_chrome29_win8 = new BaseObject;
        $this->devices->sl_chrome29_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome29_win8->browser = 'chrome';
        $this->devices->sl_chrome29_win8->desiredCapabilities = array();
        $this->devices->sl_chrome29_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_chrome29_win8->desiredCapabilities['version'] = "29";

        $this->devices->sl_chrome28_win8 = new BaseObject;
        $this->devices->sl_chrome28_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome28_win8->browser = 'chrome';
        $this->devices->sl_chrome28_win8->desiredCapabilities = array();
        $this->devices->sl_chrome28_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_chrome28_win8->desiredCapabilities['version'] = "28";

        $this->devices->sl_chrome27_win8 = new BaseObject;
        $this->devices->sl_chrome27_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome27_win8->browser = 'chrome';
        $this->devices->sl_chrome27_win8->desiredCapabilities = array();
        $this->devices->sl_chrome27_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_chrome27_win8->desiredCapabilities['version'] = "27";

        $this->devices->sl_chrome26_win8 = new BaseObject;
        $this->devices->sl_chrome26_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome26_win8->browser = 'chrome';
        $this->devices->sl_chrome26_win8->desiredCapabilities = array();
        $this->devices->sl_chrome26_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_chrome26_win8->desiredCapabilities['version'] = "26";

        $this->devices->sl_ie10_win8 = new BaseObject;
        $this->devices->sl_ie10_win8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_ie10_win8->browser = 'internet explorer';
        $this->devices->sl_ie10_win8->desiredCapabilities = array();
        $this->devices->sl_ie10_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->devices->sl_ie10_win8->desiredCapabilities['version'] = "10";

        # Windows 7
        $this->devices->sl_firefox25_win7 = new BaseObject;
        $this->devices->sl_firefox25_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox25_win7->browser = 'firefox';
        $this->devices->sl_firefox25_win7->desiredCapabilities = array();
        $this->devices->sl_firefox25_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox25_win7->desiredCapabilities['version'] = "25";

        $this->devices->sl_firefox24_win7 = new BaseObject;
        $this->devices->sl_firefox24_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox24_win7->browser = 'firefox';
        $this->devices->sl_firefox24_win7->desiredCapabilities = array();
        $this->devices->sl_firefox24_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox24_win7->desiredCapabilities['version'] = "24";

        $this->devices->sl_firefox23_win7 = new BaseObject;
        $this->devices->sl_firefox23_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox23_win7->browser = 'firefox';
        $this->devices->sl_firefox23_win7->desiredCapabilities = array();
        $this->devices->sl_firefox23_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox23_win7->desiredCapabilities['version'] = "23";

        $this->devices->sl_firefox22_win7 = new BaseObject;
        $this->devices->sl_firefox22_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox22_win7->browser = 'firefox';
        $this->devices->sl_firefox22_win7->desiredCapabilities = array();
        $this->devices->sl_firefox22_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox22_win7->desiredCapabilities['version'] = "22";

        $this->devices->sl_firefox21_win7 = new BaseObject;
        $this->devices->sl_firefox21_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox21_win7->browser = 'firefox';
        $this->devices->sl_firefox21_win7->desiredCapabilities = array();
        $this->devices->sl_firefox21_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox21_win7->desiredCapabilities['version'] = "21";

        $this->devices->sl_firefox20_win7 = new BaseObject;
        $this->devices->sl_firefox20_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox20_win7->browser = 'firefox';
        $this->devices->sl_firefox20_win7->desiredCapabilities = array();
        $this->devices->sl_firefox20_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox20_win7->desiredCapabilities['version'] = "20";

        $this->devices->sl_firefox19_win7 = new BaseObject;
        $this->devices->sl_firefox19_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox19_win7->browser = 'firefox';
        $this->devices->sl_firefox19_win7->desiredCapabilities = array();
        $this->devices->sl_firefox19_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox19_win7->desiredCapabilities['version'] = "19";

        $this->devices->sl_firefox18_win7 = new BaseObject;
        $this->devices->sl_firefox18_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox18_win7->browser = 'firefox';
        $this->devices->sl_firefox18_win7->desiredCapabilities = array();
        $this->devices->sl_firefox18_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox18_win7->desiredCapabilities['version'] = "18";

        $this->devices->sl_firefox17_win7 = new BaseObject;
        $this->devices->sl_firefox17_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox17_win7->browser = 'firefox';
        $this->devices->sl_firefox17_win7->desiredCapabilities = array();
        $this->devices->sl_firefox17_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox17_win7->desiredCapabilities['version'] = "17";

        $this->devices->sl_firefox16_win7 = new BaseObject;
        $this->devices->sl_firefox16_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox16_win7->browser = 'firefox';
        $this->devices->sl_firefox16_win7->desiredCapabilities = array();
        $this->devices->sl_firefox16_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox16_win7->desiredCapabilities['version'] = "16";

        $this->devices->sl_firefox15_win7 = new BaseObject;
        $this->devices->sl_firefox15_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox15_win7->browser = 'firefox';
        $this->devices->sl_firefox15_win7->desiredCapabilities = array();
        $this->devices->sl_firefox15_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox15_win7->desiredCapabilities['version'] = "15";

        $this->devices->sl_firefox14_win7 = new BaseObject;
        $this->devices->sl_firefox14_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox14_win7->browser = 'firefox';
        $this->devices->sl_firefox14_win7->desiredCapabilities = array();
        $this->devices->sl_firefox14_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox14_win7->desiredCapabilities['version'] = "14";

        $this->devices->sl_firefox13_win7 = new BaseObject;
        $this->devices->sl_firefox13_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox13_win7->browser = 'firefox';
        $this->devices->sl_firefox13_win7->desiredCapabilities = array();
        $this->devices->sl_firefox13_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox13_win7->desiredCapabilities['version'] = "13";

        $this->devices->sl_firefox12_win7 = new BaseObject;
        $this->devices->sl_firefox12_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox12_win7->browser = 'firefox';
        $this->devices->sl_firefox12_win7->desiredCapabilities = array();
        $this->devices->sl_firefox12_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox12_win7->desiredCapabilities['version'] = "12";

        $this->devices->sl_firefox11_win7 = new BaseObject;
        $this->devices->sl_firefox11_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox11_win7->browser = 'firefox';
        $this->devices->sl_firefox11_win7->desiredCapabilities = array();
        $this->devices->sl_firefox11_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox11_win7->desiredCapabilities['version'] = "11";

        $this->devices->sl_firefox10_win7 = new BaseObject;
        $this->devices->sl_firefox10_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox10_win7->browser = 'firefox';
        $this->devices->sl_firefox10_win7->desiredCapabilities = array();
        $this->devices->sl_firefox10_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox10_win7->desiredCapabilities['version'] = "10";

        $this->devices->sl_firefox9_win7 = new BaseObject;
        $this->devices->sl_firefox9_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox9_win7->browser = 'firefox';
        $this->devices->sl_firefox9_win7->desiredCapabilities = array();
        $this->devices->sl_firefox9_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox9_win7->desiredCapabilities['version'] = "9";

        $this->devices->sl_firefox8_win7 = new BaseObject;
        $this->devices->sl_firefox8_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox8_win7->browser = 'firefox';
        $this->devices->sl_firefox8_win7->desiredCapabilities = array();
        $this->devices->sl_firefox8_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox8_win7->desiredCapabilities['version'] = "8";

        $this->devices->sl_firefox7_win7 = new BaseObject;
        $this->devices->sl_firefox7_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox7_win7->browser = 'firefox';
        $this->devices->sl_firefox7_win7->desiredCapabilities = array();
        $this->devices->sl_firefox7_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox7_win7->desiredCapabilities['version'] = "7";

        $this->devices->sl_firefox6_win7 = new BaseObject;
        $this->devices->sl_firefox6_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox6_win7->browser = 'firefox';
        $this->devices->sl_firefox6_win7->desiredCapabilities = array();
        $this->devices->sl_firefox6_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox6_win7->desiredCapabilities['version'] = "6";

        $this->devices->sl_firefox5_win7 = new BaseObject;
        $this->devices->sl_firefox5_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox5_win7->browser = 'firefox';
        $this->devices->sl_firefox5_win7->desiredCapabilities = array();
        $this->devices->sl_firefox5_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox5_win7->desiredCapabilities['version'] = "5";

        $this->devices->sl_firefox4_win7 = new BaseObject;
        $this->devices->sl_firefox4_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox4_win7->browser = 'firefox';
        $this->devices->sl_firefox4_win7->desiredCapabilities = array();
        $this->devices->sl_firefox4_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_firefox4_win7->desiredCapabilities['version'] = "4";

        $this->devices->sl_chrome31_win7 = new BaseObject;
        $this->devices->sl_chrome31_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome31_win7->browser = 'chrome';
        $this->devices->sl_chrome31_win7->desiredCapabilities = array();
        $this->devices->sl_chrome31_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_chrome31_win7->desiredCapabilities['version'] = "31";

        $this->devices->sl_chrome30_win7 = new BaseObject;
        $this->devices->sl_chrome30_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome30_win7->browser = 'chrome';
        $this->devices->sl_chrome30_win7->desiredCapabilities = array();
        $this->devices->sl_chrome30_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_chrome30_win7->desiredCapabilities['version'] = "30";

        $this->devices->sl_chrome29_win7 = new BaseObject;
        $this->devices->sl_chrome29_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome29_win7->browser = 'chrome';
        $this->devices->sl_chrome29_win7->desiredCapabilities = array();
        $this->devices->sl_chrome29_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_chrome29_win7->desiredCapabilities['version'] = "29";

        $this->devices->sl_chrome28_win7 = new BaseObject;
        $this->devices->sl_chrome28_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome28_win7->browser = 'chrome';
        $this->devices->sl_chrome28_win7->desiredCapabilities = array();
        $this->devices->sl_chrome28_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_chrome28_win7->desiredCapabilities['version'] = "28";

        $this->devices->sl_chrome27_win7 = new BaseObject;
        $this->devices->sl_chrome27_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome27_win7->browser = 'chrome';
        $this->devices->sl_chrome27_win7->desiredCapabilities = array();
        $this->devices->sl_chrome27_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_chrome27_win7->desiredCapabilities['version'] = "27";

        $this->devices->sl_chrome26_win7 = new BaseObject;
        $this->devices->sl_chrome26_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome26_win7->browser = 'chrome';
        $this->devices->sl_chrome26_win7->desiredCapabilities = array();
        $this->devices->sl_chrome26_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_chrome26_win7->desiredCapabilities['version'] = "26";

        $this->devices->sl_ie10_win7 = new BaseObject;
        $this->devices->sl_ie10_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_ie10_win7->browser = 'internet explorer';
        $this->devices->sl_ie10_win7->desiredCapabilities = array();
        $this->devices->sl_ie10_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_ie10_win7->desiredCapabilities['version'] = "10";

        $this->devices->sl_ie9_win7 = new BaseObject;
        $this->devices->sl_ie9_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_ie9_win7->browser = 'internet explorer';
        $this->devices->sl_ie9_win7->desiredCapabilities = array();
        $this->devices->sl_ie9_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_ie9_win7->desiredCapabilities['version'] = "9";

        $this->devices->sl_ie8_win7 = new BaseObject;
        $this->devices->sl_ie8_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_ie8_win7->browser = 'internet explorer';
        $this->devices->sl_ie8_win7->desiredCapabilities = array();
        $this->devices->sl_ie8_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_ie8_win7->desiredCapabilities['version'] = "8";

        $this->devices->sl_opera12_win7 = new BaseObject;
        $this->devices->sl_opera12_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_opera12_win7->browser = 'opera';
        $this->devices->sl_opera12_win7->desiredCapabilities = array();
        $this->devices->sl_opera12_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_opera12_win7->desiredCapabilities['version'] = "12";

        $this->devices->sl_opera11_win7 = new BaseObject;
        $this->devices->sl_opera11_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_opera11_win7->browser = 'opera';
        $this->devices->sl_opera11_win7->desiredCapabilities = array();
        $this->devices->sl_opera11_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_opera11_win7->desiredCapabilities['version'] = "11";

        $this->devices->sl_safari5_win7 = new BaseObject;
        $this->devices->sl_safari5_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari5_win7->browser = 'safari';
        $this->devices->sl_safari5_win7->desiredCapabilities = array();
        $this->devices->sl_safari5_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->devices->sl_safari5_win7->desiredCapabilities['version'] = "5";

        # Windows XP
        $this->devices->sl_firefox25_winxp = new BaseObject;
        $this->devices->sl_firefox25_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox25_winxp->browser = 'firefox';
        $this->devices->sl_firefox25_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox25_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox25_winxp->desiredCapabilities['version'] = "25";

        $this->devices->sl_firefox24_winxp = new BaseObject;
        $this->devices->sl_firefox24_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox24_winxp->browser = 'firefox';
        $this->devices->sl_firefox24_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox24_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox24_winxp->desiredCapabilities['version'] = "24";

        $this->devices->sl_firefox23_winxp = new BaseObject;
        $this->devices->sl_firefox23_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox23_winxp->browser = 'firefox';
        $this->devices->sl_firefox23_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox23_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox23_winxp->desiredCapabilities['version'] = "23";

        $this->devices->sl_firefox22_winxp = new BaseObject;
        $this->devices->sl_firefox22_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox22_winxp->browser = 'firefox';
        $this->devices->sl_firefox22_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox22_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox22_winxp->desiredCapabilities['version'] = "22";

        $this->devices->sl_firefox21_winxp = new BaseObject;
        $this->devices->sl_firefox21_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox21_winxp->browser = 'firefox';
        $this->devices->sl_firefox21_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox21_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox21_winxp->desiredCapabilities['version'] = "21";

        $this->devices->sl_firefox20_winxp = new BaseObject;
        $this->devices->sl_firefox20_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox20_winxp->browser = 'firefox';
        $this->devices->sl_firefox20_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox20_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox20_winxp->desiredCapabilities['version'] = "20";

        $this->devices->sl_firefox19_winxp = new BaseObject;
        $this->devices->sl_firefox19_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox19_winxp->browser = 'firefox';
        $this->devices->sl_firefox19_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox19_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox19_winxp->desiredCapabilities['version'] = "19";

        $this->devices->sl_firefox18_winxp = new BaseObject;
        $this->devices->sl_firefox18_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox18_winxp->browser = 'firefox';
        $this->devices->sl_firefox18_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox18_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox18_winxp->desiredCapabilities['version'] = "18";

        $this->devices->sl_firefox17_winxp = new BaseObject;
        $this->devices->sl_firefox17_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox17_winxp->browser = 'firefox';
        $this->devices->sl_firefox17_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox17_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox17_winxp->desiredCapabilities['version'] = "17";

        $this->devices->sl_firefox16_winxp = new BaseObject;
        $this->devices->sl_firefox16_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox16_winxp->browser = 'firefox';
        $this->devices->sl_firefox16_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox16_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox16_winxp->desiredCapabilities['version'] = "16";

        $this->devices->sl_firefox15_winxp = new BaseObject;
        $this->devices->sl_firefox15_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox15_winxp->browser = 'firefox';
        $this->devices->sl_firefox15_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox15_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox15_winxp->desiredCapabilities['version'] = "15";

        $this->devices->sl_firefox14_winxp = new BaseObject;
        $this->devices->sl_firefox14_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox14_winxp->browser = 'firefox';
        $this->devices->sl_firefox14_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox14_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox14_winxp->desiredCapabilities['version'] = "14";

        $this->devices->sl_firefox13_winxp = new BaseObject;
        $this->devices->sl_firefox13_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox13_winxp->browser = 'firefox';
        $this->devices->sl_firefox13_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox13_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox13_winxp->desiredCapabilities['version'] = "13";

        $this->devices->sl_firefox12_winxp = new BaseObject;
        $this->devices->sl_firefox12_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox12_winxp->browser = 'firefox';
        $this->devices->sl_firefox12_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox12_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox12_winxp->desiredCapabilities['version'] = "12";

        $this->devices->sl_firefox11_winxp = new BaseObject;
        $this->devices->sl_firefox11_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox11_winxp->browser = 'firefox';
        $this->devices->sl_firefox11_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox11_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox11_winxp->desiredCapabilities['version'] = "11";

        $this->devices->sl_firefox10_winxp = new BaseObject;
        $this->devices->sl_firefox10_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox10_winxp->browser = 'firefox';
        $this->devices->sl_firefox10_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox10_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox10_winxp->desiredCapabilities['version'] = "10";

        $this->devices->sl_firefox9_winxp = new BaseObject;
        $this->devices->sl_firefox9_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox9_winxp->browser = 'firefox';
        $this->devices->sl_firefox9_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox9_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox9_winxp->desiredCapabilities['version'] = "9";

        $this->devices->sl_firefox8_winxp = new BaseObject;
        $this->devices->sl_firefox8_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox8_winxp->browser = 'firefox';
        $this->devices->sl_firefox8_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox8_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox8_winxp->desiredCapabilities['version'] = "8";

        $this->devices->sl_firefox7_winxp = new BaseObject;
        $this->devices->sl_firefox7_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox7_winxp->browser = 'firefox';
        $this->devices->sl_firefox7_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox7_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox7_winxp->desiredCapabilities['version'] = "7";

        $this->devices->sl_firefox6_winxp = new BaseObject;
        $this->devices->sl_firefox6_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox6_winxp->browser = 'firefox';
        $this->devices->sl_firefox6_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox6_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox6_winxp->desiredCapabilities['version'] = "6";

        $this->devices->sl_firefox5_winxp = new BaseObject;
        $this->devices->sl_firefox5_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox5_winxp->browser = 'firefox';
        $this->devices->sl_firefox5_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox5_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox5_winxp->desiredCapabilities['version'] = "5";

        $this->devices->sl_firefox4_winxp = new BaseObject;
        $this->devices->sl_firefox4_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox4_winxp->browser = 'firefox';
        $this->devices->sl_firefox4_winxp->desiredCapabilities = array();
        $this->devices->sl_firefox4_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_firefox4_winxp->desiredCapabilities['version'] = "4";

        $this->devices->sl_chrome31_winxp = new BaseObject;
        $this->devices->sl_chrome31_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome31_winxp->browser = 'chrome';
        $this->devices->sl_chrome31_winxp->desiredCapabilities = array();
        $this->devices->sl_chrome31_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_chrome31_winxp->desiredCapabilities['version'] = "31";

        $this->devices->sl_chrome30_winxp = new BaseObject;
        $this->devices->sl_chrome30_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome30_winxp->browser = 'chrome';
        $this->devices->sl_chrome30_winxp->desiredCapabilities = array();
        $this->devices->sl_chrome30_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_chrome30_winxp->desiredCapabilities['version'] = "30";

        $this->devices->sl_chrome29_winxp = new BaseObject;
        $this->devices->sl_chrome29_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome29_winxp->browser = 'chrome';
        $this->devices->sl_chrome29_winxp->desiredCapabilities = array();
        $this->devices->sl_chrome29_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_chrome29_winxp->desiredCapabilities['version'] = "29";

        $this->devices->sl_chrome28_winxp = new BaseObject;
        $this->devices->sl_chrome28_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome28_winxp->browser = 'chrome';
        $this->devices->sl_chrome28_winxp->desiredCapabilities = array();
        $this->devices->sl_chrome28_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_chrome28_winxp->desiredCapabilities['version'] = "28";

        $this->devices->sl_chrome27_winxp = new BaseObject;
        $this->devices->sl_chrome27_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome27_winxp->browser = 'chrome';
        $this->devices->sl_chrome27_winxp->desiredCapabilities = array();
        $this->devices->sl_chrome27_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_chrome27_winxp->desiredCapabilities['version'] = "27";

        $this->devices->sl_chrome26_winxp = new BaseObject;
        $this->devices->sl_chrome26_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome26_winxp->browser = 'chrome';
        $this->devices->sl_chrome26_winxp->desiredCapabilities = array();
        $this->devices->sl_chrome26_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_chrome26_winxp->desiredCapabilities['version'] = "26";

        $this->devices->sl_ie8_winxp = new BaseObject;
        $this->devices->sl_ie8_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_ie8_winxp->browser = 'internet explorer';
        $this->devices->sl_ie8_winxp->desiredCapabilities = array();
        $this->devices->sl_ie8_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_ie8_winxp->desiredCapabilities['version'] = "8";

        $this->devices->sl_ie7_winxp = new BaseObject;
        $this->devices->sl_ie7_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_ie7_winxp->browser = 'internet explorer';
        $this->devices->sl_ie7_winxp->desiredCapabilities = array();
        $this->devices->sl_ie7_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_ie7_winxp->desiredCapabilities['version'] = "7";

        $this->devices->sl_ie6_winxp = new BaseObject;
        $this->devices->sl_ie6_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_ie6_winxp->browser = 'internet explorer';
        $this->devices->sl_ie6_winxp->desiredCapabilities = array();
        $this->devices->sl_ie6_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_ie6_winxp->desiredCapabilities['version'] = "6";

        $this->devices->sl_opera12_winxp = new BaseObject;
        $this->devices->sl_opera12_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_opera12_winxp->browser = 'opera';
        $this->devices->sl_opera12_winxp->desiredCapabilities = array();
        $this->devices->sl_opera12_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_opera12_winxp->desiredCapabilities['version'] = "12";

        $this->devices->sl_opera11_winxp = new BaseObject;
        $this->devices->sl_opera11_winxp->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_opera11_winxp->browser = 'opera';
        $this->devices->sl_opera11_winxp->desiredCapabilities = array();
        $this->devices->sl_opera11_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->devices->sl_opera11_winxp->desiredCapabilities['version'] = "11";

        # OSX 10.6 Snow Leopard
        $this->devices->sl_firefox25_osx10_6 = new BaseObject;
        $this->devices->sl_firefox25_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox25_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox25_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox25_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox25_osx10_6->desiredCapabilities['version'] = "25";

        $this->devices->sl_firefox24_osx10_6 = new BaseObject;
        $this->devices->sl_firefox24_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox24_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox24_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox24_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox24_osx10_6->desiredCapabilities['version'] = "24";

        $this->devices->sl_firefox23_osx10_6 = new BaseObject;
        $this->devices->sl_firefox23_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox23_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox23_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox23_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox23_osx10_6->desiredCapabilities['version'] = "23";

        $this->devices->sl_firefox22_osx10_6 = new BaseObject;
        $this->devices->sl_firefox22_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox22_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox22_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox22_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox22_osx10_6->desiredCapabilities['version'] = "22";

        $this->devices->sl_firefox21_osx10_6 = new BaseObject;
        $this->devices->sl_firefox21_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox21_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox21_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox21_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox21_osx10_6->desiredCapabilities['version'] = "21";

        $this->devices->sl_firefox20_osx10_6 = new BaseObject;
        $this->devices->sl_firefox20_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox20_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox20_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox20_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox20_osx10_6->desiredCapabilities['version'] = "20";

        $this->devices->sl_firefox19_osx10_6 = new BaseObject;
        $this->devices->sl_firefox19_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox19_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox19_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox19_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox19_osx10_6->desiredCapabilities['version'] = "19";

        $this->devices->sl_firefox18_osx10_6 = new BaseObject;
        $this->devices->sl_firefox18_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox18_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox18_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox18_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox18_osx10_6->desiredCapabilities['version'] = "18";

        $this->devices->sl_firefox17_osx10_6 = new BaseObject;
        $this->devices->sl_firefox17_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox17_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox17_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox17_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox17_osx10_6->desiredCapabilities['version'] = "17";

        $this->devices->sl_firefox16_osx10_6 = new BaseObject;
        $this->devices->sl_firefox16_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox16_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox16_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox16_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox16_osx10_6->desiredCapabilities['version'] = "16";

        $this->devices->sl_firefox15_osx10_6 = new BaseObject;
        $this->devices->sl_firefox15_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox15_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox15_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox15_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox15_osx10_6->desiredCapabilities['version'] = "15";

        $this->devices->sl_firefox14_osx10_6 = new BaseObject;
        $this->devices->sl_firefox14_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox14_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox14_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox14_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox14_osx10_6->desiredCapabilities['version'] = "14";

        $this->devices->sl_firefox13_osx10_6 = new BaseObject;
        $this->devices->sl_firefox13_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox13_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox13_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox13_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox13_osx10_6->desiredCapabilities['version'] = "13";

        $this->devices->sl_firefox12_osx10_6 = new BaseObject;
        $this->devices->sl_firefox12_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox12_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox12_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox12_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox12_osx10_6->desiredCapabilities['version'] = "12";

        $this->devices->sl_firefox11_osx10_6 = new BaseObject;
        $this->devices->sl_firefox11_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox11_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox11_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox11_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox11_osx10_6->desiredCapabilities['version'] = "11";

        $this->devices->sl_firefox10_osx10_6 = new BaseObject;
        $this->devices->sl_firefox10_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox10_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox10_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox10_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox10_osx10_6->desiredCapabilities['version'] = "10";

        $this->devices->sl_firefox9_osx10_6 = new BaseObject;
        $this->devices->sl_firefox9_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox9_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox9_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox9_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox9_osx10_6->desiredCapabilities['version'] = "9";

        $this->devices->sl_firefox8_osx10_6 = new BaseObject;
        $this->devices->sl_firefox8_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox8_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox8_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox8_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox8_osx10_6->desiredCapabilities['version'] = "8";

        $this->devices->sl_firefox7_osx10_6 = new BaseObject;
        $this->devices->sl_firefox7_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox7_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox7_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox7_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox7_osx10_6->desiredCapabilities['version'] = "7";

        $this->devices->sl_firefox6_osx10_6 = new BaseObject;
        $this->devices->sl_firefox6_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox6_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox6_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox6_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox6_osx10_6->desiredCapabilities['version'] = "6";

        $this->devices->sl_firefox5_osx10_6 = new BaseObject;
        $this->devices->sl_firefox5_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox5_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox5_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox5_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox5_osx10_6->desiredCapabilities['version'] = "5";

        $this->devices->sl_firefox4_osx10_6 = new BaseObject;
        $this->devices->sl_firefox4_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox4_osx10_6->browser = 'firefox';
        $this->devices->sl_firefox4_osx10_6->desiredCapabilities = array();
        $this->devices->sl_firefox4_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_firefox4_osx10_6->desiredCapabilities['version'] = "4";

        $this->devices->sl_chrome28_osx10_6 = new BaseObject;
        $this->devices->sl_chrome28_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome28_osx10_6->browser = 'chrome';
        $this->devices->sl_chrome28_osx10_6->desiredCapabilities = array();
        $this->devices->sl_chrome28_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_chrome28_osx10_6->desiredCapabilities['version'] = "28";

        $this->devices->sl_ie5_osx10_6 = new BaseObject;
        $this->devices->sl_ie5_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_ie5_osx10_6->browser = 'internet explorer';
        $this->devices->sl_ie5_osx10_6->desiredCapabilities = array();
        $this->devices->sl_ie5_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_ie5_osx10_6->desiredCapabilities['version'] = "5";

        # OSX 10.8 Mountain Lion
        $this->devices->sl_chrome27_osx10_8 = new BaseObject;
        $this->devices->sl_chrome27_osx10_8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome27_osx10_8->browser = 'chrome';
        $this->devices->sl_chrome27_osx10_8->desiredCapabilities = array();
        $this->devices->sl_chrome27_osx10_8->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_chrome27_osx10_8->desiredCapabilities['version'] = "27";

        $this->devices->sl_ie6_osx10_8 = new BaseObject;
        $this->devices->sl_ie6_osx10_8->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_ie6_osx10_8->browser = 'internet explorer';
        $this->devices->sl_ie6_osx10_8->desiredCapabilities = array();
        $this->devices->sl_ie6_osx10_8->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_ie6_osx10_8->desiredCapabilities['version'] = "6";

        # Linux
        $this->devices->sl_firefox25_linux = new BaseObject;
        $this->devices->sl_firefox25_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox25_linux->browser = 'firefox';
        $this->devices->sl_firefox25_linux->desiredCapabilities = array();
        $this->devices->sl_firefox25_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox25_linux->desiredCapabilities['version'] = "25";

        $this->devices->sl_firefox24_linux = new BaseObject;
        $this->devices->sl_firefox24_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox24_linux->browser = 'firefox';
        $this->devices->sl_firefox24_linux->desiredCapabilities = array();
        $this->devices->sl_firefox24_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox24_linux->desiredCapabilities['version'] = "24";

        $this->devices->sl_firefox23_linux = new BaseObject;
        $this->devices->sl_firefox23_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox23_linux->browser = 'firefox';
        $this->devices->sl_firefox23_linux->desiredCapabilities = array();
        $this->devices->sl_firefox23_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox23_linux->desiredCapabilities['version'] = "23";

        $this->devices->sl_firefox22_linux = new BaseObject;
        $this->devices->sl_firefox22_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox22_linux->browser = 'firefox';
        $this->devices->sl_firefox22_linux->desiredCapabilities = array();
        $this->devices->sl_firefox22_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox22_linux->desiredCapabilities['version'] = "22";

        $this->devices->sl_firefox21_linux = new BaseObject;
        $this->devices->sl_firefox21_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox21_linux->browser = 'firefox';
        $this->devices->sl_firefox21_linux->desiredCapabilities = array();
        $this->devices->sl_firefox21_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox21_linux->desiredCapabilities['version'] = "21";

        $this->devices->sl_firefox20_linux = new BaseObject;
        $this->devices->sl_firefox20_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox20_linux->browser = 'firefox';
        $this->devices->sl_firefox20_linux->desiredCapabilities = array();
        $this->devices->sl_firefox20_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox20_linux->desiredCapabilities['version'] = "20";

        $this->devices->sl_firefox19_linux = new BaseObject;
        $this->devices->sl_firefox19_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox19_linux->browser = 'firefox';
        $this->devices->sl_firefox19_linux->desiredCapabilities = array();
        $this->devices->sl_firefox19_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox19_linux->desiredCapabilities['version'] = "19";

        $this->devices->sl_firefox18_linux = new BaseObject;
        $this->devices->sl_firefox18_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox18_linux->browser = 'firefox';
        $this->devices->sl_firefox18_linux->desiredCapabilities = array();
        $this->devices->sl_firefox18_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox18_linux->desiredCapabilities['version'] = "18";

        $this->devices->sl_firefox17_linux = new BaseObject;
        $this->devices->sl_firefox17_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox17_linux->browser = 'firefox';
        $this->devices->sl_firefox17_linux->desiredCapabilities = array();
        $this->devices->sl_firefox17_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox17_linux->desiredCapabilities['version'] = "17";

        $this->devices->sl_firefox16_linux = new BaseObject;
        $this->devices->sl_firefox16_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox16_linux->browser = 'firefox';
        $this->devices->sl_firefox16_linux->desiredCapabilities = array();
        $this->devices->sl_firefox16_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox16_linux->desiredCapabilities['version'] = "16";

        $this->devices->sl_firefox15_linux = new BaseObject;
        $this->devices->sl_firefox15_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox15_linux->browser = 'firefox';
        $this->devices->sl_firefox15_linux->desiredCapabilities = array();
        $this->devices->sl_firefox15_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox15_linux->desiredCapabilities['version'] = "15";

        $this->devices->sl_firefox14_linux = new BaseObject;
        $this->devices->sl_firefox14_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox14_linux->browser = 'firefox';
        $this->devices->sl_firefox14_linux->desiredCapabilities = array();
        $this->devices->sl_firefox14_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox14_linux->desiredCapabilities['version'] = "14";

        $this->devices->sl_firefox13_linux = new BaseObject;
        $this->devices->sl_firefox13_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox13_linux->browser = 'firefox';
        $this->devices->sl_firefox13_linux->desiredCapabilities = array();
        $this->devices->sl_firefox13_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox13_linux->desiredCapabilities['version'] = "13";

        $this->devices->sl_firefox12_linux = new BaseObject;
        $this->devices->sl_firefox12_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox12_linux->browser = 'firefox';
        $this->devices->sl_firefox12_linux->desiredCapabilities = array();
        $this->devices->sl_firefox12_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox12_linux->desiredCapabilities['version'] = "12";

        $this->devices->sl_firefox11_linux = new BaseObject;
        $this->devices->sl_firefox11_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox11_linux->browser = 'firefox';
        $this->devices->sl_firefox11_linux->desiredCapabilities = array();
        $this->devices->sl_firefox11_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox11_linux->desiredCapabilities['version'] = "11";

        $this->devices->sl_firefox10_linux = new BaseObject;
        $this->devices->sl_firefox10_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox10_linux->browser = 'firefox';
        $this->devices->sl_firefox10_linux->desiredCapabilities = array();
        $this->devices->sl_firefox10_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox10_linux->desiredCapabilities['version'] = "10";

        $this->devices->sl_firefox9_linux = new BaseObject;
        $this->devices->sl_firefox9_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox9_linux->browser = 'firefox';
        $this->devices->sl_firefox9_linux->desiredCapabilities = array();
        $this->devices->sl_firefox9_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox9_linux->desiredCapabilities['version'] = "9";

        $this->devices->sl_firefox8_linux = new BaseObject;
        $this->devices->sl_firefox8_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox8_linux->browser = 'firefox';
        $this->devices->sl_firefox8_linux->desiredCapabilities = array();
        $this->devices->sl_firefox8_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox8_linux->desiredCapabilities['version'] = "8";

        $this->devices->sl_firefox7_linux = new BaseObject;
        $this->devices->sl_firefox7_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox7_linux->browser = 'firefox';
        $this->devices->sl_firefox7_linux->desiredCapabilities = array();
        $this->devices->sl_firefox7_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox7_linux->desiredCapabilities['version'] = "7";

        $this->devices->sl_firefox6_linux = new BaseObject;
        $this->devices->sl_firefox6_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox6_linux->browser = 'firefox';
        $this->devices->sl_firefox6_linux->desiredCapabilities = array();
        $this->devices->sl_firefox6_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox6_linux->desiredCapabilities['version'] = "6";

        $this->devices->sl_firefox5_linux = new BaseObject;
        $this->devices->sl_firefox5_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox5_linux->browser = 'firefox';
        $this->devices->sl_firefox5_linux->desiredCapabilities = array();
        $this->devices->sl_firefox5_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox5_linux->desiredCapabilities['version'] = "5";

        $this->devices->sl_firefox4_linux = new BaseObject;
        $this->devices->sl_firefox4_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_firefox4_linux->browser = 'firefox';
        $this->devices->sl_firefox4_linux->desiredCapabilities = array();
        $this->devices->sl_firefox4_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_firefox4_linux->desiredCapabilities['version'] = "4";

        $this->devices->sl_chrome31_win7 = new BaseObject;
        $this->devices->sl_chrome31_win7->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome31_win7->browser = 'chrome';
        $this->devices->sl_chrome31_win7->desiredCapabilities = array();
        $this->devices->sl_chrome31_win7->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_chrome31_win7->desiredCapabilities['version'] = "31";

        $this->devices->sl_chrome30_linux = new BaseObject;
        $this->devices->sl_chrome30_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome30_linux->browser = 'chrome';
        $this->devices->sl_chrome30_linux->desiredCapabilities = array();
        $this->devices->sl_chrome30_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_chrome30_linux->desiredCapabilities['version'] = "30";

        $this->devices->sl_chrome29_linux = new BaseObject;
        $this->devices->sl_chrome29_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome29_linux->browser = 'chrome';
        $this->devices->sl_chrome29_linux->desiredCapabilities = array();
        $this->devices->sl_chrome29_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_chrome29_linux->desiredCapabilities['version'] = "29";

        $this->devices->sl_chrome28_linux = new BaseObject;
        $this->devices->sl_chrome28_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome28_linux->browser = 'chrome';
        $this->devices->sl_chrome28_linux->desiredCapabilities = array();
        $this->devices->sl_chrome28_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_chrome28_linux->desiredCapabilities['version'] = "28";

        $this->devices->sl_chrome27_linux = new BaseObject;
        $this->devices->sl_chrome27_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome27_linux->browser = 'chrome';
        $this->devices->sl_chrome27_linux->desiredCapabilities = array();
        $this->devices->sl_chrome27_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_chrome27_linux->desiredCapabilities['version'] = "27";

        $this->devices->sl_chrome26_linux = new BaseObject;
        $this->devices->sl_chrome26_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_chrome26_linux->browser = 'chrome';
        $this->devices->sl_chrome26_linux->desiredCapabilities = array();
        $this->devices->sl_chrome26_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_chrome26_linux->desiredCapabilities['version'] = "26";

        $this->devices->sl_opera12_linux = new BaseObject;
        $this->devices->sl_opera12_linux->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_opera12_linux->browser = 'opera';
        $this->devices->sl_opera12_linux->desiredCapabilities = array();
        $this->devices->sl_opera12_linux->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_opera12_linux->desiredCapabilities['version'] = "12";

        # iOS - iPad
        $this->devices->sl_safari_ipad_ios6_1_portrait = new BaseObject;
        $this->devices->sl_safari_ipad_ios6_1_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_ipad_ios6_1_portrait->browser = 'ipad';
        $this->devices->sl_safari_ipad_ios6_1_portrait->desiredCapabilities = array();
        $this->devices->sl_safari_ipad_ios6_1_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_ipad_ios6_1_portrait->desiredCapabilities['version'] = "6.1";
        $this->devices->sl_safari_ipad_ios6_1_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_safari_ipad_ios6_1_landscape = new BaseObject;
        $this->devices->sl_safari_ipad_ios6_1_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_ipad_ios6_1_landscape->browser = 'ipad';
        $this->devices->sl_safari_ipad_ios6_1_landscape->desiredCapabilities = array();
        $this->devices->sl_safari_ipad_ios6_1_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_ipad_ios6_1_landscape->desiredCapabilities['version'] = "6.1";
        $this->devices->sl_safari_ipad_ios6_1_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->devices->sl_safari_ipad_ios6_0_portrait = new BaseObject;
        $this->devices->sl_safari_ipad_ios6_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_ipad_ios6_0_portrait->browser = 'ipad';
        $this->devices->sl_safari_ipad_ios6_0_portrait->desiredCapabilities = array();
        $this->devices->sl_safari_ipad_ios6_0_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_ipad_ios6_0_portrait->desiredCapabilities['version'] = "6.0";
        $this->devices->sl_safari_ipad_ios6_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_safari_ipad_ios6_0_landscape = new BaseObject;
        $this->devices->sl_safari_ipad_ios6_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_ipad_ios6_0_landscape->browser = 'ipad';
        $this->devices->sl_safari_ipad_ios6_0_landscape->desiredCapabilities = array();
        $this->devices->sl_safari_ipad_ios6_0_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_ipad_ios6_0_landscape->desiredCapabilities['version'] = "6.0";
        $this->devices->sl_safari_ipad_ios6_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->devices->sl_safari_ipad_ios5_1_portrait = new BaseObject;
        $this->devices->sl_safari_ipad_ios5_1_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_ipad_ios5_1_portrait->browser = 'ipad';
        $this->devices->sl_safari_ipad_ios5_1_portrait->desiredCapabilities = array();
        $this->devices->sl_safari_ipad_ios5_1_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_ipad_ios5_1_portrait->desiredCapabilities['version'] = "5.1";
        $this->devices->sl_safari_ipad_ios5_1_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_safari_ipad_ios5_1_landscape = new BaseObject;
        $this->devices->sl_safari_ipad_ios5_1_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_ipad_ios5_1_landscape->browser = 'ipad';
        $this->devices->sl_safari_ipad_ios5_1_landscape->desiredCapabilities = array();
        $this->devices->sl_safari_ipad_ios5_1_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_ipad_ios5_1_landscape->desiredCapabilities['version'] = "5.1";
        $this->devices->sl_safari_ipad_ios5_1_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->devices->sl_safari_ipad_ios5_0_portrait = new BaseObject;
        $this->devices->sl_safari_ipad_ios5_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_ipad_ios5_0_portrait->browser = 'ipad';
        $this->devices->sl_safari_ipad_ios5_0_portrait->desiredCapabilities = array();
        $this->devices->sl_safari_ipad_ios5_0_portrait->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_safari_ipad_ios5_0_portrait->desiredCapabilities['version'] = "5.0";
        $this->devices->sl_safari_ipad_ios5_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_safari_ipad_ios5_0_landscape = new BaseObject;
        $this->devices->sl_safari_ipad_ios5_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_ipad_ios5_0_landscape->browser = 'ipad';
        $this->devices->sl_safari_ipad_ios5_0_landscape->desiredCapabilities = array();
        $this->devices->sl_safari_ipad_ios5_0_landscape->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_safari_ipad_ios5_0_landscape->desiredCapabilities['version'] = "5.0";
        $this->devices->sl_safari_ipad_ios5_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->devices->sl_safari_ipad_ios4_portrait = new BaseObject;
        $this->devices->sl_safari_ipad_ios4_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_ipad_ios4_portrait->browser = 'ipad';
        $this->devices->sl_safari_ipad_ios4_portrait->desiredCapabilities = array();
        $this->devices->sl_safari_ipad_ios4_portrait->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_safari_ipad_ios4_portrait->desiredCapabilities['version'] = "4";
        $this->devices->sl_safari_ipad_ios4_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_safari_ipad_ios4_landscape = new BaseObject;
        $this->devices->sl_safari_ipad_ios4_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_ipad_ios4_landscape->browser = 'ipad';
        $this->devices->sl_safari_ipad_ios4_landscape->desiredCapabilities = array();
        $this->devices->sl_safari_ipad_ios4_landscape->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_safari_ipad_ios4_landscape->desiredCapabilities['version'] = "4";
        $this->devices->sl_safari_ipad_ios4_landscape->desiredCapabilities['device-orientation'] = "landscape";

        # iOS - iPhone
        $this->devices->sl_safari_iphone_ios6_1_portrait = new BaseObject;
        $this->devices->sl_safari_iphone_ios6_1_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_iphone_ios6_1_portrait->browser = 'iphone';
        $this->devices->sl_safari_iphone_ios6_1_portrait->desiredCapabilities = array();
        $this->devices->sl_safari_iphone_ios6_1_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_iphone_ios6_1_portrait->desiredCapabilities['version'] = "6.1";
        $this->devices->sl_safari_iphone_ios6_1_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_safari_iphone_ios6_1_landscape = new BaseObject;
        $this->devices->sl_safari_iphone_ios6_1_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_iphone_ios6_1_landscape->browser = 'iphone';
        $this->devices->sl_safari_iphone_ios6_1_landscape->desiredCapabilities = array();
        $this->devices->sl_safari_iphone_ios6_1_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_iphone_ios6_1_landscape->desiredCapabilities['version'] = "6.1";
        $this->devices->sl_safari_iphone_ios6_1_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->devices->sl_safari_iphone_ios6_0_portrait = new BaseObject;
        $this->devices->sl_safari_iphone_ios6_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_iphone_ios6_0_portrait->browser = 'iphone';
        $this->devices->sl_safari_iphone_ios6_0_portrait->desiredCapabilities = array();
        $this->devices->sl_safari_iphone_ios6_0_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_iphone_ios6_0_portrait->desiredCapabilities['version'] = "6.0";
        $this->devices->sl_safari_iphone_ios6_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_safari_iphone_ios6_0_landscape = new BaseObject;
        $this->devices->sl_safari_iphone_ios6_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_iphone_ios6_0_landscape->browser = 'iphone';
        $this->devices->sl_safari_iphone_ios6_0_landscape->desiredCapabilities = array();
        $this->devices->sl_safari_iphone_ios6_0_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_iphone_ios6_0_landscape->desiredCapabilities['version'] = "6.0";
        $this->devices->sl_safari_iphone_ios6_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->devices->sl_safari_iphone_ios5_1_portrait = new BaseObject;
        $this->devices->sl_safari_iphone_ios5_1_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_iphone_ios5_1_portrait->browser = 'iphone';
        $this->devices->sl_safari_iphone_ios5_1_portrait->desiredCapabilities = array();
        $this->devices->sl_safari_iphone_ios5_1_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_iphone_ios5_1_portrait->desiredCapabilities['version'] = "5.1";
        $this->devices->sl_safari_iphone_ios5_1_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_safari_iphone_ios5_1_landscape = new BaseObject;
        $this->devices->sl_safari_iphone_ios5_1_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_iphone_ios5_1_landscape->browser = 'iphone';
        $this->devices->sl_safari_iphone_ios5_1_landscape->desiredCapabilities = array();
        $this->devices->sl_safari_iphone_ios5_1_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->devices->sl_safari_iphone_ios5_1_landscape->desiredCapabilities['version'] = "5.1";
        $this->devices->sl_safari_iphone_ios5_1_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->devices->sl_safari_iphone_ios5_0_portrait = new BaseObject;
        $this->devices->sl_safari_iphone_ios5_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_iphone_ios5_0_portrait->browser = 'iphone';
        $this->devices->sl_safari_iphone_ios5_0_portrait->desiredCapabilities = array();
        $this->devices->sl_safari_iphone_ios5_0_portrait->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_safari_iphone_ios5_0_portrait->desiredCapabilities['version'] = "5.0";
        $this->devices->sl_safari_iphone_ios5_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_safari_iphone_ios5_0_landscape = new BaseObject;
        $this->devices->sl_safari_iphone_ios5_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_iphone_ios5_0_landscape->browser = 'iphone';
        $this->devices->sl_safari_iphone_ios5_0_landscape->desiredCapabilities = array();
        $this->devices->sl_safari_iphone_ios5_0_landscape->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_safari_iphone_ios5_0_landscape->desiredCapabilities['version'] = "5.0";
        $this->devices->sl_safari_iphone_ios5_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->devices->sl_safari_iphone_ios4_portrait = new BaseObject;
        $this->devices->sl_safari_iphone_ios4_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_iphone_ios4_portrait->browser = 'iphone';
        $this->devices->sl_safari_iphone_ios4_portrait->desiredCapabilities = array();
        $this->devices->sl_safari_iphone_ios4_portrait->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_safari_iphone_ios4_portrait->desiredCapabilities['version'] = "4";
        $this->devices->sl_safari_iphone_ios4_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_safari_iphone_ios4_landscape = new BaseObject;
        $this->devices->sl_safari_iphone_ios4_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_safari_iphone_ios4_landscape->browser = 'iphone';
        $this->devices->sl_safari_iphone_ios4_landscape->desiredCapabilities = array();
        $this->devices->sl_safari_iphone_ios4_landscape->desiredCapabilities['platform'] = "OS X 10.6";
        $this->devices->sl_safari_iphone_ios4_landscape->desiredCapabilities['version'] = "4";
        $this->devices->sl_safari_iphone_ios4_landscape->desiredCapabilities['device-orientation'] = "landscape";

        # android 4.0 - phone
        $this->devices->sl_android_phone_4_0_portrait = new BaseObject;
        $this->devices->sl_android_phone_4_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_android_phone_4_0_portrait->browser = 'android';
        $this->devices->sl_android_phone_4_0_portrait->desiredCapabilities = array();
        $this->devices->sl_android_phone_4_0_portrait->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_android_phone_4_0_portrait->desiredCapabilities['version'] = "4.0";
        $this->devices->sl_android_phone_4_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_android_phone_4_0_landscape = new BaseObject;
        $this->devices->sl_android_phone_4_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_android_phone_4_0_landscape->browser = 'android';
        $this->devices->sl_android_phone_4_0_landscape->desiredCapabilities = array();
        $this->devices->sl_android_phone_4_0_landscape->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_android_phone_4_0_landscape->desiredCapabilities['version'] = "4.0";
        $this->devices->sl_android_phone_4_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        # android 4.0 - tablet
        $this->devices->sl_android_tablet_4_0_portrait = new BaseObject;
        $this->devices->sl_android_tablet_4_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_android_tablet_4_0_portrait->browser = 'android';
        $this->devices->sl_android_tablet_4_0_portrait->desiredCapabilities = array();
        $this->devices->sl_android_tablet_4_0_portrait->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_android_tablet_4_0_portrait->desiredCapabilities['version'] = "4.0";
        $this->devices->sl_android_tablet_4_0_portrait->desiredCapabilities['device-type'] = "tablet";
        $this->devices->sl_android_tablet_4_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->devices->sl_android_tablet_4_0_landscape = new BaseObject;
        $this->devices->sl_android_tablet_4_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->devices->sl_android_tablet_4_0_landscape->browser = 'android';
        $this->devices->sl_android_tablet_4_0_landscape->desiredCapabilities = array();
        $this->devices->sl_android_tablet_4_0_landscape->desiredCapabilities['platform'] = "Linux";
        $this->devices->sl_android_tablet_4_0_landscape->desiredCapabilities['version'] = "4.0";
        $this->devices->sl_android_tablet_4_0_landscape->desiredCapabilities['device-type'] = "tablet";
        $this->devices->sl_android_tablet_4_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        // all done
    }

    public function initDevice($deviceName)
    {
        // does the device exist?
        if (!isset($this->devices->$deviceName)) {
            throw new E4xx_NoSuchDevice($deviceName);
        }

        // copy over the device that we want
        $this->device = $this->devices->$deviceName;

        // remember the device name
        $this->deviceName = $deviceName;
    }

    public function initEnvironment($envName)
    {
        // make sure we start with a fresh environment
        $this->env = new BaseObject;

        // we need to work out which environment we are running against,
        // as all other decisions are affected by this
        $this->env->mergeFrom($this->environments->defaults);
        try {
            $this->env->mergeFrom($this->environments->$envName);
        } catch (E5xx_NoSuchProperty $e) {
            echo "*** warning: using empty config instead of '{$envName}'";
        }

        // we need to remember the name of the environment too!
        $this->envName = $envName;

        // we need to provide information about the machine that we
        // are running on
        $this->env->host = new BaseObject;
        list($this->env->host->networkInterface, $this->env->host->ipAddress) = $this->getHostIpAddress();
    }

    public function initPhases()
    {
        // make sure that phases.namespaces is correctly defined
        if (isset($this->phases->namespaces)) {
            if (!is_array($this->phases->namespaces)) {
                throw new E5xx_InvalidConfig("the 'phases.namespaces' section of the config must either be an array, or it must be left out");
            }
        }
    }

    public function initProse()
    {
        // make sure that prose.namespaces is correctly defined
        if (isset($this->prose, $this->prose->namespaces)) {
            if (!is_array($this->prose->namespaces)) {
                throw new E5xx_InvalidConfig("the 'prose.namespaces' section of the config must either be an array, or it must be left out");
            }
        }
    }

    public function initReports()
    {
        // where are we looking?
        if (isset($this->reports, $this->reports->namespaces)) {
            if (!is_array($this->reports->namespaces)) {
                throw new E5xx_InvalidConfig("the 'reports.namespaces' section of the config must either be an array, or it must be left out");
            }
        }
    }

    protected function getHostIpAddress()
    {
        // step 1 - how many adapters do we have on this box?
        // @todo Maybe we want to move this somewhere more central later?
        $os = Os::getOs();
        $parser = IfconfigParser::fromDistributions($os->getPossibleClassNames());
        $netifaces = new netifaces($os, $parser);

        $adapters = $netifaces->listAdapters();
        if (empty($adapters)) {
            throw new Exception("unable to parse host machine network adapters list");
        }

        // step 2 - find an adapter that is most likely to have the IP address
        // that we want
        //
        // note: am not sure that the search list for OSX interfaces is
        // reliable :(

        try {
            $searchList = array("br0", "p2p1", "eth0", "en2", "en0", "en1", "wlan0");
            foreach ($searchList as $adapterToTest) {
                // skip over any adapters that don't exist on this machine
                if (!in_array($adapterToTest, $adapters)) {
                    continue;
                }

                // we think the adapter is present
                //
                // does it have an IP address?
                try {
                    $ipAddress = $netifaces->getIpAddress($adapterToTest);
                } catch(NetifacesException $e){
                    // We couldn't get an IP address
                    $ipAddress = null;
                }

                // did we get back a valid IP address?
                $parts = explode('.', $ipAddress);
                if (count($parts) == 4) {
                    // success!
                    return array($adapterToTest, $ipAddress);
                }
            }

            // if we get here, we could not determine the IP address of our
            // host :(
            //
            // this sucks
            throw new NetifacesException("Unable to determine IP address");

        } catch (NetifacesException $e){
            throw new Exception("could not determine IP address of host machine");
        }
    }
}
