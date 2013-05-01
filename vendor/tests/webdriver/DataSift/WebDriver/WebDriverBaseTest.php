<?php
// Copyright 2012-present MediaSift Ltd. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace DataSift\WebDriver;

use Exception;
use PHPUnit_Framework_TestCase;

class WebDriverBaseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // your code here
    }

    public function tearDown()
    {
        // your code here
    }

    public function testKnowsAboutAllTheErrorStatusCodes()
    {
    	// create a class to test
    	$obj = new WebDriverBaseForTests();

    	for ($i = 1; $i <=34; $i++) {
    		$className = $obj->returnExceptionToThrow($i);

    		$this->assertTrue(is_string($className));
    		$this->assertNotEquals('UnknownWebDriverError', $className);
    	}
    }

    public function testHasExceptionClassesForAllKnownErrorStatusCodes()
    {
    	// create a class to test
    	$obj = new WebDriverBaseForTests();

    	for ($i = 1; $i <=34; $i++) {
    		$className = $obj->returnExceptionToThrow($i);

    		$exceptionObj = new $className(null, null);
    		$this->assertTrue(is_object($exceptionObj));
    		$this->assertTrue($exceptionObj instanceof Exception);
    		$this->assertFalse($exceptionObj instanceof UnknownWebDriverError);
    	}
    }
}
