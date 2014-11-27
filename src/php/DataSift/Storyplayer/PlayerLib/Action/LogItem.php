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

use DataSift\Storyplayer\Injectables;

/**
 * Represents a single activity item in the log
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Action_LogItem
{
	private $nestLevel;
	private $text;
	private $startTime;
	private $endTime;
	private $nestedAction = null;
	private $steps = array();
	private $injectables;
	private $output;

	/**
	 * @param integer $nestLevel
	 */
	public function __construct(Injectables $injectables, $nestLevel)
	{
		$this->nestLevel     = $nestLevel;
		$this->injectables   = $injectables;
		$this->output        = $injectables->output;
		$this->dataFormatter = $injectables->dataFormatter;
	}

	/**
	 *
	 * @param  mixed $message
	 *         the message to log. if it isn't a string, we'll convert it
	 *         and apply the user's -V preference before logging
	 * @param  array $codeLine
	 *         metadata about the line of code that we're logging about
	 * @return Action_LogItem
	 *         return $this for fluent interfaces
	 */
	public function startAction($message, $codeLine = null)
	{
		// when did this happen?
		$this->startTime = microtime(true);
		$this->endTime   = null;

		// only log the top-level item
		if ($this->nestLevel > 1) {
			$codeLine = null;
		}

		// convert to string if required
		//
		// if the user hasn't used -V from the command-line, then this
		// will also truncate the output
		if (is_array($message)) {
			$text = $this->dataFormatter->convertMessageArray($message);
		}
		else {
			$text = $this->dataFormatter->convertData($message);
		}

		// write to screen
		$this->writeToLog($text, $codeLine);

		// all done
		return $this;
	}

	/**
	 * @param mixed $message
	 */
	public function endAction($message = null)
	{
		// close any open sub-actions
		$this->closeAllOpenSubActions();

		// remember when the action completed
		$this->endTime = microtime(true);

		// do we any output to log?
		if ($message == null || empty($message)) {
			return;
		}
		// convert to string if required
		//
		// if the user hasn't used -V from the command-line, then this
		// will also truncate the output
		$text = $this->dataFormatter->convertData($message);

		// log the result
		$this->writeToLog('... ' . $text);
	}

	/**
	 * @return Action_LogItem
	 */
	public function newNestedAction()
	{
		// do we have a nested action open?
		if (!isset($this->nestedAction) || $this->nestedAction->getIsComplete()) {
			// we have no open actions - start a new one
			$openItem = $this->nestedAction = new Action_LogItem($this->injectables, $this->nestLevel + 1);
		}
		else {
			// we have an open action - nest something inside
			$openItem = $this->nestedAction->newNestedAction();
		}

		// all done
		return $openItem;
	}

	/**
	 *
	 * @return void
	 */
	public function closeAllOpenSubActions()
	{
		if (!isset($this->nestedAction)) {
			return;
		}

		$this->nestedAction->endAction();
		unset($this->nestedAction);
	}

	/**
	 *
	 * @return boolean
	 *         TRUE if the action is complete
	 *         FALSE if the action is currently in progress or has never
	 *               been started
	 */
	public function getIsComplete()
	{
		if (isset($this->endTime) && $this->endTime !== null) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @return boolean
	 *         TRUE if the action is currently in progress
	 *         FALSE if the action is complete or has never been started
	 */
	public function getIsOpen()
	{
		if (isset($this->startTime) && $this->startTime !== null && !isset($this->endTime)) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @param string $text
	 *        the message to write
	 * @param callback $callable
	 *        the function / lambda to call
	 */
	public function addStep($text, $callable)
	{
		// make sure any nested action has completed
		$this->closeAllOpenSubActions();

		// create a log item for this step
		$action = $this->newNestedAction();
		$action->startAction($text);

		// call the callback
		$return = $callable($action);

		// mark this action as complete
		$action->endAction();
		unset($this->nestedAction);

		// all done
		return $return;
	}

	public function startStep($text)
	{
		// make sure any nested action has completed
		$this->closeAllOpenSubActions();

		// create a log item for this step
		$action = new Action_LogItem($this->injectables, $this->nestLevel + 1);
		$action->startAction($text);

		// add the action to our collection
		$this->nestedAction = $action;
	}

	public function endStep()
	{
		$this->closeAllOpenSubActions();
	}

	public function captureOutput($text)
	{
		// trick the logger into indenting the output one more
		$this->nestLevel++;

		// NOTE: output captured from sub-processes is NEVER subjected
		// to truncation if -V is not used from the command-line
		$this->output->logPhaseSubprocessOutput($text);

		// restore our original output nesting level
		$this->nestLevel--;
	}

	protected function writeToLog($text, $codeLine = null)
	{
		$this->output->logPhaseActivity(str_repeat("  ", $this->nestLevel - 1) . $text, $codeLine);
	}

	public function getNestLevel()
	{
		return $this->nestLevel;
	}

	// ==================================================================
	//
	// Helpers for testing etc go here
	//
	// ------------------------------------------------------------------

	public function getOpenAction()
	{
		// do we have an active nested action?
		if (isset($this->nestedAction) && $this->nestedAction->getIsOpen()) {
			// ask it to figure out what to return
			return $this->nestedAction->getOpenAction();
		}

		// are we the open action?
		if ($this->getIsOpen()) {
			return $this;
		}

		// if we get here, then there is no current open action
		return null;
	}

	public function getStartTime()
	{
		if (!isset($this->startTime)) {
			return null;
		}

		return $this->startTime;
	}

	public function getEndTime()
	{
		if (!isset($this->endTime)) {
			return null;
		}

		return $this->endTime;
	}
}