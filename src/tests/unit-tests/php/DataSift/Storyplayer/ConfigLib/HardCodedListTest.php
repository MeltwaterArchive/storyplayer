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

class HardCodedListTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::__construct()
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new HardCodedList('DataSift\Storyplayer\ConfigLib\WrappedConfig');

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof HardCodedList);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::__construct
	 */
	public function testStartsWithEmptyList()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expected = [];

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new HardCodedList('DataSift\Storyplayer\ConfigLib\WrappedConfig');

	    // ----------------------------------------------------------------
	    // test the results

	    $actual = $obj->getConfigs();
	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::setConfigType
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::getConfigType
	 */
	public function testRemembersConfigType()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expected = 'DataSift\Storyplayer\ConfigLib\WrappedConfig';

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new HardCodedList($expected);

	    // ----------------------------------------------------------------
	    // test the results

	    $actual = $obj->getConfigType();
	    $this->assertEquals($expected, $actual);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::setConfigType
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::getConfigType
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_NoSuchConfigClass
	 */
	public function testConfigTypeMustBeAValidClass()
	{
		// ----------------------------------------------------------------
		// setup the test

		$classname = 'DataSift\Storyplayer\ConfigLib\DoesNotExistConfig';

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new HardCodedList($classname);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::newConfig
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::addConfig
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::getConfigs
	 */
	public function testCanCreateNewConfig()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expectedName = 'test-config';
	    $obj = new HardCodedList('DataSift\Storyplayer\ConfigLib\WrappedConfig');

	    // ----------------------------------------------------------------
	    // perform the change

	    $newConfig = $obj->newConfig($expectedName);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($newConfig instanceof WrappedConfig);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::newConfig
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::addConfig
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::getConfigs
	 */
	public function testNewConfigsAreAutoAddedToTheList()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expectedName = 'test-config';
	    $obj = new HardCodedList('DataSift\Storyplayer\ConfigLib\WrappedConfig');

	    // ----------------------------------------------------------------
	    // perform the change

	    $newConfig = $obj->newConfig($expectedName);

	    // ----------------------------------------------------------------
	    // test the results

	    $configs = $obj->getConfigs();
	    $this->assertTrue(isset($configs[$expectedName]));
	    $this->assertSame($configs[$expectedName], $newConfig);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::addConfig
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::getConfigs
	 */
	public function testCanAddNewConfig()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expectedName = 'test-config';
	    $obj = new HardCodedList('DataSift\Storyplayer\ConfigLib\WrappedConfig');

	    // ----------------------------------------------------------------
	    // perform the change

	    $config = new WrappedConfig();
	    $config->setName($expectedName);
	    $obj->addConfig($config);

	    // ----------------------------------------------------------------
	    // test the results

	    $configs = $obj->getConfigs();
	    $this->assertTrue(isset($configs[$expectedName]));
	    $this->assertSame($configs[$expectedName], $config);
	}

	/**
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::__construct
	 * @covers DataSift\Storyplayer\ConfigLib\HardCodedList::addConfig
	 * @expectedException DataSift\Storyplayer\ConfigLib\E4xx_IncompatibleConfigClass
	 */
	public function testCanAddedConfigsMustBeCompatibleType()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expectedName = 'test-config';
	    $obj = new HardCodedList('DataSift\Storyplayer\ConfigLib\StoryplayerConfig');

	    // ----------------------------------------------------------------
	    // perform the change

	    $config = new WrappedConfig();
	    $config->setName($expectedName);
	    $obj->addConfig($config);
	}
}