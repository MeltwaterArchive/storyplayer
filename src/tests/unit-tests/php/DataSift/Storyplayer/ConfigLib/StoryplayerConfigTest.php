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
 * @package     Storyplayer/ConfigLib
 * @author      Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright   2011-present Mediasift Ltd www.datasift.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\ConfigLib;

use stdClass;
use PHPUnit_Framework_TestCase;
use Mockery;
use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Storyplayer\Injectables;

class StoryplayerConfigTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::__construct()
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof StoryplayerConfig);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::__construct
	 */
	public function testStartsWithEmptyConfig()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expected = new BaseObject;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // test the results

	    $actual = $obj->getConfig();
	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::__construct
	 */
	public function testStartsWithNoName()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expected = StoryplayerConfig::NO_NAME;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // test the results

	    $actual = $obj->getName();
	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::getConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::setConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::getName()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::setName()
	 */
	public function testCanLoadConfigFile()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedConfig = new BaseObject;
	    $expectedConfig->defaults = [
	    	"-s", "storyplayer-2.0"
	    ];
	    $expectedName = "storyplayer-config-1";

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/storyplayer-config-1.json");

	    // ----------------------------------------------------------------
	    // test the results

	    $actualConfig = $obj->getConfig();
	    $actualName   = $obj->getName();

	    $this->assertEquals($expectedConfig, $actualConfig);
	    $this->assertEquals($expectedName, $actualName);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateIsObject()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateDefaultsSection()
	 */
	public function testCanValidateConfig()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/storyplayer-config-1.json");

	    // ----------------------------------------------------------------
	    // test the results

	    $obj->validateConfig();
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateIsObject()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_StoryplayerConfigMustBeAnObject
	 */
	public function testConfigMustBeAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/storyplayer-config-as-an-array.json");

	    // ----------------------------------------------------------------
	    // test the results

	    $obj->validateConfig();
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateDefaultsSection()
	 */
	public function testDefaultsSectionIsOptional()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/storyplayer-config-with-no-defaults.json");

	    // ----------------------------------------------------------------
	    // test the results

	    $obj->validateConfig();
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateDefaultsSection()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_StoryplayerDefaultsSectionMustBeAnArray
	 */
	public function testDefaultsSectionMustBeAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/storyplayer-config-with-defaults-as-object.json");

	    // ----------------------------------------------------------------
	    // test the results

	    $obj->validateConfig();
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateDefaultsSection()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_StoryplayerDefaultsMustBeStrings
	 */
	public function testDefaultsSectionCannotContainFalse()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/storyplayer-config-with-defaults-contains-false.json");

	    // ----------------------------------------------------------------
	    // test the results

	    $obj->validateConfig();
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateDefaultsSection()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_StoryplayerDefaultsMustBeStrings
	 */
	public function testDefaultsSectionCannotContainTrue()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/storyplayer-config-with-defaults-contains-true.json");

	    // ----------------------------------------------------------------
	    // test the results

	    $obj->validateConfig();
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateDefaultsSection()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_StoryplayerDefaultsMustBeStrings
	 */
	public function testDefaultsSectionCannotContainNull()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/storyplayer-config-with-defaults-contains-null.json");

	    // ----------------------------------------------------------------
	    // test the results

	    $obj->validateConfig();
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateDefaultsSection()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_StoryplayerDefaultsMustBeStrings
	 */
	public function testDefaultsSectionCannotContainArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/storyplayer-config-with-defaults-contains-array.json");

	    // ----------------------------------------------------------------
	    // test the results

	    $obj->validateConfig();
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\StoryplayerConfig::validateDefaultsSection()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_StoryplayerDefaultsMustBeStrings
	 */
	public function testDefaultsSectionCannotContainObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/storyplayer-config-with-defaults-contains-object.json");

	    // ----------------------------------------------------------------
	    // test the results

	    $obj->validateConfig();
	}

}