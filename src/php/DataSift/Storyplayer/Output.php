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
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer;

use DataSift\Storyplayer\OutputLib\OutputPlugin;
use DataSift\Storyplayer\OutputLib\DefaultOutputPlugin;

/**
 * all output goes through here
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Output
{
	protected $plugins = array();

	public function __construct()
	{
		// we need a default output for the console
		$this->plugins['console'] = new DefaultOutputPlugin();
	}

	/**
	 * set the plugin for a named output slot
	 *
	 * @param string       $slot
	 *        the name of the slot to use for this plugin
	 * @param OutputPlugin $plugin
	 *        the plugin to use in the slot
	 */
	public function addPlugin($slot, OutputPlugin $plugin)
	{
		$this->plugins[$slot] = $plugin;
	}

	/**
	 * called when storyplayer starts
	 *
	 * @return void
	 */
	public function startStoryplayer()
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->startStoryplayer();
		}
	}

	/**
	 * called when Storyplayer exits
	 *
	 * @return void
	 */
	public function endStoryplayer()
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->endStoryplayer();
		}
	}

	/**
	 * called when a new story starts
	 *
	 * a single copy of Storyplayer may execute multiple tests
	 *
	 * @return void
	 */
	public function startStory()
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->startStory();
		}
	}

	/**
	 * called when a story finishes
	 *
	 * @return void
	 */
	public function endStory()
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->endStory();
		}
	}

	/**
	 * called when a story starts a new phase
	 *
	 * @return void
	 */
	public function startPhase()
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->startPhase();
		}
	}

	/**
	 * called when a story ends a phase
	 *
	 * @return void
	 */
	public function endPhase()
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->endPhase();
		}
	}

	/**
	 * called when a story logs an action
	 *
	 * @return void
	 */
	public function logStoryActivity()
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->logActivity();
		}
	}

	/**
	 * called when a story logs an error
	 *
	 * @return void
	 */
	public function logStoryError()
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->logError();
		}
	}

	/**
	 * called when the outer CLI shell encounters a fatal error
	 *
	 * @param  string $msg
	 *         the error message to show the user
	 *
	 * @return void
	 */
	public function logCliError($msg)
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->logCliError($msg);
		}
	}

	/**
	 * called when the outer CLI shell needs to publish a warning
	 *
	 * @param  string $msg
	 *         the warning message to show the user
	 *
	 * @return void
	 */
	public function logCliWarning($msg)
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->logCliWarning($msg);
		}
	}

	public function logCliInfo($msg)
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->logCliInfo($msg);
		}
	}
}
