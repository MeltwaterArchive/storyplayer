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
use DataSift\BrowserMobProxy\BrowserMobProxyClient;
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
class LocalWebDriverAdapter extends BaseAdapter implements DeviceAdapter
{
	public function start(StoryTeller $st)
	{
		$httpProxy = new BrowserMobProxyClient();
		$httpProxy->enableFeature('enhancedReplies');

		$this->proxySession = $httpProxy->createProxy();

		// start recording
		$this->proxySession->startHAR();

		// create the browser session
		$webDriver = new WebDriverClient();
		$this->browserSession = $webDriver->newSession(
			$this->browserDetails->browser,
			array(
				'proxy' => $this->proxySession->getWebDriverProxyConfig()
			) + $this->browserDetails->desiredCapabilities

		);
	}

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

	public function applyHttpBasicAuthForHost($hostname, $url)
	{
		// get the auth credentials
		$credentials = $this->getHttpBasicAuthForHost($hostname);

		if (isset($this->proxySession)) {
			$this->proxySession->setHttpBasicAuth($hostname, $credentials['user'], $credentials['pass']);
		}

		// all done
		return $url;
	}
}