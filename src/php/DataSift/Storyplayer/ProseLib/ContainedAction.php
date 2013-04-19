<?php

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class ContainedAction
{
	protected $st;
	protected $topElement;

	public function __construct(StoryTeller $st, $topElement)
	{
		$this->st = $st;
		$this->topElement = $topElement;
	}

	// ==================================================================
	//
	// Input actions go here
	//
	// ------------------------------------------------------------------

	public function check()
	{
		$action = function($st, $element, $elementName, $elementDesc) {
			$log = $st->startAction("check $elementDesc '$elementName'");

			// does the element need clicking to check it?
			if (!$element->selected()) {
				// click the element to check it
				$element->click();
				$log->endAction();
			}
			else {
				$log->endAction("was already checked");
			}
		};

		return new TargettedAction(
			$this->st,
			$action,
			"check",
			$this->topElement
		);
	}

	public function clear()
	{
		$action = function($st, $element, $elementName, $elementDesc) {

			$log = $st->startAction("clear $elementDesc '$elementName'");

			$tag = $element->name();

			switch ($tag) {
				case "input":
				case "textarea":
					$element->clear();
					break;
			}

			$log->endAction();
		};

		return new TargettedAction(
			$this->st,
			$action,
			"clear",
			$this->topElement
		);
	}

	public function click()
	{
		$action = function($st, $element, $elementName, $elementDesc) {
			$log = $st->startAction("click $elementDesc '$elementName'");
			$element->click();
			$log->endAction();
		};

		return new TargettedAction(
			$this->st,
			$action,
			"click",
			$this->topElement
		);
	}

	public function select($label)
	{
		$action = function ($st, $element, $elementName, $elementDesc) use ($label) {

			// what are we doing?
			$log = $st->startAction("choose option '$label' from $elementDesc '$elementName'");

			// get the option to select
			$option = $element->element('xpath', 'option[normalize-space(text()) = "' . $label . '" ]');

			// select it
			$option->click();

			// all done
			$log->endAction();
		};

		return new TargettedAction(
			$this->st,
			$action,
			"select",
			$this->topElement
		);
	}

	public function type($text)
	{
		$action = function($st, $element, $elementName, $elementDesc) use ($text) {

			// what are we doing?
			$log = $st->startAction("type '$text' into $elementDesc '$elementName'");

			// type the text
			$element->type($text);

			// all done
			$log->endAction();
		};

		return new TargettedAction(
			$this->st,
			$action,
			"type",
			$this->topElement
		);
	}

	public function typeSpecial($text)
	{
		$action = function($st, $element, $elementName, $elementDesc) use ($text) {

			// what are we doing?
			$log = $st->startAction("type special character '$text' into $elementDesc '$elementName'");
			$element->typeSpecial($text);
			$log->endAction();
		};

		return new TargettedAction(
			$this->st,
			$action,
			"typeSpecial",
			$this->topElement
		);
	}
}