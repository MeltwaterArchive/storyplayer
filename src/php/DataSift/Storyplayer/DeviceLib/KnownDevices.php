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

namespace DataSift\Storyplayer\DeviceLib;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * Our list of known devices
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class KnownDevices extends BaseObject
{
    public $devices;

    public function __construct()
    {
        $this->initDefaultConfig();
    }

    /**
     * @return void
     */
    public function initDefaultConfig()
    {
        // defaults for Chrome, running locally
        $this->chrome = new BaseObject;
        $this->chrome->adapter = 'LocalWebDriver';
        $this->chrome->browser  = 'chrome';

        // defaults for Firefox, running locally
        $this->firefox = new BaseObject;
        $this->firefox->adapter = 'LocalWebDriver';
        $this->firefox->browser  = 'firefox';

        // defaults for Safari, running locally
        $this->safari = new BaseObject;
        $this->safari->adapter = 'LocalWebDriver';
        $this->safari->browser  = 'safari';

        // ----------------------------------------------------------------
        //
        // Sauce Labs browsers

        # Windows 8.1
        $this->sl_firefox25_win8_1 = new BaseObject;
        $this->sl_firefox25_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox25_win8_1->browser = 'firefox';
        $this->sl_firefox25_win8_1->desiredCapabilities = array();
        $this->sl_firefox25_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox25_win8_1->desiredCapabilities['version'] = "25";

        $this->sl_firefox24_win8_1 = new BaseObject;
        $this->sl_firefox24_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox24_win8_1->browser = 'firefox';
        $this->sl_firefox24_win8_1->desiredCapabilities = array();
        $this->sl_firefox24_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox24_win8_1->desiredCapabilities['version'] = "24";

        $this->sl_firefox23_win8_1 = new BaseObject;
        $this->sl_firefox23_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox23_win8_1->browser = 'firefox';
        $this->sl_firefox23_win8_1->desiredCapabilities = array();
        $this->sl_firefox23_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox23_win8_1->desiredCapabilities['version'] = "23";

        $this->sl_firefox22_win8_1 = new BaseObject;
        $this->sl_firefox22_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox22_win8_1->browser = 'firefox';
        $this->sl_firefox22_win8_1->desiredCapabilities = array();
        $this->sl_firefox22_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox22_win8_1->desiredCapabilities['version'] = "22";

        $this->sl_firefox21_win8_1 = new BaseObject;
        $this->sl_firefox21_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox21_win8_1->browser = 'firefox';
        $this->sl_firefox21_win8_1->desiredCapabilities = array();
        $this->sl_firefox21_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox21_win8_1->desiredCapabilities['version'] = "21";

        $this->sl_firefox20_win8_1 = new BaseObject;
        $this->sl_firefox20_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox20_win8_1->browser = 'firefox';
        $this->sl_firefox20_win8_1->desiredCapabilities = array();
        $this->sl_firefox20_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox20_win8_1->desiredCapabilities['version'] = "20";

        $this->sl_firefox19_win8_1 = new BaseObject;
        $this->sl_firefox19_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox19_win8_1->browser = 'firefox';
        $this->sl_firefox19_win8_1->desiredCapabilities = array();
        $this->sl_firefox19_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox19_win8_1->desiredCapabilities['version'] = "19";

        $this->sl_firefox18_win8_1 = new BaseObject;
        $this->sl_firefox18_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox18_win8_1->browser = 'firefox';
        $this->sl_firefox18_win8_1->desiredCapabilities = array();
        $this->sl_firefox18_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox18_win8_1->desiredCapabilities['version'] = "18";

        $this->sl_firefox17_win8_1 = new BaseObject;
        $this->sl_firefox17_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox17_win8_1->browser = 'firefox';
        $this->sl_firefox17_win8_1->desiredCapabilities = array();
        $this->sl_firefox17_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox17_win8_1->desiredCapabilities['version'] = "17";

        $this->sl_firefox16_win8_1 = new BaseObject;
        $this->sl_firefox16_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox16_win8_1->browser = 'firefox';
        $this->sl_firefox16_win8_1->desiredCapabilities = array();
        $this->sl_firefox16_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox16_win8_1->desiredCapabilities['version'] = "16";

        $this->sl_firefox15_win8_1 = new BaseObject;
        $this->sl_firefox15_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox15_win8_1->browser = 'firefox';
        $this->sl_firefox15_win8_1->desiredCapabilities = array();
        $this->sl_firefox15_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox15_win8_1->desiredCapabilities['version'] = "15";

        $this->sl_firefox14_win8_1 = new BaseObject;
        $this->sl_firefox14_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox14_win8_1->browser = 'firefox';
        $this->sl_firefox14_win8_1->desiredCapabilities = array();
        $this->sl_firefox14_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox14_win8_1->desiredCapabilities['version'] = "14";

        $this->sl_firefox13_win8_1 = new BaseObject;
        $this->sl_firefox13_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox13_win8_1->browser = 'firefox';
        $this->sl_firefox13_win8_1->desiredCapabilities = array();
        $this->sl_firefox13_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox13_win8_1->desiredCapabilities['version'] = "13";

        $this->sl_firefox12_win8_1 = new BaseObject;
        $this->sl_firefox12_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox12_win8_1->browser = 'firefox';
        $this->sl_firefox12_win8_1->desiredCapabilities = array();
        $this->sl_firefox12_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox12_win8_1->desiredCapabilities['version'] = "12";

        $this->sl_firefox11_win8_1 = new BaseObject;
        $this->sl_firefox11_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox11_win8_1->browser = 'firefox';
        $this->sl_firefox11_win8_1->desiredCapabilities = array();
        $this->sl_firefox11_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox11_win8_1->desiredCapabilities['version'] = "11";

        $this->sl_firefox10_win8_1 = new BaseObject;
        $this->sl_firefox10_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox10_win8_1->browser = 'firefox';
        $this->sl_firefox10_win8_1->desiredCapabilities = array();
        $this->sl_firefox10_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox10_win8_1->desiredCapabilities['version'] = "10";

        $this->sl_firefox9_win8_1 = new BaseObject;
        $this->sl_firefox9_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox9_win8_1->browser = 'firefox';
        $this->sl_firefox9_win8_1->desiredCapabilities = array();
        $this->sl_firefox9_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox9_win8_1->desiredCapabilities['version'] = "9";

        $this->sl_firefox8_win8_1 = new BaseObject;
        $this->sl_firefox8_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox8_win8_1->browser = 'firefox';
        $this->sl_firefox8_win8_1->desiredCapabilities = array();
        $this->sl_firefox8_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox8_win8_1->desiredCapabilities['version'] = "8";

        $this->sl_firefox7_win8_1 = new BaseObject;
        $this->sl_firefox7_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox7_win8_1->browser = 'firefox';
        $this->sl_firefox7_win8_1->desiredCapabilities = array();
        $this->sl_firefox7_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox7_win8_1->desiredCapabilities['version'] = "7";

        $this->sl_firefox6_win8_1 = new BaseObject;
        $this->sl_firefox6_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox6_win8_1->browser = 'firefox';
        $this->sl_firefox6_win8_1->desiredCapabilities = array();
        $this->sl_firefox6_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox6_win8_1->desiredCapabilities['version'] = "6";

        $this->sl_firefox5_win8_1 = new BaseObject;
        $this->sl_firefox5_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox5_win8_1->browser = 'firefox';
        $this->sl_firefox5_win8_1->desiredCapabilities = array();
        $this->sl_firefox5_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox5_win8_1->desiredCapabilities['version'] = "5";

        $this->sl_firefox4_win8_1 = new BaseObject;
        $this->sl_firefox4_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox4_win8_1->browser = 'firefox';
        $this->sl_firefox4_win8_1->desiredCapabilities = array();
        $this->sl_firefox4_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_firefox4_win8_1->desiredCapabilities['version'] = "4";

        $this->sl_chrome31_win8_1 = new BaseObject;
        $this->sl_chrome31_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome31_win8_1->browser = 'chrome';
        $this->sl_chrome31_win8_1->desiredCapabilities = array();
        $this->sl_chrome31_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_chrome31_win8_1->desiredCapabilities['version'] = "31";

        $this->sl_chrome30_win8_1 = new BaseObject;
        $this->sl_chrome30_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome30_win8_1->browser = 'chrome';
        $this->sl_chrome30_win8_1->desiredCapabilities = array();
        $this->sl_chrome30_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_chrome30_win8_1->desiredCapabilities['version'] = "30";

        $this->sl_chrome29_win8_1 = new BaseObject;
        $this->sl_chrome29_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome29_win8_1->browser = 'chrome';
        $this->sl_chrome29_win8_1->desiredCapabilities = array();
        $this->sl_chrome29_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_chrome29_win8_1->desiredCapabilities['version'] = "29";

        $this->sl_chrome28_win8_1 = new BaseObject;
        $this->sl_chrome28_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome28_win8_1->browser = 'chrome';
        $this->sl_chrome28_win8_1->desiredCapabilities = array();
        $this->sl_chrome28_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_chrome28_win8_1->desiredCapabilities['version'] = "28";

        $this->sl_chrome27_win8_1 = new BaseObject;
        $this->sl_chrome27_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome27_win8_1->browser = 'chrome';
        $this->sl_chrome27_win8_1->desiredCapabilities = array();
        $this->sl_chrome27_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_chrome27_win8_1->desiredCapabilities['version'] = "27";

        $this->sl_chrome26_win8_1 = new BaseObject;
        $this->sl_chrome26_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome26_win8_1->browser = 'chrome';
        $this->sl_chrome26_win8_1->desiredCapabilities = array();
        $this->sl_chrome26_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_chrome26_win8_1->desiredCapabilities['version'] = "26";

        $this->sl_ie11_win8_1 = new BaseObject;
        $this->sl_ie11_win8_1->adapter = 'SauceLabsWebDriver';
        $this->sl_ie11_win8_1->browser = 'internet explorer';
        $this->sl_ie11_win8_1->desiredCapabilities = array();
        $this->sl_ie11_win8_1->desiredCapabilities['platform'] = "Windows 8.1";
        $this->sl_ie11_win8_1->desiredCapabilities['version'] = "11";

        # Windows 8.0
        $this->sl_firefox25_win8 = new BaseObject;
        $this->sl_firefox25_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox25_win8->browser = 'firefox';
        $this->sl_firefox25_win8->desiredCapabilities = array();
        $this->sl_firefox25_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox25_win8->desiredCapabilities['version'] = "25";

        $this->sl_firefox24_win8 = new BaseObject;
        $this->sl_firefox24_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox24_win8->browser = 'firefox';
        $this->sl_firefox24_win8->desiredCapabilities = array();
        $this->sl_firefox24_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox24_win8->desiredCapabilities['version'] = "24";

        $this->sl_firefox23_win8 = new BaseObject;
        $this->sl_firefox23_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox23_win8->browser = 'firefox';
        $this->sl_firefox23_win8->desiredCapabilities = array();
        $this->sl_firefox23_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox23_win8->desiredCapabilities['version'] = "23";

        $this->sl_firefox22_win8 = new BaseObject;
        $this->sl_firefox22_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox22_win8->browser = 'firefox';
        $this->sl_firefox22_win8->desiredCapabilities = array();
        $this->sl_firefox22_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox22_win8->desiredCapabilities['version'] = "22";

        $this->sl_firefox21_win8 = new BaseObject;
        $this->sl_firefox21_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox21_win8->browser = 'firefox';
        $this->sl_firefox21_win8->desiredCapabilities = array();
        $this->sl_firefox21_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox21_win8->desiredCapabilities['version'] = "21";

        $this->sl_firefox20_win8 = new BaseObject;
        $this->sl_firefox20_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox20_win8->browser = 'firefox';
        $this->sl_firefox20_win8->desiredCapabilities = array();
        $this->sl_firefox20_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox20_win8->desiredCapabilities['version'] = "20";

        $this->sl_firefox19_win8 = new BaseObject;
        $this->sl_firefox19_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox19_win8->browser = 'firefox';
        $this->sl_firefox19_win8->desiredCapabilities = array();
        $this->sl_firefox19_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox19_win8->desiredCapabilities['version'] = "19";

        $this->sl_firefox18_win8 = new BaseObject;
        $this->sl_firefox18_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox18_win8->browser = 'firefox';
        $this->sl_firefox18_win8->desiredCapabilities = array();
        $this->sl_firefox18_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox18_win8->desiredCapabilities['version'] = "18";

        $this->sl_firefox17_win8 = new BaseObject;
        $this->sl_firefox17_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox17_win8->browser = 'firefox';
        $this->sl_firefox17_win8->desiredCapabilities = array();
        $this->sl_firefox17_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox17_win8->desiredCapabilities['version'] = "17";

        $this->sl_firefox16_win8 = new BaseObject;
        $this->sl_firefox16_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox16_win8->browser = 'firefox';
        $this->sl_firefox16_win8->desiredCapabilities = array();
        $this->sl_firefox16_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox16_win8->desiredCapabilities['version'] = "16";

        $this->sl_firefox15_win8 = new BaseObject;
        $this->sl_firefox15_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox15_win8->browser = 'firefox';
        $this->sl_firefox15_win8->desiredCapabilities = array();
        $this->sl_firefox15_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox15_win8->desiredCapabilities['version'] = "15";

        $this->sl_firefox14_win8 = new BaseObject;
        $this->sl_firefox14_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox14_win8->browser = 'firefox';
        $this->sl_firefox14_win8->desiredCapabilities = array();
        $this->sl_firefox14_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox14_win8->desiredCapabilities['version'] = "14";

        $this->sl_firefox13_win8 = new BaseObject;
        $this->sl_firefox13_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox13_win8->browser = 'firefox';
        $this->sl_firefox13_win8->desiredCapabilities = array();
        $this->sl_firefox13_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox13_win8->desiredCapabilities['version'] = "13";

        $this->sl_firefox12_win8 = new BaseObject;
        $this->sl_firefox12_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox12_win8->browser = 'firefox';
        $this->sl_firefox12_win8->desiredCapabilities = array();
        $this->sl_firefox12_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox12_win8->desiredCapabilities['version'] = "12";

        $this->sl_firefox11_win8 = new BaseObject;
        $this->sl_firefox11_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox11_win8->browser = 'firefox';
        $this->sl_firefox11_win8->desiredCapabilities = array();
        $this->sl_firefox11_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox11_win8->desiredCapabilities['version'] = "11";

        $this->sl_firefox10_win8 = new BaseObject;
        $this->sl_firefox10_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox10_win8->browser = 'firefox';
        $this->sl_firefox10_win8->desiredCapabilities = array();
        $this->sl_firefox10_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox10_win8->desiredCapabilities['version'] = "10";

        $this->sl_firefox9_win8 = new BaseObject;
        $this->sl_firefox9_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox9_win8->browser = 'firefox';
        $this->sl_firefox9_win8->desiredCapabilities = array();
        $this->sl_firefox9_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox9_win8->desiredCapabilities['version'] = "9";

        $this->sl_firefox8_win8 = new BaseObject;
        $this->sl_firefox8_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox8_win8->browser = 'firefox';
        $this->sl_firefox8_win8->desiredCapabilities = array();
        $this->sl_firefox8_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox8_win8->desiredCapabilities['version'] = "8";

        $this->sl_firefox7_win8 = new BaseObject;
        $this->sl_firefox7_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox7_win8->browser = 'firefox';
        $this->sl_firefox7_win8->desiredCapabilities = array();
        $this->sl_firefox7_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox7_win8->desiredCapabilities['version'] = "7";

        $this->sl_firefox6_win8 = new BaseObject;
        $this->sl_firefox6_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox6_win8->browser = 'firefox';
        $this->sl_firefox6_win8->desiredCapabilities = array();
        $this->sl_firefox6_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox6_win8->desiredCapabilities['version'] = "6";

        $this->sl_firefox5_win8 = new BaseObject;
        $this->sl_firefox5_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox5_win8->browser = 'firefox';
        $this->sl_firefox5_win8->desiredCapabilities = array();
        $this->sl_firefox5_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox5_win8->desiredCapabilities['version'] = "5";

        $this->sl_firefox4_win8 = new BaseObject;
        $this->sl_firefox4_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox4_win8->browser = 'firefox';
        $this->sl_firefox4_win8->desiredCapabilities = array();
        $this->sl_firefox4_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_firefox4_win8->desiredCapabilities['version'] = "4";

        $this->sl_chrome31_win8 = new BaseObject;
        $this->sl_chrome31_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome31_win8->browser = 'chrome';
        $this->sl_chrome31_win8->desiredCapabilities = array();
        $this->sl_chrome31_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_chrome31_win8->desiredCapabilities['version'] = "31";

        $this->sl_chrome30_win8 = new BaseObject;
        $this->sl_chrome30_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome30_win8->browser = 'chrome';
        $this->sl_chrome30_win8->desiredCapabilities = array();
        $this->sl_chrome30_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_chrome30_win8->desiredCapabilities['version'] = "30";

        $this->sl_chrome29_win8 = new BaseObject;
        $this->sl_chrome29_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome29_win8->browser = 'chrome';
        $this->sl_chrome29_win8->desiredCapabilities = array();
        $this->sl_chrome29_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_chrome29_win8->desiredCapabilities['version'] = "29";

        $this->sl_chrome28_win8 = new BaseObject;
        $this->sl_chrome28_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome28_win8->browser = 'chrome';
        $this->sl_chrome28_win8->desiredCapabilities = array();
        $this->sl_chrome28_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_chrome28_win8->desiredCapabilities['version'] = "28";

        $this->sl_chrome27_win8 = new BaseObject;
        $this->sl_chrome27_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome27_win8->browser = 'chrome';
        $this->sl_chrome27_win8->desiredCapabilities = array();
        $this->sl_chrome27_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_chrome27_win8->desiredCapabilities['version'] = "27";

        $this->sl_chrome26_win8 = new BaseObject;
        $this->sl_chrome26_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome26_win8->browser = 'chrome';
        $this->sl_chrome26_win8->desiredCapabilities = array();
        $this->sl_chrome26_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_chrome26_win8->desiredCapabilities['version'] = "26";

        $this->sl_ie10_win8 = new BaseObject;
        $this->sl_ie10_win8->adapter = 'SauceLabsWebDriver';
        $this->sl_ie10_win8->browser = 'internet explorer';
        $this->sl_ie10_win8->desiredCapabilities = array();
        $this->sl_ie10_win8->desiredCapabilities['platform'] = "Windows 8";
        $this->sl_ie10_win8->desiredCapabilities['version'] = "10";

        # Windows 7
        $this->sl_firefox25_win7 = new BaseObject;
        $this->sl_firefox25_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox25_win7->browser = 'firefox';
        $this->sl_firefox25_win7->desiredCapabilities = array();
        $this->sl_firefox25_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox25_win7->desiredCapabilities['version'] = "25";

        $this->sl_firefox24_win7 = new BaseObject;
        $this->sl_firefox24_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox24_win7->browser = 'firefox';
        $this->sl_firefox24_win7->desiredCapabilities = array();
        $this->sl_firefox24_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox24_win7->desiredCapabilities['version'] = "24";

        $this->sl_firefox23_win7 = new BaseObject;
        $this->sl_firefox23_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox23_win7->browser = 'firefox';
        $this->sl_firefox23_win7->desiredCapabilities = array();
        $this->sl_firefox23_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox23_win7->desiredCapabilities['version'] = "23";

        $this->sl_firefox22_win7 = new BaseObject;
        $this->sl_firefox22_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox22_win7->browser = 'firefox';
        $this->sl_firefox22_win7->desiredCapabilities = array();
        $this->sl_firefox22_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox22_win7->desiredCapabilities['version'] = "22";

        $this->sl_firefox21_win7 = new BaseObject;
        $this->sl_firefox21_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox21_win7->browser = 'firefox';
        $this->sl_firefox21_win7->desiredCapabilities = array();
        $this->sl_firefox21_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox21_win7->desiredCapabilities['version'] = "21";

        $this->sl_firefox20_win7 = new BaseObject;
        $this->sl_firefox20_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox20_win7->browser = 'firefox';
        $this->sl_firefox20_win7->desiredCapabilities = array();
        $this->sl_firefox20_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox20_win7->desiredCapabilities['version'] = "20";

        $this->sl_firefox19_win7 = new BaseObject;
        $this->sl_firefox19_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox19_win7->browser = 'firefox';
        $this->sl_firefox19_win7->desiredCapabilities = array();
        $this->sl_firefox19_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox19_win7->desiredCapabilities['version'] = "19";

        $this->sl_firefox18_win7 = new BaseObject;
        $this->sl_firefox18_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox18_win7->browser = 'firefox';
        $this->sl_firefox18_win7->desiredCapabilities = array();
        $this->sl_firefox18_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox18_win7->desiredCapabilities['version'] = "18";

        $this->sl_firefox17_win7 = new BaseObject;
        $this->sl_firefox17_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox17_win7->browser = 'firefox';
        $this->sl_firefox17_win7->desiredCapabilities = array();
        $this->sl_firefox17_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox17_win7->desiredCapabilities['version'] = "17";

        $this->sl_firefox16_win7 = new BaseObject;
        $this->sl_firefox16_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox16_win7->browser = 'firefox';
        $this->sl_firefox16_win7->desiredCapabilities = array();
        $this->sl_firefox16_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox16_win7->desiredCapabilities['version'] = "16";

        $this->sl_firefox15_win7 = new BaseObject;
        $this->sl_firefox15_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox15_win7->browser = 'firefox';
        $this->sl_firefox15_win7->desiredCapabilities = array();
        $this->sl_firefox15_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox15_win7->desiredCapabilities['version'] = "15";

        $this->sl_firefox14_win7 = new BaseObject;
        $this->sl_firefox14_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox14_win7->browser = 'firefox';
        $this->sl_firefox14_win7->desiredCapabilities = array();
        $this->sl_firefox14_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox14_win7->desiredCapabilities['version'] = "14";

        $this->sl_firefox13_win7 = new BaseObject;
        $this->sl_firefox13_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox13_win7->browser = 'firefox';
        $this->sl_firefox13_win7->desiredCapabilities = array();
        $this->sl_firefox13_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox13_win7->desiredCapabilities['version'] = "13";

        $this->sl_firefox12_win7 = new BaseObject;
        $this->sl_firefox12_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox12_win7->browser = 'firefox';
        $this->sl_firefox12_win7->desiredCapabilities = array();
        $this->sl_firefox12_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox12_win7->desiredCapabilities['version'] = "12";

        $this->sl_firefox11_win7 = new BaseObject;
        $this->sl_firefox11_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox11_win7->browser = 'firefox';
        $this->sl_firefox11_win7->desiredCapabilities = array();
        $this->sl_firefox11_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox11_win7->desiredCapabilities['version'] = "11";

        $this->sl_firefox10_win7 = new BaseObject;
        $this->sl_firefox10_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox10_win7->browser = 'firefox';
        $this->sl_firefox10_win7->desiredCapabilities = array();
        $this->sl_firefox10_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox10_win7->desiredCapabilities['version'] = "10";

        $this->sl_firefox9_win7 = new BaseObject;
        $this->sl_firefox9_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox9_win7->browser = 'firefox';
        $this->sl_firefox9_win7->desiredCapabilities = array();
        $this->sl_firefox9_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox9_win7->desiredCapabilities['version'] = "9";

        $this->sl_firefox8_win7 = new BaseObject;
        $this->sl_firefox8_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox8_win7->browser = 'firefox';
        $this->sl_firefox8_win7->desiredCapabilities = array();
        $this->sl_firefox8_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox8_win7->desiredCapabilities['version'] = "8";

        $this->sl_firefox7_win7 = new BaseObject;
        $this->sl_firefox7_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox7_win7->browser = 'firefox';
        $this->sl_firefox7_win7->desiredCapabilities = array();
        $this->sl_firefox7_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox7_win7->desiredCapabilities['version'] = "7";

        $this->sl_firefox6_win7 = new BaseObject;
        $this->sl_firefox6_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox6_win7->browser = 'firefox';
        $this->sl_firefox6_win7->desiredCapabilities = array();
        $this->sl_firefox6_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox6_win7->desiredCapabilities['version'] = "6";

        $this->sl_firefox5_win7 = new BaseObject;
        $this->sl_firefox5_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox5_win7->browser = 'firefox';
        $this->sl_firefox5_win7->desiredCapabilities = array();
        $this->sl_firefox5_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox5_win7->desiredCapabilities['version'] = "5";

        $this->sl_firefox4_win7 = new BaseObject;
        $this->sl_firefox4_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox4_win7->browser = 'firefox';
        $this->sl_firefox4_win7->desiredCapabilities = array();
        $this->sl_firefox4_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_firefox4_win7->desiredCapabilities['version'] = "4";

        $this->sl_chrome31_win7 = new BaseObject;
        $this->sl_chrome31_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome31_win7->browser = 'chrome';
        $this->sl_chrome31_win7->desiredCapabilities = array();
        $this->sl_chrome31_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_chrome31_win7->desiredCapabilities['version'] = "31";

        $this->sl_chrome30_win7 = new BaseObject;
        $this->sl_chrome30_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome30_win7->browser = 'chrome';
        $this->sl_chrome30_win7->desiredCapabilities = array();
        $this->sl_chrome30_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_chrome30_win7->desiredCapabilities['version'] = "30";

        $this->sl_chrome29_win7 = new BaseObject;
        $this->sl_chrome29_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome29_win7->browser = 'chrome';
        $this->sl_chrome29_win7->desiredCapabilities = array();
        $this->sl_chrome29_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_chrome29_win7->desiredCapabilities['version'] = "29";

        $this->sl_chrome28_win7 = new BaseObject;
        $this->sl_chrome28_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome28_win7->browser = 'chrome';
        $this->sl_chrome28_win7->desiredCapabilities = array();
        $this->sl_chrome28_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_chrome28_win7->desiredCapabilities['version'] = "28";

        $this->sl_chrome27_win7 = new BaseObject;
        $this->sl_chrome27_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome27_win7->browser = 'chrome';
        $this->sl_chrome27_win7->desiredCapabilities = array();
        $this->sl_chrome27_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_chrome27_win7->desiredCapabilities['version'] = "27";

        $this->sl_chrome26_win7 = new BaseObject;
        $this->sl_chrome26_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome26_win7->browser = 'chrome';
        $this->sl_chrome26_win7->desiredCapabilities = array();
        $this->sl_chrome26_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_chrome26_win7->desiredCapabilities['version'] = "26";

        $this->sl_ie10_win7 = new BaseObject;
        $this->sl_ie10_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_ie10_win7->browser = 'internet explorer';
        $this->sl_ie10_win7->desiredCapabilities = array();
        $this->sl_ie10_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_ie10_win7->desiredCapabilities['version'] = "10";

        $this->sl_ie9_win7 = new BaseObject;
        $this->sl_ie9_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_ie9_win7->browser = 'internet explorer';
        $this->sl_ie9_win7->desiredCapabilities = array();
        $this->sl_ie9_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_ie9_win7->desiredCapabilities['version'] = "9";

        $this->sl_ie8_win7 = new BaseObject;
        $this->sl_ie8_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_ie8_win7->browser = 'internet explorer';
        $this->sl_ie8_win7->desiredCapabilities = array();
        $this->sl_ie8_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_ie8_win7->desiredCapabilities['version'] = "8";

        $this->sl_opera12_win7 = new BaseObject;
        $this->sl_opera12_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_opera12_win7->browser = 'opera';
        $this->sl_opera12_win7->desiredCapabilities = array();
        $this->sl_opera12_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_opera12_win7->desiredCapabilities['version'] = "12";

        $this->sl_opera11_win7 = new BaseObject;
        $this->sl_opera11_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_opera11_win7->browser = 'opera';
        $this->sl_opera11_win7->desiredCapabilities = array();
        $this->sl_opera11_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_opera11_win7->desiredCapabilities['version'] = "11";

        $this->sl_safari5_win7 = new BaseObject;
        $this->sl_safari5_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_safari5_win7->browser = 'safari';
        $this->sl_safari5_win7->desiredCapabilities = array();
        $this->sl_safari5_win7->desiredCapabilities['platform'] = "Windows 7";
        $this->sl_safari5_win7->desiredCapabilities['version'] = "5";

        # Windows XP
        $this->sl_firefox25_winxp = new BaseObject;
        $this->sl_firefox25_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox25_winxp->browser = 'firefox';
        $this->sl_firefox25_winxp->desiredCapabilities = array();
        $this->sl_firefox25_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox25_winxp->desiredCapabilities['version'] = "25";

        $this->sl_firefox24_winxp = new BaseObject;
        $this->sl_firefox24_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox24_winxp->browser = 'firefox';
        $this->sl_firefox24_winxp->desiredCapabilities = array();
        $this->sl_firefox24_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox24_winxp->desiredCapabilities['version'] = "24";

        $this->sl_firefox23_winxp = new BaseObject;
        $this->sl_firefox23_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox23_winxp->browser = 'firefox';
        $this->sl_firefox23_winxp->desiredCapabilities = array();
        $this->sl_firefox23_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox23_winxp->desiredCapabilities['version'] = "23";

        $this->sl_firefox22_winxp = new BaseObject;
        $this->sl_firefox22_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox22_winxp->browser = 'firefox';
        $this->sl_firefox22_winxp->desiredCapabilities = array();
        $this->sl_firefox22_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox22_winxp->desiredCapabilities['version'] = "22";

        $this->sl_firefox21_winxp = new BaseObject;
        $this->sl_firefox21_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox21_winxp->browser = 'firefox';
        $this->sl_firefox21_winxp->desiredCapabilities = array();
        $this->sl_firefox21_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox21_winxp->desiredCapabilities['version'] = "21";

        $this->sl_firefox20_winxp = new BaseObject;
        $this->sl_firefox20_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox20_winxp->browser = 'firefox';
        $this->sl_firefox20_winxp->desiredCapabilities = array();
        $this->sl_firefox20_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox20_winxp->desiredCapabilities['version'] = "20";

        $this->sl_firefox19_winxp = new BaseObject;
        $this->sl_firefox19_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox19_winxp->browser = 'firefox';
        $this->sl_firefox19_winxp->desiredCapabilities = array();
        $this->sl_firefox19_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox19_winxp->desiredCapabilities['version'] = "19";

        $this->sl_firefox18_winxp = new BaseObject;
        $this->sl_firefox18_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox18_winxp->browser = 'firefox';
        $this->sl_firefox18_winxp->desiredCapabilities = array();
        $this->sl_firefox18_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox18_winxp->desiredCapabilities['version'] = "18";

        $this->sl_firefox17_winxp = new BaseObject;
        $this->sl_firefox17_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox17_winxp->browser = 'firefox';
        $this->sl_firefox17_winxp->desiredCapabilities = array();
        $this->sl_firefox17_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox17_winxp->desiredCapabilities['version'] = "17";

        $this->sl_firefox16_winxp = new BaseObject;
        $this->sl_firefox16_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox16_winxp->browser = 'firefox';
        $this->sl_firefox16_winxp->desiredCapabilities = array();
        $this->sl_firefox16_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox16_winxp->desiredCapabilities['version'] = "16";

        $this->sl_firefox15_winxp = new BaseObject;
        $this->sl_firefox15_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox15_winxp->browser = 'firefox';
        $this->sl_firefox15_winxp->desiredCapabilities = array();
        $this->sl_firefox15_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox15_winxp->desiredCapabilities['version'] = "15";

        $this->sl_firefox14_winxp = new BaseObject;
        $this->sl_firefox14_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox14_winxp->browser = 'firefox';
        $this->sl_firefox14_winxp->desiredCapabilities = array();
        $this->sl_firefox14_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox14_winxp->desiredCapabilities['version'] = "14";

        $this->sl_firefox13_winxp = new BaseObject;
        $this->sl_firefox13_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox13_winxp->browser = 'firefox';
        $this->sl_firefox13_winxp->desiredCapabilities = array();
        $this->sl_firefox13_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox13_winxp->desiredCapabilities['version'] = "13";

        $this->sl_firefox12_winxp = new BaseObject;
        $this->sl_firefox12_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox12_winxp->browser = 'firefox';
        $this->sl_firefox12_winxp->desiredCapabilities = array();
        $this->sl_firefox12_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox12_winxp->desiredCapabilities['version'] = "12";

        $this->sl_firefox11_winxp = new BaseObject;
        $this->sl_firefox11_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox11_winxp->browser = 'firefox';
        $this->sl_firefox11_winxp->desiredCapabilities = array();
        $this->sl_firefox11_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox11_winxp->desiredCapabilities['version'] = "11";

        $this->sl_firefox10_winxp = new BaseObject;
        $this->sl_firefox10_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox10_winxp->browser = 'firefox';
        $this->sl_firefox10_winxp->desiredCapabilities = array();
        $this->sl_firefox10_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox10_winxp->desiredCapabilities['version'] = "10";

        $this->sl_firefox9_winxp = new BaseObject;
        $this->sl_firefox9_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox9_winxp->browser = 'firefox';
        $this->sl_firefox9_winxp->desiredCapabilities = array();
        $this->sl_firefox9_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox9_winxp->desiredCapabilities['version'] = "9";

        $this->sl_firefox8_winxp = new BaseObject;
        $this->sl_firefox8_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox8_winxp->browser = 'firefox';
        $this->sl_firefox8_winxp->desiredCapabilities = array();
        $this->sl_firefox8_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox8_winxp->desiredCapabilities['version'] = "8";

        $this->sl_firefox7_winxp = new BaseObject;
        $this->sl_firefox7_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox7_winxp->browser = 'firefox';
        $this->sl_firefox7_winxp->desiredCapabilities = array();
        $this->sl_firefox7_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox7_winxp->desiredCapabilities['version'] = "7";

        $this->sl_firefox6_winxp = new BaseObject;
        $this->sl_firefox6_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox6_winxp->browser = 'firefox';
        $this->sl_firefox6_winxp->desiredCapabilities = array();
        $this->sl_firefox6_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox6_winxp->desiredCapabilities['version'] = "6";

        $this->sl_firefox5_winxp = new BaseObject;
        $this->sl_firefox5_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox5_winxp->browser = 'firefox';
        $this->sl_firefox5_winxp->desiredCapabilities = array();
        $this->sl_firefox5_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox5_winxp->desiredCapabilities['version'] = "5";

        $this->sl_firefox4_winxp = new BaseObject;
        $this->sl_firefox4_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox4_winxp->browser = 'firefox';
        $this->sl_firefox4_winxp->desiredCapabilities = array();
        $this->sl_firefox4_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_firefox4_winxp->desiredCapabilities['version'] = "4";

        $this->sl_chrome31_winxp = new BaseObject;
        $this->sl_chrome31_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome31_winxp->browser = 'chrome';
        $this->sl_chrome31_winxp->desiredCapabilities = array();
        $this->sl_chrome31_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_chrome31_winxp->desiredCapabilities['version'] = "31";

        $this->sl_chrome30_winxp = new BaseObject;
        $this->sl_chrome30_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome30_winxp->browser = 'chrome';
        $this->sl_chrome30_winxp->desiredCapabilities = array();
        $this->sl_chrome30_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_chrome30_winxp->desiredCapabilities['version'] = "30";

        $this->sl_chrome29_winxp = new BaseObject;
        $this->sl_chrome29_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome29_winxp->browser = 'chrome';
        $this->sl_chrome29_winxp->desiredCapabilities = array();
        $this->sl_chrome29_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_chrome29_winxp->desiredCapabilities['version'] = "29";

        $this->sl_chrome28_winxp = new BaseObject;
        $this->sl_chrome28_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome28_winxp->browser = 'chrome';
        $this->sl_chrome28_winxp->desiredCapabilities = array();
        $this->sl_chrome28_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_chrome28_winxp->desiredCapabilities['version'] = "28";

        $this->sl_chrome27_winxp = new BaseObject;
        $this->sl_chrome27_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome27_winxp->browser = 'chrome';
        $this->sl_chrome27_winxp->desiredCapabilities = array();
        $this->sl_chrome27_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_chrome27_winxp->desiredCapabilities['version'] = "27";

        $this->sl_chrome26_winxp = new BaseObject;
        $this->sl_chrome26_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome26_winxp->browser = 'chrome';
        $this->sl_chrome26_winxp->desiredCapabilities = array();
        $this->sl_chrome26_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_chrome26_winxp->desiredCapabilities['version'] = "26";

        $this->sl_ie8_winxp = new BaseObject;
        $this->sl_ie8_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_ie8_winxp->browser = 'internet explorer';
        $this->sl_ie8_winxp->desiredCapabilities = array();
        $this->sl_ie8_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_ie8_winxp->desiredCapabilities['version'] = "8";

        $this->sl_ie7_winxp = new BaseObject;
        $this->sl_ie7_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_ie7_winxp->browser = 'internet explorer';
        $this->sl_ie7_winxp->desiredCapabilities = array();
        $this->sl_ie7_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_ie7_winxp->desiredCapabilities['version'] = "7";

        $this->sl_ie6_winxp = new BaseObject;
        $this->sl_ie6_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_ie6_winxp->browser = 'internet explorer';
        $this->sl_ie6_winxp->desiredCapabilities = array();
        $this->sl_ie6_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_ie6_winxp->desiredCapabilities['version'] = "6";

        $this->sl_opera12_winxp = new BaseObject;
        $this->sl_opera12_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_opera12_winxp->browser = 'opera';
        $this->sl_opera12_winxp->desiredCapabilities = array();
        $this->sl_opera12_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_opera12_winxp->desiredCapabilities['version'] = "12";

        $this->sl_opera11_winxp = new BaseObject;
        $this->sl_opera11_winxp->adapter = 'SauceLabsWebDriver';
        $this->sl_opera11_winxp->browser = 'opera';
        $this->sl_opera11_winxp->desiredCapabilities = array();
        $this->sl_opera11_winxp->desiredCapabilities['platform'] = "Windows XP";
        $this->sl_opera11_winxp->desiredCapabilities['version'] = "11";

        # OSX 10.6 Snow Leopard
        $this->sl_firefox25_osx10_6 = new BaseObject;
        $this->sl_firefox25_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox25_osx10_6->browser = 'firefox';
        $this->sl_firefox25_osx10_6->desiredCapabilities = array();
        $this->sl_firefox25_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox25_osx10_6->desiredCapabilities['version'] = "25";

        $this->sl_firefox24_osx10_6 = new BaseObject;
        $this->sl_firefox24_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox24_osx10_6->browser = 'firefox';
        $this->sl_firefox24_osx10_6->desiredCapabilities = array();
        $this->sl_firefox24_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox24_osx10_6->desiredCapabilities['version'] = "24";

        $this->sl_firefox23_osx10_6 = new BaseObject;
        $this->sl_firefox23_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox23_osx10_6->browser = 'firefox';
        $this->sl_firefox23_osx10_6->desiredCapabilities = array();
        $this->sl_firefox23_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox23_osx10_6->desiredCapabilities['version'] = "23";

        $this->sl_firefox22_osx10_6 = new BaseObject;
        $this->sl_firefox22_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox22_osx10_6->browser = 'firefox';
        $this->sl_firefox22_osx10_6->desiredCapabilities = array();
        $this->sl_firefox22_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox22_osx10_6->desiredCapabilities['version'] = "22";

        $this->sl_firefox21_osx10_6 = new BaseObject;
        $this->sl_firefox21_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox21_osx10_6->browser = 'firefox';
        $this->sl_firefox21_osx10_6->desiredCapabilities = array();
        $this->sl_firefox21_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox21_osx10_6->desiredCapabilities['version'] = "21";

        $this->sl_firefox20_osx10_6 = new BaseObject;
        $this->sl_firefox20_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox20_osx10_6->browser = 'firefox';
        $this->sl_firefox20_osx10_6->desiredCapabilities = array();
        $this->sl_firefox20_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox20_osx10_6->desiredCapabilities['version'] = "20";

        $this->sl_firefox19_osx10_6 = new BaseObject;
        $this->sl_firefox19_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox19_osx10_6->browser = 'firefox';
        $this->sl_firefox19_osx10_6->desiredCapabilities = array();
        $this->sl_firefox19_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox19_osx10_6->desiredCapabilities['version'] = "19";

        $this->sl_firefox18_osx10_6 = new BaseObject;
        $this->sl_firefox18_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox18_osx10_6->browser = 'firefox';
        $this->sl_firefox18_osx10_6->desiredCapabilities = array();
        $this->sl_firefox18_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox18_osx10_6->desiredCapabilities['version'] = "18";

        $this->sl_firefox17_osx10_6 = new BaseObject;
        $this->sl_firefox17_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox17_osx10_6->browser = 'firefox';
        $this->sl_firefox17_osx10_6->desiredCapabilities = array();
        $this->sl_firefox17_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox17_osx10_6->desiredCapabilities['version'] = "17";

        $this->sl_firefox16_osx10_6 = new BaseObject;
        $this->sl_firefox16_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox16_osx10_6->browser = 'firefox';
        $this->sl_firefox16_osx10_6->desiredCapabilities = array();
        $this->sl_firefox16_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox16_osx10_6->desiredCapabilities['version'] = "16";

        $this->sl_firefox15_osx10_6 = new BaseObject;
        $this->sl_firefox15_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox15_osx10_6->browser = 'firefox';
        $this->sl_firefox15_osx10_6->desiredCapabilities = array();
        $this->sl_firefox15_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox15_osx10_6->desiredCapabilities['version'] = "15";

        $this->sl_firefox14_osx10_6 = new BaseObject;
        $this->sl_firefox14_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox14_osx10_6->browser = 'firefox';
        $this->sl_firefox14_osx10_6->desiredCapabilities = array();
        $this->sl_firefox14_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox14_osx10_6->desiredCapabilities['version'] = "14";

        $this->sl_firefox13_osx10_6 = new BaseObject;
        $this->sl_firefox13_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox13_osx10_6->browser = 'firefox';
        $this->sl_firefox13_osx10_6->desiredCapabilities = array();
        $this->sl_firefox13_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox13_osx10_6->desiredCapabilities['version'] = "13";

        $this->sl_firefox12_osx10_6 = new BaseObject;
        $this->sl_firefox12_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox12_osx10_6->browser = 'firefox';
        $this->sl_firefox12_osx10_6->desiredCapabilities = array();
        $this->sl_firefox12_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox12_osx10_6->desiredCapabilities['version'] = "12";

        $this->sl_firefox11_osx10_6 = new BaseObject;
        $this->sl_firefox11_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox11_osx10_6->browser = 'firefox';
        $this->sl_firefox11_osx10_6->desiredCapabilities = array();
        $this->sl_firefox11_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox11_osx10_6->desiredCapabilities['version'] = "11";

        $this->sl_firefox10_osx10_6 = new BaseObject;
        $this->sl_firefox10_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox10_osx10_6->browser = 'firefox';
        $this->sl_firefox10_osx10_6->desiredCapabilities = array();
        $this->sl_firefox10_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox10_osx10_6->desiredCapabilities['version'] = "10";

        $this->sl_firefox9_osx10_6 = new BaseObject;
        $this->sl_firefox9_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox9_osx10_6->browser = 'firefox';
        $this->sl_firefox9_osx10_6->desiredCapabilities = array();
        $this->sl_firefox9_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox9_osx10_6->desiredCapabilities['version'] = "9";

        $this->sl_firefox8_osx10_6 = new BaseObject;
        $this->sl_firefox8_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox8_osx10_6->browser = 'firefox';
        $this->sl_firefox8_osx10_6->desiredCapabilities = array();
        $this->sl_firefox8_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox8_osx10_6->desiredCapabilities['version'] = "8";

        $this->sl_firefox7_osx10_6 = new BaseObject;
        $this->sl_firefox7_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox7_osx10_6->browser = 'firefox';
        $this->sl_firefox7_osx10_6->desiredCapabilities = array();
        $this->sl_firefox7_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox7_osx10_6->desiredCapabilities['version'] = "7";

        $this->sl_firefox6_osx10_6 = new BaseObject;
        $this->sl_firefox6_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox6_osx10_6->browser = 'firefox';
        $this->sl_firefox6_osx10_6->desiredCapabilities = array();
        $this->sl_firefox6_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox6_osx10_6->desiredCapabilities['version'] = "6";

        $this->sl_firefox5_osx10_6 = new BaseObject;
        $this->sl_firefox5_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox5_osx10_6->browser = 'firefox';
        $this->sl_firefox5_osx10_6->desiredCapabilities = array();
        $this->sl_firefox5_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox5_osx10_6->desiredCapabilities['version'] = "5";

        $this->sl_firefox4_osx10_6 = new BaseObject;
        $this->sl_firefox4_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox4_osx10_6->browser = 'firefox';
        $this->sl_firefox4_osx10_6->desiredCapabilities = array();
        $this->sl_firefox4_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_firefox4_osx10_6->desiredCapabilities['version'] = "4";

        $this->sl_chrome28_osx10_6 = new BaseObject;
        $this->sl_chrome28_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome28_osx10_6->browser = 'chrome';
        $this->sl_chrome28_osx10_6->desiredCapabilities = array();
        $this->sl_chrome28_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_chrome28_osx10_6->desiredCapabilities['version'] = "28";

        $this->sl_ie5_osx10_6 = new BaseObject;
        $this->sl_ie5_osx10_6->adapter = 'SauceLabsWebDriver';
        $this->sl_ie5_osx10_6->browser = 'internet explorer';
        $this->sl_ie5_osx10_6->desiredCapabilities = array();
        $this->sl_ie5_osx10_6->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_ie5_osx10_6->desiredCapabilities['version'] = "5";

        # OSX 10.8 Mountain Lion
        $this->sl_chrome27_osx10_8 = new BaseObject;
        $this->sl_chrome27_osx10_8->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome27_osx10_8->browser = 'chrome';
        $this->sl_chrome27_osx10_8->desiredCapabilities = array();
        $this->sl_chrome27_osx10_8->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_chrome27_osx10_8->desiredCapabilities['version'] = "27";

        $this->sl_ie6_osx10_8 = new BaseObject;
        $this->sl_ie6_osx10_8->adapter = 'SauceLabsWebDriver';
        $this->sl_ie6_osx10_8->browser = 'internet explorer';
        $this->sl_ie6_osx10_8->desiredCapabilities = array();
        $this->sl_ie6_osx10_8->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_ie6_osx10_8->desiredCapabilities['version'] = "6";

        # Linux
        $this->sl_firefox25_linux = new BaseObject;
        $this->sl_firefox25_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox25_linux->browser = 'firefox';
        $this->sl_firefox25_linux->desiredCapabilities = array();
        $this->sl_firefox25_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox25_linux->desiredCapabilities['version'] = "25";

        $this->sl_firefox24_linux = new BaseObject;
        $this->sl_firefox24_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox24_linux->browser = 'firefox';
        $this->sl_firefox24_linux->desiredCapabilities = array();
        $this->sl_firefox24_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox24_linux->desiredCapabilities['version'] = "24";

        $this->sl_firefox23_linux = new BaseObject;
        $this->sl_firefox23_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox23_linux->browser = 'firefox';
        $this->sl_firefox23_linux->desiredCapabilities = array();
        $this->sl_firefox23_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox23_linux->desiredCapabilities['version'] = "23";

        $this->sl_firefox22_linux = new BaseObject;
        $this->sl_firefox22_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox22_linux->browser = 'firefox';
        $this->sl_firefox22_linux->desiredCapabilities = array();
        $this->sl_firefox22_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox22_linux->desiredCapabilities['version'] = "22";

        $this->sl_firefox21_linux = new BaseObject;
        $this->sl_firefox21_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox21_linux->browser = 'firefox';
        $this->sl_firefox21_linux->desiredCapabilities = array();
        $this->sl_firefox21_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox21_linux->desiredCapabilities['version'] = "21";

        $this->sl_firefox20_linux = new BaseObject;
        $this->sl_firefox20_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox20_linux->browser = 'firefox';
        $this->sl_firefox20_linux->desiredCapabilities = array();
        $this->sl_firefox20_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox20_linux->desiredCapabilities['version'] = "20";

        $this->sl_firefox19_linux = new BaseObject;
        $this->sl_firefox19_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox19_linux->browser = 'firefox';
        $this->sl_firefox19_linux->desiredCapabilities = array();
        $this->sl_firefox19_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox19_linux->desiredCapabilities['version'] = "19";

        $this->sl_firefox18_linux = new BaseObject;
        $this->sl_firefox18_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox18_linux->browser = 'firefox';
        $this->sl_firefox18_linux->desiredCapabilities = array();
        $this->sl_firefox18_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox18_linux->desiredCapabilities['version'] = "18";

        $this->sl_firefox17_linux = new BaseObject;
        $this->sl_firefox17_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox17_linux->browser = 'firefox';
        $this->sl_firefox17_linux->desiredCapabilities = array();
        $this->sl_firefox17_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox17_linux->desiredCapabilities['version'] = "17";

        $this->sl_firefox16_linux = new BaseObject;
        $this->sl_firefox16_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox16_linux->browser = 'firefox';
        $this->sl_firefox16_linux->desiredCapabilities = array();
        $this->sl_firefox16_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox16_linux->desiredCapabilities['version'] = "16";

        $this->sl_firefox15_linux = new BaseObject;
        $this->sl_firefox15_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox15_linux->browser = 'firefox';
        $this->sl_firefox15_linux->desiredCapabilities = array();
        $this->sl_firefox15_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox15_linux->desiredCapabilities['version'] = "15";

        $this->sl_firefox14_linux = new BaseObject;
        $this->sl_firefox14_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox14_linux->browser = 'firefox';
        $this->sl_firefox14_linux->desiredCapabilities = array();
        $this->sl_firefox14_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox14_linux->desiredCapabilities['version'] = "14";

        $this->sl_firefox13_linux = new BaseObject;
        $this->sl_firefox13_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox13_linux->browser = 'firefox';
        $this->sl_firefox13_linux->desiredCapabilities = array();
        $this->sl_firefox13_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox13_linux->desiredCapabilities['version'] = "13";

        $this->sl_firefox12_linux = new BaseObject;
        $this->sl_firefox12_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox12_linux->browser = 'firefox';
        $this->sl_firefox12_linux->desiredCapabilities = array();
        $this->sl_firefox12_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox12_linux->desiredCapabilities['version'] = "12";

        $this->sl_firefox11_linux = new BaseObject;
        $this->sl_firefox11_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox11_linux->browser = 'firefox';
        $this->sl_firefox11_linux->desiredCapabilities = array();
        $this->sl_firefox11_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox11_linux->desiredCapabilities['version'] = "11";

        $this->sl_firefox10_linux = new BaseObject;
        $this->sl_firefox10_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox10_linux->browser = 'firefox';
        $this->sl_firefox10_linux->desiredCapabilities = array();
        $this->sl_firefox10_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox10_linux->desiredCapabilities['version'] = "10";

        $this->sl_firefox9_linux = new BaseObject;
        $this->sl_firefox9_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox9_linux->browser = 'firefox';
        $this->sl_firefox9_linux->desiredCapabilities = array();
        $this->sl_firefox9_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox9_linux->desiredCapabilities['version'] = "9";

        $this->sl_firefox8_linux = new BaseObject;
        $this->sl_firefox8_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox8_linux->browser = 'firefox';
        $this->sl_firefox8_linux->desiredCapabilities = array();
        $this->sl_firefox8_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox8_linux->desiredCapabilities['version'] = "8";

        $this->sl_firefox7_linux = new BaseObject;
        $this->sl_firefox7_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox7_linux->browser = 'firefox';
        $this->sl_firefox7_linux->desiredCapabilities = array();
        $this->sl_firefox7_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox7_linux->desiredCapabilities['version'] = "7";

        $this->sl_firefox6_linux = new BaseObject;
        $this->sl_firefox6_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox6_linux->browser = 'firefox';
        $this->sl_firefox6_linux->desiredCapabilities = array();
        $this->sl_firefox6_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox6_linux->desiredCapabilities['version'] = "6";

        $this->sl_firefox5_linux = new BaseObject;
        $this->sl_firefox5_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox5_linux->browser = 'firefox';
        $this->sl_firefox5_linux->desiredCapabilities = array();
        $this->sl_firefox5_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox5_linux->desiredCapabilities['version'] = "5";

        $this->sl_firefox4_linux = new BaseObject;
        $this->sl_firefox4_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_firefox4_linux->browser = 'firefox';
        $this->sl_firefox4_linux->desiredCapabilities = array();
        $this->sl_firefox4_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_firefox4_linux->desiredCapabilities['version'] = "4";

        $this->sl_chrome31_win7 = new BaseObject;
        $this->sl_chrome31_win7->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome31_win7->browser = 'chrome';
        $this->sl_chrome31_win7->desiredCapabilities = array();
        $this->sl_chrome31_win7->desiredCapabilities['platform'] = "Linux";
        $this->sl_chrome31_win7->desiredCapabilities['version'] = "31";

        $this->sl_chrome30_linux = new BaseObject;
        $this->sl_chrome30_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome30_linux->browser = 'chrome';
        $this->sl_chrome30_linux->desiredCapabilities = array();
        $this->sl_chrome30_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_chrome30_linux->desiredCapabilities['version'] = "30";

        $this->sl_chrome29_linux = new BaseObject;
        $this->sl_chrome29_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome29_linux->browser = 'chrome';
        $this->sl_chrome29_linux->desiredCapabilities = array();
        $this->sl_chrome29_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_chrome29_linux->desiredCapabilities['version'] = "29";

        $this->sl_chrome28_linux = new BaseObject;
        $this->sl_chrome28_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome28_linux->browser = 'chrome';
        $this->sl_chrome28_linux->desiredCapabilities = array();
        $this->sl_chrome28_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_chrome28_linux->desiredCapabilities['version'] = "28";

        $this->sl_chrome27_linux = new BaseObject;
        $this->sl_chrome27_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome27_linux->browser = 'chrome';
        $this->sl_chrome27_linux->desiredCapabilities = array();
        $this->sl_chrome27_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_chrome27_linux->desiredCapabilities['version'] = "27";

        $this->sl_chrome26_linux = new BaseObject;
        $this->sl_chrome26_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_chrome26_linux->browser = 'chrome';
        $this->sl_chrome26_linux->desiredCapabilities = array();
        $this->sl_chrome26_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_chrome26_linux->desiredCapabilities['version'] = "26";

        $this->sl_opera12_linux = new BaseObject;
        $this->sl_opera12_linux->adapter = 'SauceLabsWebDriver';
        $this->sl_opera12_linux->browser = 'opera';
        $this->sl_opera12_linux->desiredCapabilities = array();
        $this->sl_opera12_linux->desiredCapabilities['platform'] = "Linux";
        $this->sl_opera12_linux->desiredCapabilities['version'] = "12";

        # iOS - iPad
        $this->sl_safari_ipad_ios6_1_portrait = new BaseObject;
        $this->sl_safari_ipad_ios6_1_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_ipad_ios6_1_portrait->browser = 'ipad';
        $this->sl_safari_ipad_ios6_1_portrait->desiredCapabilities = array();
        $this->sl_safari_ipad_ios6_1_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_ipad_ios6_1_portrait->desiredCapabilities['version'] = "6.1";
        $this->sl_safari_ipad_ios6_1_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_safari_ipad_ios6_1_landscape = new BaseObject;
        $this->sl_safari_ipad_ios6_1_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_ipad_ios6_1_landscape->browser = 'ipad';
        $this->sl_safari_ipad_ios6_1_landscape->desiredCapabilities = array();
        $this->sl_safari_ipad_ios6_1_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_ipad_ios6_1_landscape->desiredCapabilities['version'] = "6.1";
        $this->sl_safari_ipad_ios6_1_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->sl_safari_ipad_ios6_0_portrait = new BaseObject;
        $this->sl_safari_ipad_ios6_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_ipad_ios6_0_portrait->browser = 'ipad';
        $this->sl_safari_ipad_ios6_0_portrait->desiredCapabilities = array();
        $this->sl_safari_ipad_ios6_0_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_ipad_ios6_0_portrait->desiredCapabilities['version'] = "6.0";
        $this->sl_safari_ipad_ios6_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_safari_ipad_ios6_0_landscape = new BaseObject;
        $this->sl_safari_ipad_ios6_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_ipad_ios6_0_landscape->browser = 'ipad';
        $this->sl_safari_ipad_ios6_0_landscape->desiredCapabilities = array();
        $this->sl_safari_ipad_ios6_0_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_ipad_ios6_0_landscape->desiredCapabilities['version'] = "6.0";
        $this->sl_safari_ipad_ios6_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->sl_safari_ipad_ios5_1_portrait = new BaseObject;
        $this->sl_safari_ipad_ios5_1_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_ipad_ios5_1_portrait->browser = 'ipad';
        $this->sl_safari_ipad_ios5_1_portrait->desiredCapabilities = array();
        $this->sl_safari_ipad_ios5_1_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_ipad_ios5_1_portrait->desiredCapabilities['version'] = "5.1";
        $this->sl_safari_ipad_ios5_1_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_safari_ipad_ios5_1_landscape = new BaseObject;
        $this->sl_safari_ipad_ios5_1_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_ipad_ios5_1_landscape->browser = 'ipad';
        $this->sl_safari_ipad_ios5_1_landscape->desiredCapabilities = array();
        $this->sl_safari_ipad_ios5_1_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_ipad_ios5_1_landscape->desiredCapabilities['version'] = "5.1";
        $this->sl_safari_ipad_ios5_1_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->sl_safari_ipad_ios5_0_portrait = new BaseObject;
        $this->sl_safari_ipad_ios5_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_ipad_ios5_0_portrait->browser = 'ipad';
        $this->sl_safari_ipad_ios5_0_portrait->desiredCapabilities = array();
        $this->sl_safari_ipad_ios5_0_portrait->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_safari_ipad_ios5_0_portrait->desiredCapabilities['version'] = "5.0";
        $this->sl_safari_ipad_ios5_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_safari_ipad_ios5_0_landscape = new BaseObject;
        $this->sl_safari_ipad_ios5_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_ipad_ios5_0_landscape->browser = 'ipad';
        $this->sl_safari_ipad_ios5_0_landscape->desiredCapabilities = array();
        $this->sl_safari_ipad_ios5_0_landscape->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_safari_ipad_ios5_0_landscape->desiredCapabilities['version'] = "5.0";
        $this->sl_safari_ipad_ios5_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->sl_safari_ipad_ios4_portrait = new BaseObject;
        $this->sl_safari_ipad_ios4_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_ipad_ios4_portrait->browser = 'ipad';
        $this->sl_safari_ipad_ios4_portrait->desiredCapabilities = array();
        $this->sl_safari_ipad_ios4_portrait->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_safari_ipad_ios4_portrait->desiredCapabilities['version'] = "4";
        $this->sl_safari_ipad_ios4_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_safari_ipad_ios4_landscape = new BaseObject;
        $this->sl_safari_ipad_ios4_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_ipad_ios4_landscape->browser = 'ipad';
        $this->sl_safari_ipad_ios4_landscape->desiredCapabilities = array();
        $this->sl_safari_ipad_ios4_landscape->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_safari_ipad_ios4_landscape->desiredCapabilities['version'] = "4";
        $this->sl_safari_ipad_ios4_landscape->desiredCapabilities['device-orientation'] = "landscape";

        # iOS - iPhone
        $this->sl_safari_iphone_ios6_1_portrait = new BaseObject;
        $this->sl_safari_iphone_ios6_1_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_iphone_ios6_1_portrait->browser = 'iphone';
        $this->sl_safari_iphone_ios6_1_portrait->desiredCapabilities = array();
        $this->sl_safari_iphone_ios6_1_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_iphone_ios6_1_portrait->desiredCapabilities['version'] = "6.1";
        $this->sl_safari_iphone_ios6_1_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_safari_iphone_ios6_1_landscape = new BaseObject;
        $this->sl_safari_iphone_ios6_1_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_iphone_ios6_1_landscape->browser = 'iphone';
        $this->sl_safari_iphone_ios6_1_landscape->desiredCapabilities = array();
        $this->sl_safari_iphone_ios6_1_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_iphone_ios6_1_landscape->desiredCapabilities['version'] = "6.1";
        $this->sl_safari_iphone_ios6_1_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->sl_safari_iphone_ios6_0_portrait = new BaseObject;
        $this->sl_safari_iphone_ios6_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_iphone_ios6_0_portrait->browser = 'iphone';
        $this->sl_safari_iphone_ios6_0_portrait->desiredCapabilities = array();
        $this->sl_safari_iphone_ios6_0_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_iphone_ios6_0_portrait->desiredCapabilities['version'] = "6.0";
        $this->sl_safari_iphone_ios6_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_safari_iphone_ios6_0_landscape = new BaseObject;
        $this->sl_safari_iphone_ios6_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_iphone_ios6_0_landscape->browser = 'iphone';
        $this->sl_safari_iphone_ios6_0_landscape->desiredCapabilities = array();
        $this->sl_safari_iphone_ios6_0_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_iphone_ios6_0_landscape->desiredCapabilities['version'] = "6.0";
        $this->sl_safari_iphone_ios6_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->sl_safari_iphone_ios5_1_portrait = new BaseObject;
        $this->sl_safari_iphone_ios5_1_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_iphone_ios5_1_portrait->browser = 'iphone';
        $this->sl_safari_iphone_ios5_1_portrait->desiredCapabilities = array();
        $this->sl_safari_iphone_ios5_1_portrait->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_iphone_ios5_1_portrait->desiredCapabilities['version'] = "5.1";
        $this->sl_safari_iphone_ios5_1_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_safari_iphone_ios5_1_landscape = new BaseObject;
        $this->sl_safari_iphone_ios5_1_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_iphone_ios5_1_landscape->browser = 'iphone';
        $this->sl_safari_iphone_ios5_1_landscape->desiredCapabilities = array();
        $this->sl_safari_iphone_ios5_1_landscape->desiredCapabilities['platform'] = "OS X 10.8";
        $this->sl_safari_iphone_ios5_1_landscape->desiredCapabilities['version'] = "5.1";
        $this->sl_safari_iphone_ios5_1_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->sl_safari_iphone_ios5_0_portrait = new BaseObject;
        $this->sl_safari_iphone_ios5_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_iphone_ios5_0_portrait->browser = 'iphone';
        $this->sl_safari_iphone_ios5_0_portrait->desiredCapabilities = array();
        $this->sl_safari_iphone_ios5_0_portrait->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_safari_iphone_ios5_0_portrait->desiredCapabilities['version'] = "5.0";
        $this->sl_safari_iphone_ios5_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_safari_iphone_ios5_0_landscape = new BaseObject;
        $this->sl_safari_iphone_ios5_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_iphone_ios5_0_landscape->browser = 'iphone';
        $this->sl_safari_iphone_ios5_0_landscape->desiredCapabilities = array();
        $this->sl_safari_iphone_ios5_0_landscape->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_safari_iphone_ios5_0_landscape->desiredCapabilities['version'] = "5.0";
        $this->sl_safari_iphone_ios5_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        $this->sl_safari_iphone_ios4_portrait = new BaseObject;
        $this->sl_safari_iphone_ios4_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_iphone_ios4_portrait->browser = 'iphone';
        $this->sl_safari_iphone_ios4_portrait->desiredCapabilities = array();
        $this->sl_safari_iphone_ios4_portrait->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_safari_iphone_ios4_portrait->desiredCapabilities['version'] = "4";
        $this->sl_safari_iphone_ios4_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_safari_iphone_ios4_landscape = new BaseObject;
        $this->sl_safari_iphone_ios4_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_safari_iphone_ios4_landscape->browser = 'iphone';
        $this->sl_safari_iphone_ios4_landscape->desiredCapabilities = array();
        $this->sl_safari_iphone_ios4_landscape->desiredCapabilities['platform'] = "OS X 10.6";
        $this->sl_safari_iphone_ios4_landscape->desiredCapabilities['version'] = "4";
        $this->sl_safari_iphone_ios4_landscape->desiredCapabilities['device-orientation'] = "landscape";

        # android 4.0 - phone
        $this->sl_android_phone_4_0_portrait = new BaseObject;
        $this->sl_android_phone_4_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_android_phone_4_0_portrait->browser = 'android';
        $this->sl_android_phone_4_0_portrait->desiredCapabilities = array();
        $this->sl_android_phone_4_0_portrait->desiredCapabilities['platform'] = "Linux";
        $this->sl_android_phone_4_0_portrait->desiredCapabilities['version'] = "4.0";
        $this->sl_android_phone_4_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_android_phone_4_0_landscape = new BaseObject;
        $this->sl_android_phone_4_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_android_phone_4_0_landscape->browser = 'android';
        $this->sl_android_phone_4_0_landscape->desiredCapabilities = array();
        $this->sl_android_phone_4_0_landscape->desiredCapabilities['platform'] = "Linux";
        $this->sl_android_phone_4_0_landscape->desiredCapabilities['version'] = "4.0";
        $this->sl_android_phone_4_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        # android 4.0 - tablet
        $this->sl_android_tablet_4_0_portrait = new BaseObject;
        $this->sl_android_tablet_4_0_portrait->adapter = 'SauceLabsWebDriver';
        $this->sl_android_tablet_4_0_portrait->browser = 'android';
        $this->sl_android_tablet_4_0_portrait->desiredCapabilities = array();
        $this->sl_android_tablet_4_0_portrait->desiredCapabilities['platform'] = "Linux";
        $this->sl_android_tablet_4_0_portrait->desiredCapabilities['version'] = "4.0";
        $this->sl_android_tablet_4_0_portrait->desiredCapabilities['device-type'] = "tablet";
        $this->sl_android_tablet_4_0_portrait->desiredCapabilities['device-orientation'] = "portrait";

        $this->sl_android_tablet_4_0_landscape = new BaseObject;
        $this->sl_android_tablet_4_0_landscape->adapter = 'SauceLabsWebDriver';
        $this->sl_android_tablet_4_0_landscape->browser = 'android';
        $this->sl_android_tablet_4_0_landscape->desiredCapabilities = array();
        $this->sl_android_tablet_4_0_landscape->desiredCapabilities['platform'] = "Linux";
        $this->sl_android_tablet_4_0_landscape->desiredCapabilities['version'] = "4.0";
        $this->sl_android_tablet_4_0_landscape->desiredCapabilities['device-type'] = "tablet";
        $this->sl_android_tablet_4_0_landscape->desiredCapabilities['device-orientation'] = "landscape";

        // all done
    }
}
