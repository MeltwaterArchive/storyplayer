<?php

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

class TargettedExpects
{
	protected $st;
	protected $searchFunction;
	protected $searchTerm;
	protected $element;
	protected $elementType;

	public function __construct(StoryTeller $st, callable $searchFunction, $searchTerm, $elemendDesc)
	{
		$this->st             = $st;
		$this->searchFunction = $searchFunction;
		$this->searchTerm     = $searchTerm;
		$this->elementDesc    = $elementDesc;
	}

	public function isBlank()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("[ {$this->elementDesc} '{$this->searchTerm}' must be blank ]");

		// get the element
		$element = $this->getElement();

		// test it
		if (strlen($element->attribute("value")) > 0) {
			throw new E5xx_ExpectFailed(__METHOD__, $this->searchTerm . ' is blank', $this->searchTerm . ' is not blank');
		}

		// all done
		$log->endAction();
		return true;
	}

	public function isNotBlank()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("[ {$this->elementDesc} '{$this->searchTerm}' must not be blank ]");

		// get the element
		$element = $this->getElement();

		// test it
		if (strlen($element->attribute("value")) > 0) {
			$log->endAction();
			return true;
		}

		throw new E5xx_ExpectFailed(__METHOD__, $this->searchTerm . ' is not blank', $this->searchTerm . ' is blank');
	}

	public function isChecked()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("[ {$this->elementDesc} '{$this->searchTerm}' must be checked ]");

		// get the element
		$element = $this->getElement();

		// test it
		if ($element->attribute("checked")) {
			$log->endAction();
			return true;
		}

		throw new E5xx_ExpectFailed(__METHOD__, $this->searchTerm . ' checked', $this->searchTerm . ' not checked');
	}

	public function isNotChecked()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("[ {$this->elementDesc} '{$this->searchTerm}' must not be checked ]");

		// get the element
		$element = $this->getElement();

		// test it
		if ($element->attribute("checked")) {
			throw new E5xx_ExpectFailed(__METHOD__, $this->searchTerm . ' not checked', $this->searchTerm . ' checked');
		}

		// all done
		$log->endAction();
		return true;
	}

	protected function getElement()
	{
		$callable = $this->searchFunction;

		$log = $this->st->startAction("[ Find element on page with label, id or name '{$this->searchTerm}'");
		try {
			$element = $callable();
			$log->endAction();

			return $element;
		}
		catch (Exception $e) {
			throw new E5xx_ExpectFailed(__METHOD__, $this->searchTerm . ' exists', 'does not exist');
		}
	}
}