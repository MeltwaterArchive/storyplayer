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

/**
 * Base class for all curl prose
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Michael Heap <michael.heap@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class CurlBase extends Prose
{
	/**
	 * @var array The default curl opts
	 */
	protected $defaultOptions = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => 0
	);

	/**
	 * Execute the a curl request
	 * 
	 * @param string $url URL to request
	 * @param string $verb The Http verb
	 * @param array $params  params to add to the URL
	 * @param array $headers HTTP headers to use
	 * @param array $options Curl opts array
	 * 
	 * @return object|string Response sent by the server. If it's JSON, we'll decode it
	 */
	protected function doRequest($url, $verb, $params = array(), $headers = array(), $options = array())
	{
		// shorthand
		$st = $this->st;

		$verb = strtoupper($verb);

		// create a new cURL resource
		$ch = curl_init();

		// handle the verb correctly
		switch ($verb) {
			case 'GET':
				$url = $url . '?' . http_build_query($params);
				break;
			case 'POST':
			case 'PUT':
			case 'DELETE':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
				break;
		}

		// what are we doing?
		$log = $st->startAction("HTTP ${verb} '${url}'");

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt_array($ch, $options + $this->defaultOptions);

		// set headers
		if (!empty($headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		// grab URL and pass it to the browser
		$response = curl_exec($ch);
		$error = curl_error($ch);

		// close cURL resource, and free up system resources
		curl_close($ch);

		if ($error){
			throw new E5xx_ActionFailed(__CLASS__.': '.$error);
		}

		// Try and decode it
		$decoded = json_decode($response);

		if ($decoded){
			$response = $decoded;
		}

		// all done
		return $response;
	}
}
