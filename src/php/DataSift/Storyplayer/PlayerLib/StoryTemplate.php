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
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Storyplayer\StoryLib\Story;

/**
 * Base class for reusable test environment setup/teardown instructions
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
abstract class StoryTemplate
{
	protected $story;
	protected $vmParams;

	public function __construct($params = array())
	{
		$this->params = $params;
	}

	public function setStory(Story $story)
	{
		$this->story = $story;
	}

	public function getParams($additionalParams = array())
	{
		return $this->params + $additionalParams;
	}

	abstract public function getName();

	/**
	 * Helper methods to keep the Templates API in line with the phases
	 */

	public function hasTestEnvironmentSetup()
	{
		return method_exists($this, 'testEnvironmentSetup');
	}

	public function hasTestSetup()
	{
		return method_exists($this, 'testSetup');
	}

	public function hasPerPhaseSetup()
	{
		return method_exists($this, 'perPhaseSetup');
	}

	public function hasPerPhaseTeardown()
	{
		return method_exists($this, 'perPhaseTeardown');
	}

	public function hasHints()
	{
		return method_exists($this, 'hints');
	}

	public function hasPreTestPrediction()
	{
		return method_exists($this, 'preTestPrediction');
	}

	public function hasPreTestInspection()
	{
		return method_exists($this, 'preTestInspection');
	}

	public function hasAction()
	{
		return method_exists($this, 'action');
	}

	public function hasPostTestInspection()
	{
		return method_exists($this, 'postTestInspection');
	}

	public function hasTestTeardown()
	{
		return method_exists($this, 'testTeardown');
	}

	public function hasTestEnvironmentTeardown()
	{
		return method_exists($this, 'testEnvironmentTeardown');
	}



	public function getTestEnvironmentSetup()
	{
		return array($this, 'testEnvironmentSetup');
	}

	public function getTestSetup()
	{
		return array($this, 'testSetup');
	}

	public function getPerPhaseSetup()
	{
		return array($this, 'perPhaseSetup');
	}

	public function getPerPhaseTeardown()
	{
		return array($this, 'perPhaseTeardown');
	}

	public function getHints()
	{
		return array($this, 'hints');
	}

	public function getPreTestPrediction()
	{
		return array($this, 'preTestPrediction');
	}

	public function getPreTestInspection()
	{
		return array($this, 'preTestInspection');
	}

	public function getAction()
	{
		return array($this, 'action');
	}

	public function getPostTestInspection()
	{
		return array($this, 'postTestInspection');
	}

	public function getTestTeardown()
	{
		return array($this, 'testTeardown');
	}

	public function getTestEnvironmentTeardown()
	{
		return array($this, 'testEnvironmentTeardown');
	}

}
