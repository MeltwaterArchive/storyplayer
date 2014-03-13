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

use DataSift\Storyplayer\Cli\Injectables;
use DataSift\Stone\LogLib\Log;

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
class ActionLogItem
{
	private $nestLevel;
	private $user;
	private $text;
	private $startTime;
	private $endTime;
	private $nestedActions = array();
	private $steps = array();
	private $logLevel = null;

	public function __construct($injectables, $nestLevel, $logLevel = null)
	{
		$this->nestLevel   = $nestLevel;
		$this->logLevel    = $logLevel;
		$this->injectables = $injectables;
		$this->output      = $injectables->output;
	}

	public function startAction($user, $text)
	{
		// when did this happen?
		$this->start = microtime(true);

		// what is about to happen?
		$this->text = $text;

		// who are we pretending to be?
		$this->user = clone $user;

		// set the log level
		$this->setLogLevel($text);

		// write to screen
		$this->writeToLog($this->text);

		// all done
		return $this;
	}

	public function endAction($resultText = null)
	{
		// remember when the action completed
		$this->endTime = microtime(true);

		// do we any output to log?
		if (!empty($resultText)) {
			$this->writeToLog('... ' . $resultText, true);
		}
	}

	public function newNestedAction()
	{
		$openItem = $this->getLastOpenAction();

		if (!is_object($openItem) || $openItem->isComplete()) {
			// we have no open actions - start a new one
			$openItem = new ActionLogItem($this->injectables, $this->nestLevel + 1, $this->getLogLevel());
			$this->nestedActions[] = $openItem;
		}
		else {
			// we have an open action - nest something inside
			$openItem = $openItem->newNestedAction();
		}

		// all done
		return $openItem;
	}

	public function getLastOpenAction()
	{
		// is this action now completed?
		if ($this->isComplete()) {
			// nothing to see, move along
			return null;
		}

		// do we have any actions at all?
		if (count($this->nestedActions) == 0)
		{
			return null;
		}

		// is our last nested action currently open?
		$endAction = end($this->nestedActions);
		if ($endAction->isComplete()) {
			// no it is not
			//
			// *we* are the currently open action
			return null;
		}

		// if we get here, then we have an open, nested action
		//
		// ask *it* to get the last action
		$nestedOpenAction = $endAction->getLastOpenAction();

		// is there a further nested action, or not?
		if (is_object($nestedOpenAction)) {
			// yes there is - return it
			return $nestedOpenAction;
		}
		else {
			// no there isn't
			return $endAction;
		}
	}

	public function getFirstOpenAction()
	{
		// is this action now completed?
		if ($this->isComplete()) {
			// nothing to see, move along
			return null;
		}

		// do we have any actions at all?
		if (count($this->nestedActions) == 0)
		{
			return null;
		}

		// is our last nested action currently open?
		$endAction = end($this->nestedActions);
		if ($endAction->isComplete()) {
			// no it is not
			//
			// *we* are the currently open action
			return null;
		}

		// yes it is
		return $endAction;
	}

	public function closeAllOpenActions()
	{
		$openItem = $this->getFirstOpenAction();
		if (is_object($openItem)) {
			$openItem->closeAllOpenActions();
			$openItem->endAction();
		}
	}

	public function closeAllOpenSubActions()
	{
		$openItem = $this->getFirstOpenAction();
		if (is_object($openItem)) {
			$openItem->closeAllOpenActions();
		}
	}

	public function isComplete()
	{
		if (!empty($this->endTime)) {
			return true;
		}

		return false;
	}

	public function isOpen()
	{
		if (empty($this->endTime)) {
			return true;
		}

		return false;
	}

	public function addStep($text, $callable)
	{
		// create a log item for this step
		$action = new ActionLogItem($this->injectables, $this->nestLevel + 1, $this->getLogLevel());
		$action->startAction($this->user, $text);

		// add it to our collection
		$this->nestedActions[] = $action;

		// call the user's callback
		$return = $callable($action);

		// was there a return value?
		if ($return === true) {
			$this->text .= ' ... YES';
		}
		else if ($return === false) {
			$this->text .= ' ... NO';
		}

		// mark this action as complete
		$action->endAction();

		// all done
		return $return;
	}

	protected function getLogLevel()
	{
		if (isset($this->logLevel))
		{
			return $this->logLevel;
		}

		// no - default is LOG_INFO
		$logLevel = Log::LOG_INFO;
		return $logLevel;
	}

	protected function setLogLevel($text)
	{
		// by default, no 'bookend'
		//
		// bookend is the character used to close the log message
		$bookend = null;

		// use the nesting to set the log level
		switch ($this->nestLevel)
		{
			case 1:
				$this->logLevel = Log::LOG_INFO;
				break;

			case 2:
				$this->logLevel = Log::LOG_DEBUG;
				break;

			default:
				$this->logLevel = Log::LOG_TRACE;
				break;
		}

		// now, we let the message override the nesting
		switch ($text[0])
		{
			case '*':
				$this->text = substr($text, 2);
				$this->logLevel = Log::LOG_WARNING;
				$bookend  = "*";
				break;

			// support for other overrides goes here

			// default behaviour
			default:
				$this->text = $text;
		}

		// strip trailing text if necessary
		if ($bookend !== null) {
			if (substr($this->text, -2, 2) == ' ' . $bookend) {
				$this->text = substr($this->text, 0, -2);
			}
		}
	}

	protected function getMessageBookends($logLevel)
	{
		switch ($logLevel)
		{
			case Log::LOG_WARNING:
				return array("* ", " *");

			default:
				return array("", "");
		}
	}

	protected function writeToLog($text)
	{
		$logLevel = $this->getLogLevel();
		list($startText, $endText) = $this->getMessageBookends($logLevel);

		$this->output->logStoryActivity($logLevel, str_repeat("  ", $this->nestLevel - 1) . $startText . $text . $endText);
	}
}