<?php

namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Stone\LogLib\Log;

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

	public function __construct($nestLevel, $logLevel = null)
	{
		$this->nestLevel = $nestLevel;
		$this->logLevel  = $logLevel;
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
			$openItem = new ActionLogItem($this->nestLevel + 1, $this->getLogLevel());
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
		$action = new ActionLogItem($this->nestLevel + 1, $this->getLogLevel());
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
		switch ($text[0])
		{
			case '[':
				$this->text = substr($text, 2);
				$logLevel = Log::LOG_DEBUG;
				$bookend  = "]";
				break;

			case '(':
				$this->text = substr($text, 2);
				$logLevel = Log::LOG_TRACE;
				$bookend  = ")";
				break;

			case '*':
				$this->text = substr($text, 2);
				$logLevel = Log::LOG_WARNING;
				$bookend  = "*";
				break;

			default:
				$this->text = $text;
				$logLevel = Log::LOG_INFO;
				$bookend  = null;
		}

		// strip trailing text if necessary
		if ($bookend !== null) {
			if (substr($this->text, -2, 2) == ' ' . $bookend) {
				$this->text = substr($this->text, 0, -2);
			}
		}

		// have we already inherited?
		if (!isset($this->logLevel) || $this->logLevel < $logLevel) {
			$this->logLevel = $logLevel;
		}
	}

	protected function getMessageBookends($logLevel)
	{
		switch ($logLevel)
		{
			case Log::LOG_DEBUG:
				return array("[ ", " ]");

			case Log::LOG_TRACE:
				return array("( ", " )");

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

		Log::write($logLevel, str_repeat("  ", $this->nestLevel - 1) . $startText . $text . $endText);
	}
}