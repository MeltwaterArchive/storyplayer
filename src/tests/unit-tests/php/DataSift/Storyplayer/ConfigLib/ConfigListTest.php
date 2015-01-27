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

class ConfigListTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct()
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\WrappedConfig", __DIR__ . '/ConfigListTestData1');

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof ConfigList);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::getEntries
	 */
	public function testStartsWithEmptyConfig()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expected = [];

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\WrappedConfig", __DIR__ . '/ConfigListTestData1');

	    // ----------------------------------------------------------------
	    // test the results

	    $actual = $obj->getEntries();
	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::getConfigType
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::setConfigType
	 */
	public function testCanGetListedConfigType()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expected = "DataSift\Storyplayer\ConfigLib\WrappedConfig";

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new ConfigList($expected, __DIR__ . '/ConfigListTestData1');

	    // ----------------------------------------------------------------
	    // test the results

	    $actual = $obj->getConfigType();
	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::getConfigType
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::setConfigType
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::getWrappedConfigClassname
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::newWrappedConfigObject
	 */
	public function testCanGetNewListedConfigObject()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expectedClassname = "DataSift\Storyplayer\ConfigLib\WrappedConfig";

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new ConfigList($expectedClassname, __DIR__ . '/ConfigListTestData1');

	    // ----------------------------------------------------------------
	    // test the results

	    $actualClassname = $obj->getWrappedConfigClassname();
	    $this->assertEquals($expectedClassname, $actualClassname);

	    $actualConfigObj = $obj->newWrappedConfigObject();
	    $this->assertTrue($actualConfigObj instanceof WrappedConfig);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::setConfigType
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_NoSuchConfigClass
	 */
	public function testConfigTypeMustBeAConfigLibClass()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new ConfigList("NonExistentConfig", __DIR__ . '/ConfigListTestData1');
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::getSearchFolder
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::setSearchFolder
	 */
	public function testStartsWithGivenSearchFolder()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		$expected = __DIR__ . '/ConfigListTestData1';

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\StoryplayerConfig", $expected);

	    // ----------------------------------------------------------------
	    // test the results

	    $actual = $obj->getSearchFolder();
	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::findConfigs
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::findConfigFilenames
	 */
	public function testCanLoadConfigFilesFromDisk()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		$expectedKeys = [ 'config-1', 'config-2' ];
	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\StoryplayerConfig", __DIR__ . '/ConfigListTestData1');

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->findConfigs();

	    // ----------------------------------------------------------------
	    // test the results

	    $configs = $obj->getEntries();

	    $this->assertTrue(is_array($configs));
	    $this->assertTrue(isset($configs['config-1']));
	    $this->assertTrue($configs['config-1'] instanceof StoryplayerConfig);
	    $this->assertTrue(isset($configs['config-2']));
	    $this->assertTrue($configs['config-2'] instanceof StoryplayerConfig);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::findConfigs
	 */
	public function testConfigNamesAreSorted()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		$expectedKeys = [ 'config-1', 'config-2' ];
	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\StoryplayerConfig", __DIR__ . '/ConfigListTestData1');

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->findConfigs();

	    // ----------------------------------------------------------------
	    // test the results

	    $configs = $obj->getEntries();

	    $this->assertEquals($expectedKeys, array_keys($configs));
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::findConfigs
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::findConfigFilenames
	 */
	public function testIgnoresEmptyFolders()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\StoryplayerConfig", __DIR__ . '/ConfigListEmptyFolder');

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->findConfigs();

	    // ----------------------------------------------------------------
	    // test the results

	    $configs = $obj->getEntries();

	    $this->assertTrue(is_array($configs));
	    $this->assertEquals(0, count($configs));
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::findConfigs
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::findConfigFilenames
	 */
	public function testIgnoresMissingFolders()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\StoryplayerConfig", __DIR__ . '/ConfigListFolderDoesNotExist');

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->findConfigs();

	    // ----------------------------------------------------------------
	    // test the results

	    $configs = $obj->getEntries();

	    $this->assertTrue(is_array($configs));
	    $this->assertEquals(0, count($configs));
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::addEntry
	 */
	public function testCanAddConfigManually()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\StoryplayerConfig", __DIR__ . '/ConfigListTestData1');
	    $obj->findConfigs();

	    $expectedName = 'injected-1';
	    $expectedConfig = new StoryplayerConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->addEntry($expectedName, $expectedConfig);

	    // ----------------------------------------------------------------
	    // test the results

	    $configs = $obj->getEntries();

	    $this->assertTrue(isset($configs[$expectedName]));
	    $this->assertEquals($expectedConfig, $configs[$expectedName]);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::addEntry
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_IncompatibleConfigClass
	 */
	public function testManuallyAddedConfigsMustBeCompatibleType()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\StoryplayerConfig", __DIR__ . '/ConfigListTestData1');
	    $obj->findConfigs();

	    $expectedName = 'injected-1';
	    $expectedConfig = new WrappedConfig();

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->addEntry($expectedName, $expectedConfig);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::hasEntry
	 */
	public function testCanCheckForConfigEntry()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\StoryplayerConfig", __DIR__ . '/ConfigListTestData1');
	    $obj->findConfigs();

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual1 = $obj->hasEntry('config-1');
	    $actual2 = $obj->hasEntry('config-does-not-exist');

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($actual1);
	    $this->assertFalse($actual2);
	}


	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::getEntry
	 */
	public function testCanGetSingleConfigEntry()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\StoryplayerConfig", __DIR__ . '/ConfigListTestData1');
	    $obj->findConfigs();

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->getEntry('config-1');

	    // ----------------------------------------------------------------
	    // test the results

	    $configs = $obj->getEntries();

	    $this->assertSame($configs['config-1'], $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\ConfigList::getEntry
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_NoSuchConfigEntry
	 */
	public function testThrowsExceptionWhenSingleConfigEntryNotFound()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new ConfigList("DataSift\Storyplayer\ConfigLib\StoryplayerConfig", __DIR__ . '/ConfigListTestData1');
	    $obj->findConfigs();

	    // ----------------------------------------------------------------
	    // perform the change

	    $actual = $obj->getEntry('config-does-not-exist');
	}

}