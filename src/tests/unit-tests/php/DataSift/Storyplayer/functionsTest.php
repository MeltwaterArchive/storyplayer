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
 * @category    Libraries
 * @package     Storyplayer
 * @subpackage  Prose
 * @author      Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright   2011-present Mediasift Ltd www.datasift.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://datasift.github.io/storyplayer
 */

use DataSift\Storyplayer\PlayerLib\Story;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Storyplayer\Prose\E5xx_ProseException;
// load our global functions, used for defining stories
if (!function_exists("newStoryFor")) {
	require APP_TOPDIR . "/DataSift/Storyplayer/functions.php";
}

class FunctionsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers ::first
	 */
	public function testCanGetFirstElementOfAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $parts = [ 11, 12, 13 ];
	    $expectedResult = 11;

	    // ----------------------------------------------------------------
	    // perform the change

	    $actualResult = first($parts);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedResult, $actualResult);
	}

	/**
	 * @covers ::first
	 */
	public function testCanGetFirstElementOfAnArrayWithoutDisturbingIterator()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $parts = [ 11, 12, 13 ];
	    $expectedResult = $parts;

	    // ----------------------------------------------------------------
	    // perform the change
	    //
	    // here, we iterate over an array, whilst repeatedly getting the
	    // first element in the array
	    //
	    // this proves that first() does not cause a reset() on the array

	    $actualResult = [];
	    foreach ($parts as $part) {
	    	$actualResult[] = $part;
		    first($parts);
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedResult, $actualResult);
	}

	/**
	 * @covers ::first
	 */
	public function testFirstReturnsNullWhenParamIsNotAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $inputs = [
	    	"fred",
	    	1,
	    	3.14,
	    	null,
	    	(object)[ ' fred' ],
	    ];
	    $expectedResult = null;

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($inputs as $input) {
		    $actualResult = first($input);
	    	$this->assertEquals($expectedResult, $actualResult);
	    }
	}

	/**
	 * @covers ::first
	 */
	public function testFirstReturnsNullWhenArrayIsEmpty()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $parts = [ ];
	    $expectedResult = null;

	    // ----------------------------------------------------------------
	    // perform the change

	    $actualResult = first($parts);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedResult, $actualResult);
	}

	/**
	 * @covers ::hostWithRole
	 */
	public function testCanIterateOverHosts()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// the role to iterate over
		$rolename = 'test';

		// the hostnames that we expect to get
	    $expectedHosts = [ 'fred', 'alice', 'bob' ];

	    // our list of hostDetails to iterate over
	    $hosts = new stdClass;
	    foreach ($expectedHosts as $hostname) {
	    	$hosts->$hostname = (object)['name' => $hostname];
	    }

	   	// our fake Prose module to get the hostDetails
	    $fromRolesTable = Mockery::mock("DataSift\Storyplayer\Prose\FromRolesTable");
	    $fromRolesTable->shouldReceive('getDetailsForRole')->once()->with($rolename)->andReturn($hosts);

	    // a fake logging object
	    $log = Mockery::mock("DataSift\Storyplayer\PlayerLib\Action_LogItem");
	    $log->shouldReceive('endAction')->once();

	    // our fake $st object
	    $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");
	   	$st->shouldReceive('startAction')->andReturn($log);
	   	$st->shouldReceive('fromRolesTable')->once()->andReturn($fromRolesTable);

	    // ----------------------------------------------------------------
	    // perform the change

	   	$actualHosts = [];
	    foreach (hostWithRole('test') as $hostname) {
	    	$actualHosts[] = $hostname;
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedHosts, $actualHosts);
	}

	/**
	 * @covers ::hostWithRole
	 */
	public function testHostsIteratorThrowsExceptionForUnknownRole()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// the role to iterate over
		$rolename = 'test';

		// the hostnames that we expect to get
	    $expectedHosts = [ 'fred', 'alice', 'bob' ];

	    // our empty list of hostDetails to iterate over
	    $hosts = new stdClass;

	   	// our fake Prose module to get the hostDetails
	    $fromRolesTable = Mockery::mock("DataSift\Storyplayer\Prose\FromRolesTable");
	    $fromRolesTable->shouldReceive('getDetailsForRole')->once()->with($rolename)->andReturn($hosts);

	    // our fake $st object
	    $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");
	   	$st->shouldReceive('startAction')->never();
	   	$st->shouldReceive('fromRolesTable')->once()->andReturn($fromRolesTable);

	    // ----------------------------------------------------------------
	    // perform the change and test the results

    	$caughtException = false;
    	try {
		    foreach (hostWithRole('test') as $hostname) {
		    	$actualHosts[] = $hostname;
	    	}
	    }
    	catch (E5xx_ActionFailed $e) {
    		$caughtException = true;
    	}

    	$this->assertTrue($caughtException);
	}

	/**
	 * @covers ::newStoryFor
	 */
	public function testCanCreateStoryViaHelperFunction()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = newStoryFor("Storyplayer Unit Tests");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof Story);
	}

	/**
	 * @covers ::newStoryFor
	 */
	public function testSetsCategoryWhenStoryCreatedViaHelperFunction()
	{
	    // ----------------------------------------------------------------
		// setup the test

		$expectedCategory = "Storyplayer Unit Tests";

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = newStoryFor($expectedCategory);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof Story);
	    $this->assertEquals($expectedCategory, $obj->getCategory());
	}

	/**
	 * @covers ::newStoryFor
	 */
	public function testSetsSourceFilenameStoryWhenCreatedViaHelperFunction()
	{
	    // ----------------------------------------------------------------
		// setup the test

		$expectedFile = __FILE__;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = newStoryFor("Storyplayer Unit Tests");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof Story);
	    $this->assertEquals($expectedFile, $obj->getStoryFilename());
	}

	/**
	 * @covers ::tryTo
	 */
	public function testTryToCallsTheCallback()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedValue = 1;
	    $actualValue   = 0;

	    $callback = function() use ($expectedValue, &$actualValue) {
	    	$actualValue = $expectedValue;
	    };

	    // ----------------------------------------------------------------
	    // perform the change

	    tryTo($callback);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedValue, $actualValue);
	}

	/**
	 * @covers ::tryTo
	 */
	public function testTryToSwallowsProseExceptions()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $callback = function() {
	    	throw new E5xx_ProseException(500, "failed", "failed");
	    };
	    $caughtException = false;

	    // ----------------------------------------------------------------
	    // perform the change

	    try {
	    	tryTo($callback);
	    }
	    catch (Exception $e) {
	    	$caughtException = true;
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertFalse($caughtException);
	}
}
