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

namespace DataSift\Storyplayer\StoryLib;

use Exception;
use PHPUnit_Framework_TestCase;

// load our global functions, used for defining stories
require APP_TOPDIR . "/DataSift/Storyplayer/functions.php";

class StoryLoaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Storyplayer\StoryLib\StoryLoader::loadStory
	 */
	public function testReturnsStoryLoadedFromFile()
	{
		// ----------------------------------------------------------------
		// setup the test

		$filename        = __DIR__ . '/test-stories/BasicStory.php';

	    // ----------------------------------------------------------------
	    // perform the change

		$obj = StoryLoader::loadStory($filename);

		// ----------------------------------------------------------------
		// test the results

		$this->assertTrue($obj instanceof Story);
	}

	/**
	 * @covers DataSift\Storyplayer\StoryLib\StoryLoader::loadStory
	 */
	public function testThrowsExceptionIfInvalidFilenameProvided()
	{
		// ----------------------------------------------------------------
		// setup the test

		$filename        = __DIR__ . '/test-stories/filedoesnotexist.php';
		$expectedCode    = 500;
		$expectedMessage = "Cannot find file '$filename' to load";

	    // ----------------------------------------------------------------
	    // perform the change

		// this should throw an exception
		$caughtException = false;
		try {
			StoryLoader::loadStory($filename);
		}
		catch (Exception $e) {
			$caughtException = $e;
		}

		// ----------------------------------------------------------------
		// test the results

		$this->assertTrue($caughtException instanceof E5xx_InvalidStoryFile);
		$this->assertEquals($expectedCode, $caughtException->getCode());
		$this->assertEquals($expectedMessage, $caughtException->getMessage());
	}

	/**
	 * @covers DataSift\Storyplayer\StoryLib\StoryLoader::loadStory
	 */
	public function testThrowsExceptionIfNoStoryVariableDefined()
	{
		// ----------------------------------------------------------------
		// setup the test

		$filename 		 = __DIR__ . '/test-stories/DoesNotDeclareStory.php';
		$expectedCode    = 500;
		$expectedMessage = "Story file '$filename' did not create the \$story variable";

	    // ----------------------------------------------------------------
	    // perform the change

		// this should throw an exception
		$caughtException = false;
		try {
			StoryLoader::loadStory($filename);
		}
		catch (Exception $e) {
			$caughtException = $e;
		}

		// ----------------------------------------------------------------
		// test the results

		$this->assertTrue($caughtException instanceof E5xx_InvalidStoryFile);
		$this->assertEquals($expectedCode, $caughtException->getCode());
		$this->assertEquals($expectedMessage, $caughtException->getMessage());
	}

	/**
	 * @covers DataSift\Storyplayer\StoryLib\StoryLoader::loadStory
	 */
	public function testThrowsExceptionIfStoryVariableDefinedWithWrongType()
	{
		// ----------------------------------------------------------------
		// setup the test

		$filename 		 = __DIR__ . '/test-stories/DeclaresStoryOfWrongType.php';
		$expectedCode    = 500;
		$expectedMessage = "Story file '{$filename}' did create a \$story variable, but it is of type 'stdClass' instead of type 'DataSift\Storyplayer\StoryLib\Story'";

	    // ----------------------------------------------------------------
	    // perform the change

		// this should throw an exception
		$caughtException = false;
		try {
			StoryLoader::loadStory($filename);
		}
		catch (Exception $e) {
			$caughtException = $e;
		}

		// ----------------------------------------------------------------
		// test the results

		$this->assertTrue($caughtException instanceof E5xx_InvalidStoryFile);
		$this->assertEquals($expectedCode, $caughtException->getCode());
		$this->assertEquals($expectedMessage, $caughtException->getMessage());
	}
}
