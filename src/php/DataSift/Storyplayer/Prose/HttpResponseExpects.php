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
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Storyplayer\ProseLib\Prose;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\DataLib\DataPrinter;
use DataSift\Stone\HttpLib\HttpClientResponse;

/**
 * test the contents of a HttpClientResponse (retrieved by using
 * $st->fromHttp()->get() et al)
 *
 * great for testing APIs
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class HttpResponseExpects extends Prose
{
	/**
	 * DO NOT TYPEHINT $response!!
	 *
	 * Although it is extra work for us, if we use typehinting here, then
	 * we get a failure in the PHP engine (which is harder to handle).
	 * It's currently considered to be better if we detect the error
	 * ourselves.
	 *
	 * @param StoryTeller $st       [description]
	 * @param [type]      $response [description]
	 */
	public function __construct(StoryTeller $st, $params)
	{
		// do we HAVE a valid response?
		if (!isset($params[0])) {
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse object", "missing object");
		}
		$response = $params[0];

		if (!is_object($response)) {
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse object", gettype($response));
		}
		if (!$response instanceof HttpClientResponse) {
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse object", get_class($response));
		}

		// call our parent constructor
		parent::__construct($st, array($response));
	}

	public function hasStatusCode($status)
	{
		// shorthand
		$st = $this->st;
		$response = $this->args[0];

		// what are we doing?
		$log = $st->startAction("make sure HTTP response has status code '{$status}'");

		// do we even HAVE a response?
		if (!is_object($response)) {
			$log->endAction("no response to examine :(");
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse object", gettype($response));
		}
		if (!$response instanceof HttpClientResponse) {
			$log->endAction("no response to examine :(");
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse object", get_class($response));
		}

		if ($response->statusCode != $status) {
			$log->endAction("status code is '{$response->statusCode}'");
			throw new E5xx_ExpectFailed(__METHOD__, $status, $response->statusCode);
		}

		// if we get here, all is well
		$log->endAction();
	}

	public function hasBody($expectedBody)
	{
		// shorthand
		$st = $this->st;
		$response = $this->args[0];

		// make a printable version of $expectedBody
		$printer = new DataPrinter();
		$logValue = $printer->convertToString($expectedBody);

		if (strlen($logValue) > 1024) {
			$logValue = substr($logValue, 0, 1024) + '...';
		}

		// what are we doing?
		$log = $st->startAction("make sure HTTP response has body '{$logValue}'");

		// do the comparison
		$st->assertsString($response->getBody())->equals($expectedBody);

		// if we get here, all is well
		$log->endAction();
	}
}