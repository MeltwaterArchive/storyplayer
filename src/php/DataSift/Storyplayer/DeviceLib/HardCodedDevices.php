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
 * @package   Storyplayer/DeviceLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\DeviceLib;

use DataSift\Storyplayer\ConfigLib\HardCodedList;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * Our list of built-in test devices
 *
 * @category  Libraries
 * @package   Storyplayer/DevicesLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class HardCodedDevices extends HardCodedList
{
    public function __construct()
    {
        parent::__construct('DataSift\Storyplayer\DeviceLib\DeviceConfig');
        $this->initDefaultConfig();
    }

    /**
     * @return void
     */
    public function initDefaultConfig()
    {
        // defaults for Chrome, running locally
        $config = $this->newConfig('chrome')->getConfig();
        $config->adapter = 'LocalWebDriver';
        $config->browser  = 'chrome';

        // defaults for Firefox, running locally
        $config = $this->newConfig('firefox')->getConfig();
        $config->adapter = 'LocalWebDriver';
        $config->browser  = 'firefox';

        // defaults for Safari, running locally
        $config = $this->newConfig('safari')->getConfig();
        $config->adapter = 'LocalWebDriver';
        $config->browser  = 'safari';

        // ----------------------------------------------------------------
        //
        // Sauce Labs browsers

        # Windows 8.1
        $config = $this->newConfig('sl_firefox25_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "25";

        $config = $this->newConfig('sl_firefox24_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "24";

        $config = $this->newConfig('sl_firefox23_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "23";

        $config = $this->newConfig('sl_firefox22_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "22";

        $config = $this->newConfig('sl_firefox21_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "21";

        $config = $this->newConfig('sl_firefox20_win8_1')->getConfig();
        $config = new BaseObject;
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "20";

        $config = $this->newConfig('sl_firefox19_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "19";

        $config = $this->newConfig('sl_firefox18_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "18";

        $config = $this->newConfig('sl_firefox17_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "17";

        $config = $this->newConfig('sl_firefox16_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "16";

        $config = $this->newConfig('sl_firefox15_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "15";

        $config = $this->newConfig('sl_firefox14_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "14";

        $config = $this->newConfig('sl_firefox13_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "13";

        $config = $this->newConfig('sl_firefox12_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "12";

        $config = $this->newConfig('sl_firefox11_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "11";

        $config = $this->newConfig('sl_firefox10_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "10";

        $config = $this->newConfig('sl_firefox9_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "9";

        $config = $this->newConfig('sl_firefox8_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "8";

        $config = $this->newConfig('sl_firefox7_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "7";

        $config = $this->newConfig('sl_firefox6_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "6";

        $config = $this->newConfig('sl_firefox5_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "5";

        $config = $this->newConfig('sl_firefox4_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "4";

        $config = $this->newConfig('sl_chrome31_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "31";

        $config = $this->newConfig('sl_chrome30_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "30";

        $config = $this->newConfig('sl_chrome29_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "29";

        $config = $this->newConfig('sl_chrome28_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "28";

        $config = $this->newConfig('sl_chrome27_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "27";

        $config = $this->newConfig('sl_chrome26_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "26";

        $config = $this->newConfig('sl_ie11_win8_1')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'internet explorer';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8.1";
        $config->desiredCapabilities['version'] = "11";

        # Windows 8.0
        $config = $this->newConfig('sl_firefox25_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "25";

        $config = $this->newConfig('sl_firefox24_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "24";

        $config = $this->newConfig('sl_firefox23_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "23";

        $config = $this->newConfig('sl_firefox22_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "22";

        $config = $this->newConfig('sl_firefox21_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "21";

        $config = $this->newConfig('sl_firefox20_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "20";

        $config = $this->newConfig('sl_firefox19_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "19";

        $config = $this->newConfig('sl_firefox18_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "18";

        $config = $this->newConfig('sl_firefox17_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "17";

        $config = $this->newConfig('sl_firefox16_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "16";

        $config = $this->newConfig('sl_firefox15_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "15";

        $config = $this->newConfig('sl_firefox14_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "14";

        $config = $this->newConfig('sl_firefox13_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "13";

        $config = $this->newConfig('sl_firefox12_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "12";

        $config = $this->newConfig('sl_firefox11_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "11";

        $config = $this->newConfig('sl_firefox10_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "10";

        $config = $this->newConfig('sl_firefox9_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "9";

        $config = $this->newConfig('sl_firefox8_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "8";

        $config = $this->newConfig('sl_firefox7_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "7";

        $config = $this->newConfig('sl_firefox6_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "6";

        $config = $this->newConfig('sl_firefox5_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "5";

        $config = $this->newConfig('sl_firefox4_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "4";

        $config = $this->newConfig('sl_chrome31_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "31";

        $config = $this->newConfig('sl_chrome30_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "30";

        $config = $this->newConfig('sl_chrome29_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "29";

        $config = $this->newConfig('sl_chrome28_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "28";

        $config = $this->newConfig('sl_chrome27_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "27";

        $config = $this->newConfig('sl_chrome26_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "26";

        $config = $this->newConfig('sl_ie10_win8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'internet explorer';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 8";
        $config->desiredCapabilities['version'] = "10";

        # Windows 7
        $config = $this->newConfig('sl_firefox25_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "25";

        $config = $this->newConfig('sl_firefox24_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "24";

        $config = $this->newConfig('sl_firefox23_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "23";

        $config = $this->newConfig('sl_firefox22_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "22";

        $config = $this->newConfig('sl_firefox21_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "21";

        $config = $this->newConfig('sl_firefox20_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "20";

        $config = $this->newConfig('sl_firefox19_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "19";

        $config = $this->newConfig('sl_firefox18_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "18";

        $config = $this->newConfig('sl_firefox17_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "17";

        $config = $this->newConfig('sl_firefox16_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "16";

        $config = $this->newConfig('sl_firefox15_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "15";

        $config = $this->newConfig('sl_firefox14_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "14";

        $config = $this->newConfig('sl_firefox13_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "13";

        $config = $this->newConfig('sl_firefox12_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "12";

        $config = $this->newConfig('sl_firefox11_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "11";

        $config = $this->newConfig('sl_firefox10_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "10";

        $config = $this->newConfig('sl_firefox9_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "9";

        $config = $this->newConfig('sl_firefox8_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "8";

        $config = $this->newConfig('sl_firefox7_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "7";

        $config = $this->newConfig('sl_firefox6_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "6";

        $config = $this->newConfig('sl_firefox5_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "5";

        $config = $this->newConfig('sl_firefox4_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "4";

        $config = $this->newConfig('sl_chrome31_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "31";

        $config = $this->newConfig('sl_chrome30_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "30";

        $config = $this->newConfig('sl_chrome29_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "29";

        $config = $this->newConfig('sl_chrome28_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "28";

        $config = $this->newConfig('sl_chrome27_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "27";

        $config = $this->newConfig('sl_chrome26_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "26";

        $config = $this->newConfig('sl_ie10_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'internet explorer';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "10";

        $config = $this->newConfig('sl_ie9_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'internet explorer';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "9";

        $config = $this->newConfig('sl_ie8_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'internet explorer';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "8";

        $config = $this->newConfig('sl_opera12_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'opera';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "12";

        $config = $this->newConfig('sl_opera11_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'opera';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "11";

        $config = $this->newConfig('sl_safari5_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'safari';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows 7";
        $config->desiredCapabilities['version'] = "5";

        # Windows XP
        $config = $this->newConfig('sl_firefox25_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "25";

        $config = $this->newConfig('sl_firefox24_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "24";

        $config = $this->newConfig('sl_firefox23_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "23";

        $config = $this->newConfig('sl_firefox22_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "22";

        $config = $this->newConfig('sl_firefox21_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "21";

        $config = $this->newConfig('sl_firefox20_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "20";

        $config = $this->newConfig('sl_firefox19_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "19";

        $config = $this->newConfig('sl_firefox18_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "18";

        $config = $this->newConfig('sl_firefox17_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "17";

        $config = $this->newConfig('sl_firefox16_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "16";

        $config = $this->newConfig('sl_firefox15_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "15";

        $config = $this->newConfig('sl_firefox14_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "14";

        $config = $this->newConfig('sl_firefox13_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "13";

        $config = $this->newConfig('sl_firefox12_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "12";

        $config = $this->newConfig('sl_firefox11_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "11";

        $config = $this->newConfig('sl_firefox10_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "10";

        $config = $this->newConfig('sl_firefox9_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "9";

        $config = $this->newConfig('sl_firefox8_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "8";

        $config = $this->newConfig('sl_firefox7_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "7";

        $config = $this->newConfig('sl_firefox6_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "6";

        $config = $this->newConfig('sl_firefox5_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "5";

        $config = $this->newConfig('sl_firefox4_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "4";

        $config = $this->newConfig('sl_chrome31_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "31";

        $config = $this->newConfig('sl_chrome30_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "30";

        $config = $this->newConfig('sl_chrome29_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "29";

        $config = $this->newConfig('sl_chrome28_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "28";

        $config = $this->newConfig('sl_chrome27_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "27";

        $config = $this->newConfig('sl_chrome26_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "26";

        $config = $this->newConfig('sl_ie8_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'internet explorer';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "8";

        $config = $this->newConfig('sl_ie7_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'internet explorer';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "7";

        $config = $this->newConfig('sl_ie6_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'internet explorer';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "6";

        $config = $this->newConfig('sl_opera12_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'opera';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "12";

        $config = $this->newConfig('sl_opera11_winxp')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'opera';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Windows XP";
        $config->desiredCapabilities['version'] = "11";

        # OSX 10.6 Snow Leopard
        $config = $this->newConfig('sl_firefox25_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "25";

        $config = $this->newConfig('sl_firefox24_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "24";

        $config = $this->newConfig('sl_firefox23_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "23";

        $config = $this->newConfig('sl_firefox22_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "22";

        $config = $this->newConfig('sl_firefox21_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "21";

        $config = $this->newConfig('sl_firefox20_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "20";

        $config = $this->newConfig('sl_firefox19_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "19";

        $config = $this->newConfig('sl_firefox18_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "18";

        $config = $this->newConfig('sl_firefox17_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "17";

        $config = $this->newConfig('sl_firefox16_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "16";

        $config = $this->newConfig('sl_firefox15_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "15";

        $config = $this->newConfig('sl_firefox14_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "14";

        $config = $this->newConfig('sl_firefox13_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "13";

        $config = $this->newConfig('sl_firefox12_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "12";

        $config = $this->newConfig('sl_firefox11_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "11";

        $config = $this->newConfig('sl_firefox10_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "10";

        $config = $this->newConfig('sl_firefox9_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "9";

        $config = $this->newConfig('sl_firefox8_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "8";

        $config = $this->newConfig('sl_firefox7_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "7";

        $config = $this->newConfig('sl_firefox6_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "6";

        $config = $this->newConfig('sl_firefox5_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "5";

        $config = $this->newConfig('sl_firefox4_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "4";

        $config = $this->newConfig('sl_chrome28_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "28";

        $config = $this->newConfig('sl_ie5_osx10_6')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'internet explorer';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "5";

        # OSX 10.8 Mountain Lion
        $config = $this->newConfig('sl_chrome27_osx10_8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "27";

        $config = $this->newConfig('sl_ie6_osx10_8')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'internet explorer';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "6";

        # Linux
        $config = $this->newConfig('sl_firefox25_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "25";

        $config = $this->newConfig('sl_firefox24_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "24";

        $config = $this->newConfig('sl_firefox23_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "23";

        $config = $this->newConfig('sl_firefox22_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "22";

        $config = $this->newConfig('sl_firefox21_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "21";

        $config = $this->newConfig('sl_firefox20_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "20";

        $config = $this->newConfig('sl_firefox19_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "19";

        $config = $this->newConfig('sl_firefox18_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "18";

        $config = $this->newConfig('sl_firefox17_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "17";

        $config = $this->newConfig('sl_firefox16_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "16";

        $config = $this->newConfig('sl_firefox15_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "15";

        $config = $this->newConfig('sl_firefox14_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "14";

        $config = $this->newConfig('sl_firefox13_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "13";

        $config = $this->newConfig('sl_firefox12_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "12";

        $config = $this->newConfig('sl_firefox11_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "11";

        $config = $this->newConfig('sl_firefox10_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "10";

        $config = $this->newConfig('sl_firefox9_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "9";

        $config = $this->newConfig('sl_firefox8_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "8";

        $config = $this->newConfig('sl_firefox7_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "7";

        $config = $this->newConfig('sl_firefox6_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "6";

        $config = $this->newConfig('sl_firefox5_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "5";

        $config = $this->newConfig('sl_firefox4_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'firefox';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "4";

        $config = $this->newConfig('sl_chrome31_win7')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "31";

        $config = $this->newConfig('sl_chrome30_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "30";

        $config = $this->newConfig('sl_chrome29_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "29";

        $config = $this->newConfig('sl_chrome28_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "28";

        $config = $this->newConfig('sl_chrome27_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "27";

        $config = $this->newConfig('sl_chrome26_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'chrome';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "26";

        $config = $this->newConfig('sl_opera12_linux')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'opera';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "12";

        # iOS - iPad
        $config = $this->newConfig('sl_safari_ipad_ios6_1_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'ipad';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "6.1";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_safari_ipad_ios6_1_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'ipad';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "6.1";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        $config = $this->newConfig('sl_safari_ipad_ios6_0_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'ipad';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "6.0";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_safari_ipad_ios6_0_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'ipad';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "6.0";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        $config = $this->newConfig('sl_safari_ipad_ios5_1_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'ipad';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "5.1";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_safari_ipad_ios5_1_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'ipad';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "5.1";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        $config = $this->newConfig('sl_safari_ipad_ios5_0_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'ipad';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "5.0";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_safari_ipad_ios5_0_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'ipad';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "5.0";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        $config = $this->newConfig('sl_safari_ipad_ios4_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'ipad';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "4";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_safari_ipad_ios4_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'ipad';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "4";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        # iOS - iPhone
        $config = $this->newConfig('sl_safari_iphone_ios6_1_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'iphone';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "6.1";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_safari_iphone_ios6_1_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'iphone';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "6.1";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        $config = $this->newConfig('sl_safari_iphone_ios6_0_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'iphone';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "6.0";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_safari_iphone_ios6_0_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'iphone';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "6.0";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        $config = $this->newConfig('sl_safari_iphone_ios5_1_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'iphone';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "5.1";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_safari_iphone_ios5_1_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'iphone';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.8";
        $config->desiredCapabilities['version'] = "5.1";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        $config = $this->newConfig('sl_safari_iphone_ios5_0_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'iphone';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "5.0";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_safari_iphone_ios5_0_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'iphone';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "5.0";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        $config = $this->newConfig('sl_safari_iphone_ios4_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'iphone';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "4";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_safari_iphone_ios4_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'iphone';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "OS X 10.6";
        $config->desiredCapabilities['version'] = "4";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        # android 4.0 - phone
        $config = $this->newConfig('sl_android_phone_4_0_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'android';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "4.0";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_android_phone_4_0_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'android';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "4.0";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        # android 4.0 - tablet
        $config = $this->newConfig('sl_android_tablet_4_0_portrait')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'android';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "4.0";
        $config->desiredCapabilities['device-type'] = "tablet";
        $config->desiredCapabilities['device-orientation'] = "portrait";

        $config = $this->newConfig('sl_android_tablet_4_0_landscape')->getConfig();
        $config->adapter = 'SauceLabsWebDriver';
        $config->browser = 'android';
        $config->desiredCapabilities = array();
        $config->desiredCapabilities['platform'] = "Linux";
        $config->desiredCapabilities['version'] = "4.0";
        $config->desiredCapabilities['device-type'] = "tablet";
        $config->desiredCapabilities['device-orientation'] = "landscape";

        // all done
    }
}
