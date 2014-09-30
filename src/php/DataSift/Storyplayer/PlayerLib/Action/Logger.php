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

/**
 * The top level of the logging tree
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Action_Logger
{
	protected $action = null;
	protected $injectables;

	/**
	 * @param \DataSift\Storyplayer\Injectables $injectables
	 */
	public function __construct($injectables)
	{
		$this->injectables = $injectables;
	}

	/**
	 *
	 * @param  mixed $message
	 *         the message to write to the log
	 * @param  array $codeLine
	 *         details about the line of code we are currently executing
	 * @return Action_LogItem
	 *         the object that tracks this log entry
	 */
	public function startAction($message, $codeLine = null)
	{
		// do we have an open action?
		if (!$this->action || !$this->action->getIsOpen())
		{
			$openItem = $this->action = new Action_LogItem($this->injectables, 1);
		}
		else {
			// this is a new nested item
			$openItem = $this->action->newNestedAction();
		}

		return $openItem->startAction($message, $codeLine);
	}

	public function closeAllOpenActions()
	{
		// do we have any empty log items?
		if (!$this->action)
		{
			return;
		}

		// close the action
		if ($this->action->getIsOpen()) {
			$this->action->endAction();
		}

		// forget the action
		$this->action = null;
	}

	// ==================================================================
	//
	// Helper methods for testing etc go here
	//
	// ------------------------------------------------------------------

	public function getOpenAction()
	{
		if (!$this->action || $this->action->getIsComplete()) {
			return null;
		}

		return $this->action;
	}
}