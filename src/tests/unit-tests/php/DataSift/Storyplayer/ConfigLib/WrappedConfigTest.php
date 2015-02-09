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

class WrappedConfigTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::__construct()
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new WrappedConfig();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof WrappedConfig);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::__construct
	 */
	public function testStartsWithEmptyConfig()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expected = new BaseObject;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new WrappedConfig();

	    // ----------------------------------------------------------------
	    // test the results

	    $actual = $obj->getConfig();
	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::__construct
	 */
	public function testCanStartWithEmptyArrayForConfig()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expected = [];

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new WrappedConfig(WrappedConfig::ROOT_IS_ARRAY);

	    // ----------------------------------------------------------------
	    // test the results

	    $actual = $obj->getConfig();
	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::__construct
	 */
	public function testStartsWithNoName()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expected = WrappedConfig::NO_NAME;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new WrappedConfig();

	    // ----------------------------------------------------------------
	    // test the results

	    $actual = $obj->getName();
	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::setConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getName()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::setName()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getFilename()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::setFilename()
	 */
	public function testCanLoadConfigFile()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedConfig = new BaseObject();
	    $expectedConfig->dummy1 = true;

	    $expectedName = "wrapped-config-1";

	    $expectedFilename = __DIR__ . "/wrapped-config-1.json";

	    $obj = new WrappedConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile($expectedFilename);

	    // ----------------------------------------------------------------
	    // test the results

		$actualConfig   = $obj->getConfig();
		$actualName     = $obj->getName();
		$actualFilename = $obj->getFilename();

	    $this->assertEquals($expectedConfig, $actualConfig);
	    $this->assertEquals($expectedName, $actualName);
	    $this->assertEquals($expectedFilename, $actualFilename);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getName()
	 */
	public function testThrowsExceptionWhenConfigFileNotFound()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedConfig = new BaseObject();
	    $expectedName = WrappedConfig::NO_NAME;

	    $obj = new WrappedConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $caughtException = false;
	    try {
		    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-does-not-exist.json");
		}
		catch (E4xx_ConfigFileNotFound $e) {
			$caughtException = true;
		}

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($caughtException);

		// make sure the rest of the object has been left alone
	    $actualConfig = $obj->getConfig();
	    $actualName   = $obj->getName();

	    $this->assertEquals($expectedConfig, $actualConfig);
	    $this->assertEquals($expectedName, $actualName);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getName()
	 */
	public function testThrowsExceptionWhenConfigFileUnreadable()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedConfig = new BaseObject();
	    $expectedName = WrappedConfig::NO_NAME;

	    $obj = new WrappedConfig();

	    // make sure the file cannot be read
	    chmod(__DIR__ . "/wrapped-config-cannot-be-read.json", 0);

	    // ----------------------------------------------------------------
	    // perform the change

	    $caughtException = false;
	    try {
		    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-cannot-be-read.json");
		}
		catch (E4xx_ConfigFileNotFound $e) {
			$caughtException = true;
		}

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($caughtException);

		// make sure the rest of the object has been left alone
	    $actualConfig = $obj->getConfig();
	    $actualName   = $obj->getName();

	    $this->assertEquals($expectedConfig, $actualConfig);
	    $this->assertEquals($expectedName, $actualName);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getName()
	 */
	public function testThrowsExceptionWhenConfigFileIsInvalidJson()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedConfig = new BaseObject();
	    $expectedName = WrappedConfig::NO_NAME;

	    $obj = new WrappedConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $caughtException = false;
	    try {
		    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-is-invalid-json.json");
		}
		catch (E4xx_ConfigFileContainsInvalidJson $e) {
			$caughtException = true;
		}

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($caughtException);

		// make sure the rest of the object has been left alone
	    $actualConfig = $obj->getConfig();
	    $actualName   = $obj->getName();

	    $this->assertEquals($expectedConfig, $actualConfig);
	    $this->assertEquals($expectedName, $actualName);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::loadConfigFromFile()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getConfig()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getName()
	 */
	public function testAcceptsEmptyConfigFiles()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedConfig = new BaseObject();
	    $expectedName = "wrapped-config-is-empty";

	    $obj = new WrappedConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-is-empty.json");

	    // ----------------------------------------------------------------
	    // test the results

		// make sure the rest of the object has been left alone
	    $actualConfig = $obj->getConfig();
	    $actualName   = $obj->getName();

	    $this->assertEquals($expectedConfig, $actualConfig);
	    $this->assertEquals($expectedName, $actualName);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::setData()
	 */
	public function testCanSetDataInsideAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $this->assertTrue(is_array($obj->getConfig()->storyplayer->roles));
	    $this->assertTrue(is_object($obj->getConfig()->storyplayer->roles[0]));

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setData("storyplayer.roles.0", getenv("HOME"));

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();
	    $this->assertTrue($config instanceof BaseObject);
	    $this->assertTrue($config->storyplayer instanceof BaseObject);
	    $this->assertEquals(getenv("HOME"), $config->storyplayer->roles[0]);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::setData()
	 */
	public function testCanSetDataInsideAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $this->assertTrue(is_object($obj->getConfig()->storyplayer->user));

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setData("storyplayer.user", getenv("HOME"));

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();
	    $this->assertTrue($config instanceof BaseObject);
	    $this->assertTrue($config->storyplayer instanceof BaseObject);
	    $this->assertEquals(getenv("HOME"), $config->storyplayer->user);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::setData()
	 */
	public function testCanSetDataByExtendingTheConfig()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $this->assertFalse(isset($obj->getConfig()->storyplayer->roles[1]));

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setData("storyplayer.dirs.cwd", getcwd());
	    $obj->setData("storyplayer.roles.1.homedir", getenv("HOME"));
	    $obj->setData("storyplayer.user.env.homedir", getenv("HOME"));

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();
	    $this->assertTrue($config instanceof BaseObject);
	    $this->assertTrue($config->storyplayer instanceof BaseObject);
	    $this->assertTrue(is_array($config->storyplayer->dirs));
	    $this->assertEquals(getcwd(), $config->storyplayer->dirs["cwd"]);

	    $this->assertTrue($config->storyplayer->roles[1] instanceof BaseObject);
	    $this->assertEquals(getenv("HOME"), $config->storyplayer->roles[1]->homedir);

	    $this->assertTrue(isset($config->storyplayer->user->env));
	    $this->assertEquals(getenv("HOME"), $config->storyplayer->user->env->homedir);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::setData()
	 */
	public function testCanReplaceTopLevelUsingSetData()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $this->assertTrue(is_object($obj->getConfig()->storyplayer->user));

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setData("", getenv("HOME"));

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();
	    $this->assertTrue(is_string($config));
	    $this->assertEquals(getenv("HOME"), $config);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::setData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathCannotBeExtended
	 */
	public function testCannotSetDataByExtendingAScalar()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setData("storyplayer.ipAddress.cwd", getcwd());
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::setData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathCannotBeExtended
	 */
	public function testCannotSetDataByOverExtendingAScalar()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setData("storyplayer.ipAddress.dirs.cwd", getcwd());
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::mergeData()
	 */
	public function testCanMergeAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $dataToMerge = new stdClass;
	    $dataToMerge->home = getenv("HOME");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->mergeData("storyplayer.user", $dataToMerge);

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();

	    // this is the data we have merged
	    $this->assertEquals(getenv("HOME"), $config->storyplayer->user->home);

	    // this is data loaded from the config file
	    $this->assertEquals("test-user", $config->storyplayer->user->name);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::mergeData()
	 */
	public function testCanMergeAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $dataToMerge = [
	    	"home" => getenv("HOME")
	    ];

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->mergeData("storyplayer.dirs", $dataToMerge);

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();

	    // this is the data we have merged
	    $this->assertTrue(is_array($config->storyplayer->dirs));
	    $this->assertEquals(getenv("HOME"), $config->storyplayer->dirs["home"]);

	    // this is data loaded from the config file
	    $this->assertEquals("test-user", $config->storyplayer->user->name);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::mergeData()
	 */
	public function testCanMergeAnArrayIntoAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $dataToMerge = [
	    	"home" => getenv("HOME")
	    ];

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->mergeData("storyplayer.user", $dataToMerge);

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();

	    // this is the data we have merged
	    $this->assertEquals(getenv("HOME"), $config->storyplayer->user->home);

	    // this is data loaded from the config file
	    $this->assertEquals("test-user", $config->storyplayer->user->name);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::mergeData()
	 */
	public function testCanMergeAScalar()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $dataToMerge = getenv("HOME");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->mergeData("storyplayer.user.home", $dataToMerge);

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();

	    // this is the data we have merged
	    $this->assertEquals(getenv("HOME"), $config->storyplayer->user->home);

	    // this is data loaded from the config file
	    $this->assertEquals("test-user", $config->storyplayer->user->name);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::mergeData()
	 */
	public function testCanMergeIntoTopLevel()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $dataToMerge = new stdClass;
	    $dataToMerge->home = getenv("HOME");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->mergeData("", $dataToMerge);

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();

	    // this is the data we have merged
	    $this->assertEquals(getenv("HOME"), $config->home);

	    // this is data loaded from the config file
	    $this->assertTrue(isset($config->storyplayer));
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::mergeData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathCannotBeExtended
	 */
	public function testCannotMergeDataByExtendingAScalar()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $dataToMerge = new stdClass;
	    $dataToMerge->home = getenv("HOME");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->mergeData("storyplayer.ipAddress", $dataToMerge);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::mergeData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathCannotBeExtended
	 */
	public function testCannotMergeDataByOverExtendingAScalar()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->mergeData("storyplayer.ipAddress.dirs.cwd", getcwd());
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getExpandedConfig()
	 */
	public function testCanExpandTwigVariables()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    // we need a config that contains a variable
	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");
	    $obj->setData("storyplayer.user.home", getenv("HOME"));
	    $obj->setData("storyplayer.test.home", "{{storyplayer.user.home}}");

	    // ----------------------------------------------------------------
	    // perform the change

	    $config = $obj->getExpandedConfig();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals(getenv("HOME"), $config->storyplayer->test->home);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getData()
	 */
	public function testCanGetDataFromAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $expected1 = "127.0.0.1";
	    $expected2 = 500;

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual1 = $obj->getData("storyplayer.ipAddress");
	    $actual2 = $obj->getData("storyplayer.user.id");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expected1, $actual1);
	    $this->assertEquals($expected2, $actual2);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getData()
	 */
	public function testCanGetDataFromAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $expected = "localhost";

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->getData("storyplayer.roles.0.cli");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getData()
	 */
	public function testCanGetTopLevelData()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    $expected = $obj->getConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->getData("");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathNotFound
	 */
	public function testGetDataThrowsExceptionWhenConfigPathNotFoundInAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->getData("storyplayer.ipAddres");

	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathNotFound
	 */
	public function testGetDataThrowsExceptionWhenConfigPathNotFoundInAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->getData("storyplayer.roles.1");

	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathNotFound
	 */
	public function testGetDataThrowsExceptionWhenConfigPathNotFoundInAScalar()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->getData("storyplayer.ipAddress.netmask");
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getObject()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getData()
	 */
	public function testCanGetAnObjectFromAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");
	    $expected = $obj->getConfig()->storyplayer;

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->getObject("storyplayer");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getObject()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PropertyNotAnObject
	 */
	public function testGetObjectThrowsExceptionWhenNonObjectRetrieved()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->getObject("storyplayer.ipAddress");
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getArray()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getData()
	 */
	public function testCanGetArrayFromAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");
	    $expected = $obj->getConfig()->storyplayer->roles;

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->getArray("storyplayer.roles");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getArray()
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getData()
	 */
	public function testCanGetArrayFromAnAssocArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test
	    //
	    // this one will need some explaining
	    //
	    // in PHP, we can declare associative arrays. however, these are
	    // converted to objects as part of our support for expanding
	    // Twig variables in the config
	    //
	    // - we use json_encode() to create the text that Twig operates on
	    // - json_encode() has to turn assoc arrays into objects
	    // - if we used serialize(), then the assoc arrays would not change
	    //   into objects
	    // - we cannot use serialize() because the format includes
	    //   run-length encoding ... and expanding Twig variables breaks
	    //   that :(
	    //
	    // as a workaround, we assume that the caller knows what they are
	    // doing, and if the dot.notation.path resolves to an object, we
	    // convert it to an array before returning it
	    //
	    // we *could* walk the non-Twigged data to check that it is an
	    // array, but in all honesty ... why go to the extra effort?

	    $obj = new WrappedConfig();
	    $config = $obj->getConfig();
	    $config->phases = [
	    	"Startup" => true,
	    	"Shutdown" => false,
	    ];
	    $expected = $config->phases;

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->getArray("phases");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::getArray()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PropertyNotAnArray
	 */
	public function testGetArrayThrowsExceptionWhenNonObjectRetrieved()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");
	    $expected = $obj->getConfig()->storyplayer;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->getArray("storyplayer.ipAddress");
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::unsetData()
	 */
	public function testCanUnsetDataFromAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->unsetData("storyplayer.ipAddress");

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();
	    $this->assertTrue(isset($config->storyplayer));
	    $this->assertFalse(isset($config->storyplayer->ipAddress));
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::unsetData()
	 */
	public function testCanUnsetDataFromAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->unsetData("storyplayer.roles.0.cli");

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();
	    $this->assertTrue(isset($config->storyplayer->roles[0]));
	    $this->assertFalse(isset($config->storyplayer->roles[0]->cli));
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::unsetData()
	 */
	public function testCanUnsetAnArrayOfData()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->unsetData("storyplayer.roles.0");

	    // ----------------------------------------------------------------
	    // test the results

	    $config = $obj->getConfig();
	    $this->assertTrue(isset($config->storyplayer->roles));
	    $this->assertFalse(isset($config->storyplayer->roles[0]));
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::unsetData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathNotFound
	 */
	public function testUnsetDataThrowsExceptionWhenConfigPathNotFoundInAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->unsetData("storyplayer.ipAddres");
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::unsetData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathNotFound
	 */
	public function testUnsetDataThrowsExceptionWhenConfigPathNotFoundDeepInAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->unsetData("storyplayer.user.does.not.exist");
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::unsetData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathNotFound
	 */
	public function testUnsetDataThrowsExceptionWhenConfigPathNotFoundInAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->unsetData("storyplayer.roles.1");
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::unsetData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathNotFound
	 */
	public function testUnsetDataThrowsExceptionWhenConfigPathNotFoundDeepInAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->unsetData("storyplayer.roles.does.not.exist");
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::unsetData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathNotFound
	 */
	public function testUnsetDataThrowsExceptionWhenConfigPathNotFoundInAScalar()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->unsetData("storyplayer.ipAddress.not-found");
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::unsetData()
	 * @expectedException DataSift\Stone\ObjectLib\E4xx_PathNotFound
	 */
	public function testUnsetDataThrowsExceptionWhenConfigPathNotFoundDeepInAScalar()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->unsetData("storyplayer.ipAddress.does.not.exist");
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::hasData()
	 */
	public function testCanCheckForDataInAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->hasData("storyplayer.ipAddress");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::hasData()
	 */
	public function testCanCheckDataInAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->hasData("storyplayer.roles.0.cli");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::hasData()
	 */
	public function testCanCheckForTopLevelData()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->hasData("");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::hasData()
	 */
	public function testHasDataReturnsFalseWhenConfigPathNotFoundInAnObject()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->hasData("storyplayer.ipAddres");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertFalse($actual);
	}


	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::hasData()
	 */
	public function testHasDataReturnsFalseWhenConfigPathNotFoundInAnArray()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->hasData("storyplayer.roles.1");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertFalse($actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::hasData()
	 */
	public function testHasDataReturnsFalseWhenConfigPathNotFoundInAScalar()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->hasData("storyplayer.ipAddress.netmask");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertFalse($actual);
	}

}