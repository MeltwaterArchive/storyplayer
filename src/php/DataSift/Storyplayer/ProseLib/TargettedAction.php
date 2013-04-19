<?php

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

class TargettedAction extends TargettedBase
{
	protected $st;
	protected $action;
	protected $actionDesc;
	protected $baseElement;

	public function __construct(StoryTeller $st, $action, $actionDesc, $baseElement = null)
	{
		$this->st          = $st;
		$this->action      = $action;
		$this->actionDesc  = $actionDesc;
		$this->baseElement = $baseElement;
	}

	public function __call($methodName, $methodArgs)
	{
		$words = $this->convertMethodNameToWords($methodName);

		$targetType = $this->determineTargetType($words);

		if ($targetType != 'element') {

			// what are we searching for?
			$searchTerm = $methodArgs[0];

			$searchType = $this->determineSearchType($words);
			if ($searchType == null) {
				// we do not understand how to find the target field
				throw new E5xx_ActionFailed(__CLASS__ . '::' . $methodName, "could not work out how to find the target to action upon");
			}

			// what tag(s) do we want to narrow our search to?
			$tag = $this->determineTagType($targetType);

			if ($this->isPluralTarget($targetType)) {
				$searchMethod = 'getElements';
			}
			else {
				$searchMethod = 'getElement';
			}
			$searchMethod .= $searchType;

			// let's go find our element
			$searchObject = $this->st->fromCurrentPage();
			$searchObject->setTopElement($this->baseElement);

			$element = $searchObject->$searchMethod($searchTerm, $tag);
		}
		else {
			$element = $methodArgs[0];

			if (isset($methodArgs[1])) {
				$searchTerm = $methodArgs[1];
			}
			else {
				$searchTerm = $element->name();
			}
		}

		// now that we have our element, let's apply the action to it
		$action = $this->action;
		$return = $action($this->st, $element, $searchTerm, $methodName);

		// all done
		return $return;
	}
}