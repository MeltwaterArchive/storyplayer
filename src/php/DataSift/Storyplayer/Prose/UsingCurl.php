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
 * @package   Storyplayer/Prose
 * @author    Michael Heap <michael.heap@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Storyplayer\Prose\CurlBase;

/**
 * get information from a HTTP server, without using a web browser to
 * get it.
 *
 * great for testing APIs
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Michael Heap <michael.heap@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingCurl extends CurlBase
{
	/**
	 * post
	 *
	 * @param mixed $url URL to request
	 * @param array $params GET params to add to the URL
	 * @param array $headers HTTP headers to use
	 * @param array $options Curl opts array
	 * 
	 * @return object|string Response sent by the server. If it's JSON, we'll decode it
	 */
	public function post($url, $params = array(), $headers = array(), $options = array())
	{
		return $this->doRequest($url, 'POST', $params, $headers, $options);
	}

	/**
	 * put
	 *
	 * @param mixed $url URL to request
	 * @param array $params GET params to add to the URL
	 * @param array $headers HTTP headers to use
	 * @param array $options Curl opts array
	 * 
	 * @return object|string Response sent by the server. If it's JSON, we'll decode it
	 */
	public function put($url, $params = array(), $headers = array(), $options = array())
	{
		return $this->doRequest($url, 'PUT', $params, $headers, $options);
	}

	/**
	 * delete
	 *
	 * @param mixed $url URL to request
	 * @param array $params GET params to add to the URL
	 * @param array $headers HTTP headers to use
	 * @param array $options Curl opts array
	 * 
	 * @return object|string Response sent by the server. If it's JSON, we'll decode it
	 */
	public function delete($url, $params = array(), $headers = array(), $options = array())
	{
		return $this->doRequest($url, 'DELETE', $params, $headers, $options);
	}
}

