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
	 */
	public function testCanLoadConfigFile()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedConfig = new BaseObject();
	    $expectedConfig->dummy1 = true;

	    $expectedName = "wrapped-config-1";

	    $obj = new WrappedConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-1.json");

	    // ----------------------------------------------------------------
	    // test the results

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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_ConfigPathCannotBeExtended
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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_ConfigPathCannotBeExtended
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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_ConfigPathCannotBeExtended
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
	 * @covers DataSift\Storyplayer\ConfigLib\WrappedConfig::createPath()
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_ConfigPathCannotBeExtended
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

		// we need a Twig engine
	    $i = new Injectables;
	    $i->initTemplateEngineSupport();

	    // we need a config that contains a variable
	    $obj = new WrappedConfig();
	    $obj->loadConfigFromFile(__DIR__ . "/wrapped-config-2.json");
	    $obj->setData("storyplayer.user.home", getenv("HOME"));
	    $obj->setData("storyplayer.test.home", "{{storyplayer.user.home}}");

	    // ----------------------------------------------------------------
	    // perform the change

	    $config = $obj->getExpandedConfig($i);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals(getenv("HOME"), $config->storyplayer->test->home);
	}

}