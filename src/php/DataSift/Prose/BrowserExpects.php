<?php

namespace DataSift\Storyplayer\Prose;

use Exception;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Storyplayer\ProseLib\TargettedExpects;
use DataSift\Storyplayer\ProseLib\TargettedSearch;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class BrowserExpects extends ProseActions
{
	public function has()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {

			$log = $st->startAction("[ $elementDesc '$elementName' must exist");

			if (is_object($element)) {
				$log->endAction();
				return true;
			}

			throw new E5xx_ExpectFailed(__METHOD__, 'element to exist', 'element does not exist');
		};

		return new TargettedSearch(
			$this->st,
			$action,
			"has",
			$this->getTopElement()
		);
	}

	public function field($searchTerm)
	{
		// shorthand
		$st = $this->st;

		// how do we find the element to test?
		$action = function() use ($st, $searchTerm) {
			$element = $st->fromCurrentPage()->getElementByLabelIdOrName($searchTerm);
		};

		return new TargettedExpects($st, $action, $searchTerm, 'field');
	}

	public function title($title)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("page title must be {$title}");

		// get the browser title
		$browserTitle = $this->st->fromCurrentPage()->getTitle();

		if ($title != $browserTitle) {
			throw new E5xx_ExpectFailed('BrowserExpects::title', $title, $browserTitle);
		}

		// all done
		$log->endAction();
	}

	public function titles($titles)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$titlesString = implode('; or ', $titles);
		$log = $st->startAction("page title must be one of: {$titlesString}");

		// get the browser title
		$browserTitle = $this->st->fromCurrentPage()->getTitle();

		if (!in_array($browserTitle, $titles)) {
			throw new E5xx_ExpectFailed(__METHOD__, $titlesString, $browserTitle);
		}

		// all done
		$log->endAction();
	}

	public function hasHeading($text, $maxLevel = 2)
	{
		// shorthand
		$st         = $this->st;
		$topElement = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("page must have a heading or sub-heading with text '$text'");

		// build up the xpath to use in the query
		for($i = 1; $i <= $maxLevel; $i++) {
			$xpathList[] = "descendant::h" . $i . '[normalize-space(text()) = "' . $text . '"]';
		}
		$xpath = implode(" | ", $xpathList);

		var_dump($xpath);

		try {
			$element = $topElement->element('xpath', $xpath);
			$log->endAction();
		}
		catch (Exception $e) {
			throw new E5xx_ExpectFailed('BrowserExpects::hasHeading', $text, null);
		}
	}
}