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

use Exception;
use DataSift\BrowserMobProxy\BrowserMobProxySession;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\WebDriver\WebDriverClient;

/**
 * The adapter that talks to Browsermob-proxy and Selenium-standalone-server
 * running on the same host as Storyplayer
 *
 * @category    Libraries
 * @package     Storyplayer/DeviceLib
 * @author      Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright   2011-present Mediasift Ltd www.datasift.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://datasift.github.io/storyplayer
 */
class SauceLabsWebDriverAdapter extends BaseAdapter implements DeviceAdapter
{
    /**
     *
     * @param  StoryTeller $st
     * @return void
     */
    public function start(StoryTeller $st)
    {
        // Sauce Labs handles proxying for us (if required)
        // via the Sauce Connect app

        // build the Sauce Labs url
        $url = "http://"
             . urlencode($this->browserDetails->saucelabs->username)
             . ':'
             . urlencode($this->browserDetails->saucelabs->accesskey)
             . '@ondemand.saucelabs.com/wd/hub';

        // build the Sauce Labs capabilities array
        $desiredCapabilities = $this->browserDetails->desiredCapabilities;

        // add the story's name, so that someone looking at the Sauce Labs
        // list of jobs can see what this browser was used for
        //
        // due to encoding errors at SauceLabs, we can't use '>' as a
        // delimiter in the story's name
        $story = $st->getStory();
        $desiredCapabilities['name'] = $st->getTestEnvironmentName() . ' / ' . $st->getCurrentPhase() . ': ' . $st->getCurrentPhaseName() . ' / '. $story->getName();

        // create the browser session
        $webDriver = new WebDriverClient($url);
        $this->browserSession = $webDriver->newSession(
            $this->browserDetails->browser,
            $desiredCapabilities
        );
    }

    /**
     *
     * @return void
     */
    public function stop()
    {
        // stop the web browser
        if (is_object($this->browserSession))
        {
            $this->browserSession->close();
            $this->browserSession = null;
        }

        // now stop the proxy
        if (is_object($this->proxySession))
        {
            try {
                $this->proxySession->close();
            }
            catch (Exception $e) {
                // do nothing - we don't care!
            }
            $this->proxySession = null;
        }
    }

    /*
     * this code was written to embed the HTTP Basic Auth details
     * into the URL we are testing
     *
     * unfortunately, at the time of writing, although it's the
     * documented way of doing this, it isn't correctly supported
     * in any browser that I've tested
     *
     * there's a ticket in with SauceLabs on this one
     *
     * I'm leaving this code here in case we're able to switch to it
     * in future, as the approach we're using for now is a great big
     * dirty hack
     *
    public function applyHttpBasicAuthForHost($hostname, $url)
    {
        // get the auth credentials
        $credentials = $this->getHttpBasicAuthForHost($hostname);

        // we're going to embed them in the URL
        $url = http_build_url($url, $credentials);

        // all done
        return $url;
    }*/

    /**
     *
     * @param  string $hostname
     * @param  string $url
     * @return string
     */
    public function applyHttpBasicAuthForHost($hostname, $url)
    {
        // get the auth credentials
        $credentials = $this->getHttpBasicAuthForHost($hostname);

        // get the proxy server
        //
        // this is an absolutely *horrible* hack, as we're relying on
        // there being absolutely no parallel testing going on for this
        // to work
        //
        // that said, SauceConnect also relies on exactly the same hack,
        // so as long as it works at all, so will this hack
        $proxySession = new BrowserMobProxySession('http://localhost:9090', 9091);
        $proxySession->setHttpBasicAuth($hostname, $credentials['user'], $credentials['pass']);

        // all done
        return $url;
    }
}