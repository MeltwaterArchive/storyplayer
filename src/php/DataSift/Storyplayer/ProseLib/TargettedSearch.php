<?php

namespace DataSift\Storyplayer\ProseLib;

use Exception;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

class TargettedSearch extends TargettedBase
{
	protected $st;
	protected $pageContext;
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
		// turn the method name into an array of words
		$words = $this->convertMethodNameToWords($methodName);

		$targetType = $this->determineTargetType($words);
		$searchType = $this->determineSearchType($words);

		if ($searchType == null) {
			// we do not understand how to find the target field
			throw new E5xx_ActionFailed(__CLASS__ . '::' . $methodName, "could not work out how to find the target to action upon");
		}

		$tag = $this->determineTagType($targetType);

		// what are we searching for?
		$searchTerm = $methodArgs[0];

		if ($this->isPluralTarget($targetType)) {
			$searchMethod = 'getElements';
		}
		else {
			$searchMethod = 'getElement';
		}
		$searchMethod .= $searchType;

		// let's go find our element
		try {
			$searchObject = $this->st->fromCurrentPage();
			if ($this->baseElement !== null) {
				$searchObject->setTopElement($this->baseElement);
			}
			$element = $searchObject->$searchMethod($searchTerm, $tag);
		} catch (Exception $e) {
			$element = null;
		}

		// now that we have our element, let's apply the action to it
		$action = $this->action;
		$return = $action($this->st, $element, $searchTerm, $methodName);

		// all done
		return $return;
	}
}