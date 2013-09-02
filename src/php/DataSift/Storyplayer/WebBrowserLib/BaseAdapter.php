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
 * @package   Storyplayer/WebBrowserLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\WebBrowserLib;

/**
 * Base class for web browser adapters
 *
 * @category    Libraries
 * @package     Storyplayer/WebBrowserLib
 * @author      Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright   2011-present Mediasift Ltd www.datasift.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://datasift.github.io/storyplayer
 */
class BaseAdapter
{
	/**
	 * details about the web browser that we want to use
	 *
	 * @var stdClass
	 */
	protected $browserDetails;

	protected $browserSession;

	protected $proxySession;

	protected $httpAuthDetails = array();

	public function init($browserDetails)
	{
		$this->browserDetails = $browserDetails;
	}

	public function getProxy()
	{
		return $this->proxySession();
	}

	public function getWebBrowser()
	{
		return $this->browserSession;
	}

	public function applyHttpBasicAuthForHost($hostname, $url)
	{
		throw new E5xx_NoHttpBasicAuthSupport();
	}

	public function hasHttpBasicAuthForHost($hostname)
	{
		return (isset($this->httpAuthDetails[$hostname]));
	}

	public function getHttpBasicAuthForHost($hostname)
	{
		if (!isset($this->httpAuthDetails[$hostname])) {
			return null;
		}

		return $this->httpAuthDetails[$hostname];
	}

	public function setHttpBasicAuthForHost($hostname, $username, $password)
	{
		$this->httpAuthDetails[$hostname] = array(
			'user' => $username,
			'pass' => $password
		);
	}
}