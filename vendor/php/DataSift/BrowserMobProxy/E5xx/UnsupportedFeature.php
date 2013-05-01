<?php

/**
 * BrowserMobProxy - Client for browsermob-proxy
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category  Libraries
 * @package   BrowserMobProxy
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2012 MediaSift Ltd.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://www.datasift.com
 */

namespace DataSift\BrowserMobProxy;

use DataSift\Stone\ExceptionsLib\Exxx_Exception;

/**
 * Exception thrown when our copy of browsermob-proxy doesn't support a
 * feature that we want to use
 *
 * @category Libraries
 * @package  BrowserMobProxy
 * @author   Stuart Herbert <stuart.herbert@datasift.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 * @link     http://www.datasift.com
 */

class E5xx_UnsupportedFeature extends Exxx_Exception
{
	public function __construct($name) {
		$msg = "Unsupported browsermob-proxy feature: '{$name}'";
		parent::__construct(500, $msg, $msg);
	}
}