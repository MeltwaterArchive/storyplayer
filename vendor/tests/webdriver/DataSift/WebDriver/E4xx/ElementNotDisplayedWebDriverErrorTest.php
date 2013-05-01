<?php

/**
 * WebDriver - Client for Selenium 2 (a.k.a WebDriver)
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
 * @package   WebDriver
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2004-present Facebook
 * @copyright 2012-present MediaSift Ltd
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://www.datasift.com
 * @link      http://facebook.com
 */

namespace DataSift\WebDriver;

use PHPUnit_Framework_TestCase;

/**
 * Exception thrown when we attempt to interact with an element that is
 * outside the browser's current viewport (e.g. hidden elements)
 *
 * @category Libraries
 * @package  WebDriver
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 * @link     http://www.datasift.com
 * @link     http://facebook.com
 */

class E4xx_ElementNotDisplayedWebDriverErrorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\WebDriver\E4xx_ElementNotDisplayedWebDriverError::__construct
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new E4xx_ElementNotDisplayedWebDriverError('oh dear');

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof E4xx_ElementNotDisplayedWebDriverError);
	}

}
