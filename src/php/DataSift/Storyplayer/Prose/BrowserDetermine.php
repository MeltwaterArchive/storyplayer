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
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Prose;

use Exception;
use DataSift\Storyplayer\ProseLib\Prose;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\TargettedBrowserAction;
use DataSift\Storyplayer\ProseLib\TargettedBrowserSearch;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\ActionLogItem;

/**
 * Get information from the browser
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class BrowserDetermine extends Prose
{
	protected function initActions()
	{
		$this->initBrowser();
	}

	// ==================================================================
	//
	// Element finders go here
	//
	// ------------------------------------------------------------------

	protected function convertTagsToString($tags)
	{
		if (is_string($tags)) {
			return $tags;
		}

		return implode('|', $tags);
	}

	protected function returnFirstVisibleElement(ActionLogItem $log, $elements, $method, $successMsg, $failureMsg)
	{
		// if the page contains multiple matches, return the first one
		// that the user can see
		foreach ($elements as $element) {
			if (!$element->displayed()) {
				continue;
			}

			$location = $element->location();
			if ($location['x'] >=0 && $location['y'] >= 0) {
				$log->endAction($successMsg);

				// return the element
				return $element;
			}
		}

		// if we get here, then there was no visible element
		$log->endAction($failureMsg);

		throw new E5xx_ActionFailed($method);
	}

	public function getElementByClass($class, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '${tag}' element by '{$class}' class");

		$elements = $this->getElementsByClass($class, $tags);
		return $this->returnFirstVisibleElement(
			$log,
			$elements,
			__METHOD__,
			"found one",
			"no matching elements"
		);
	}

	public function getElementById($id, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' element with id '{$id}'");

		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// prepare the list of tags
		if (is_string($tags)) {
			$tags = array($tags);
		}

		// build up the xpath to use
		$xpathList = array();
		foreach ($tags as $tag) {
			$xpathList[] = 'descendant::' . $tag . '[@id = "' . $id . '"]';
		}

		// get the possibly matching elements
		$elements = $this->getElementsByXpath($xpathList);

		// find the first one that the user can see
		return $this->returnFirstVisibleElement(
			$log, $elements, __METHOD__, $successMsg, $failureMsg
		);
	}

	public function getElementByLabel($labelText)
	{
		// shorthand
		$st         = $this->st;
		$topElement = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("get element for label '{$labelText}'");

		try {
			$xpath = 'descendant::label[normalize-space(text()) = "' . $labelText . '"]';
			$labelElement = $log->addStep("find the label with text '{$labelText}' using xpath '{$xpath}'", function () use($xpath, $topElement){
				return $topElement->getElement('xpath', $xpath);
			});
		}
		catch (Exception $e) {
			$log->endAction("did not find label '{$labelText}'");

			throw $e;
		}

		try {
			$inputElementId = $log->addStep("determine id of corresponding input element", function() use($labelElement) {
				return $labelElement->attribute('for');
			});
		}
		catch (Exception $e) {
			$log->endAction("label '{$labelText}' is missing the 'for' attribute");

			throw $e;
		}

		try{
			$inputElement = $log->addStep("find the input element with the id '{$inputElementId}'", function() use($topElement, $inputElementId) {
				return $topElement->getElement('id', $inputElementId);
			});

			// all done
			$log->endAction();
			return $inputElement;
		}
		catch (Exception $e) {

			$log->endAction("could not find element with id '{$inputElementId}'");
			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}
	}

	public function getElementByLabelIdOrName($searchTerm, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' with label, id or name '{$searchTerm}'");

		// can we find this puppy by its label?
		try {
			$return = $this->getElementByLabel($searchTerm, $tags);
			$log->endAction("found one by its label");
			return $return;
		}
		catch (Exception $e) {
			// do nothing
		}

		// okay, so can we find it by its id instead?
		try {
			$return = $this->getElementById($searchTerm, $tags);
			$log->endAction("found one by its id");
			return $return;
		}
		catch (Exception $e) {
			// do nothing
		}

		// last chance - can we find it by its text?
		$return = $this->getElementByName($searchTerm, $tags);
		$log->endAction("found one by its name");
		return $return;
	}

	public function getElementByName($name, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' element with name '{$name}'");

		// what goes right and wrong
		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// prepare the list of tags
		if (is_string($tags)) {
			$tags = array($tags);
		}

		// build up the xpath to use
		$xpathList = array();
		foreach ($tags as $tag) {
			$xpathList[] = 'descendant::' . $tag . '[@name = "' . $name . '"]';
		}

		// get the possibly matching elements
		$elements = $this->getElementsByXpath($xpathList);

		// find the first one that the user can see
		return $this->returnFirstVisibleElement(
			$log, $elements, __METHOD__, $successMsg, $failureMsg
		);
	}

	public function getElementByPlaceholder($text, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' element with placeholder '{$text}'");

		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// prepare the list of tags
		if (is_string($tags)) {
			$tags = array($tags);
		}

		// build up the xpath to use
		$xpathList = array();
		foreach ($tags as $tag) {
			$xpathList[] = 'descendant::' . $tag . '[@placeholder = "' . $text . '"]';
		}

		// get the possibly matching elements
		$elements = $this->getElementsByXpath($xpathList);

		// find the first one that the user can see
		return $this->returnFirstVisibleElement(
			$log, $elements, __METHOD__, $successMsg, $failureMsg
		);
	}

	public function getElementByAltText($text, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' element with alt text '{$text}'");

		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// prepare the list of tags
		if (is_string($tags)) {
			$tags = array($tags);
		}

		// build up the xpath to use
		$xpathList = array();
		foreach ($tags as $tag) {
			$xpathList[] = 'descendant::' . $tag . '[@alt = "' . $text . '"]';
		}

		// get the possibly matching elements
		$elements = $this->getElementsByXpath($xpathList);

		// find the first one that the user can see
		return $this->returnFirstVisibleElement(
			$log, $elements, __METHOD__, $successMsg, $failureMsg
		);
	}

	public function getElementByText($text, $tags = '*')
	{
		// short hand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' element with text '{$text}'");

		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// prepare the list of tags
		if (is_string($tags)) {
			$tags = array($tags);
		}

		// build up the xpath to use
		$xpathList = array();
		foreach ($tags as $tag) {
			$xpathList[] = 'descendant::' . $tag . '[normalize-space(text()) = "' . $text . '"]';
			$xpathList[] = 'descendant::' . $tag . '[normalize-space(string(.)) = "' . $text . '"]';
			$xpathList[] = 'descendant::' . $tag . '/*[normalize-space(string(.)) = "' . $text . '"]/parent::' . $tag;

			// special cases
			if ($tag == '*' || $tag == 'input' || $tag == 'button') {
				$xpathList[] = 'descendant::input[normalize-space(@value) = "' . $text . '"]';
				$xpathList[] = 'descendant::input[normalize-space(@placeholder) = "' . $text . '"]';
			}
		}

		// get the possibly matching elements
		$elements = $this->getElementsByXpath($xpathList);

		// find the first one that the user can see
		return $this->returnFirstVisibleElement(
			$log, $elements, __METHOD__, $successMsg, $failureMsg
		);
	}

	public function getElementByTitle($title, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' element with title '{$title}'");

		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// prepare the list of tags
		if (is_string($tags)) {
			$tags = array($tags);
		}

		// build up the xpath to use
		$xpathList = array();
		foreach ($tags as $tag) {
			$xpathList[] = 'descendant::' . $tag . '[@title = "' . $title . '"]';
		}

		// search using the xpath
		$elements = $this->getElementsByXpath($xpathList);

		// get the possibly matching elements
		$elements = $this->getElementsByXpath($xpathList);

		// find the first one that the user can see
		return $this->returnFirstVisibleElement(
			$log, $elements, __METHOD__, $successMsg, $failureMsg
		);
	}

	public function getElementByXpath($xpathList)
	{
		// shorthand
		$st = $this->st;
		$topElement = $this->getTopElement();

		try{
			foreach ($xpathList as $xpath) {
				$element = $log->addStep("find element using xpath '{$xpath}'", function() use($topElement, $xpath) {
					return $topElement->getElement('xpath', $xpath);
				});

				// return the element
				return $element;
			}
		}
		catch (Exception $e) {
			// log the result
			$log->endAction("no matching elements");

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// if we get here, we found a match
		return $element;
	}

	public function getElementsByClass($class, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' elements with CSS class '{$class}'");

		// shorthand
		$topElement = $this->getTopElement();

		// prepare the list of tags
		if (is_string($tags)) {
			$tags = array($tags);
		}

		// build up the xpath to use
		$xpathList = array();
		foreach ($tags as $tag) {
			$xpathList[] = 'descendant::' . $tag . '[contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ")]';
		}

		// find the matches
		$elements = $this->getElementsByXpath($xpathList);

		// log the result
		$log->endAction(count($elements) . " element(s) found");

		// return the elements
		return $elements;
	}

	public function getElementsById($id, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' elements with id '{$id}'");

		// shorthand
		$topElement = $this->getTopElement();

		// prepare the list of tags
		if (is_string($tags)) {
			$tags = array($tags);
		}

		// build up the xpath to use
		$xpathList = array();
		foreach ($tags as $tag) {
			$xpathList[] = 'descendant::' . $tag . '[@id = "' . $id . '"]';
		}

		// find the matches
		$elements = $this->getElementsByXpath($xpathList);

		// log the result
		$log->endAction(count($elements) . " element(s) found");

		// return the elements
		return $elements;
	}

	public function getElementsByName($name, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' elements with name '{$name}'");

		// shorthand
		$topElement = $this->getTopElement();

		// prepare the list of tags
		if (is_string($tags)) {
			$tags = array($tags);
		}

		// build up the xpath to use
		$xpathList = array();
		foreach ($tags as $tag) {
			$xpathList[] = 'descendant::' . $tag . '[@name = "' . $name . '"]';
		}

		// find the matches
		$elements = $this->getElementsByXpath($xpathList);

		// log the result
		$log->endAction(count($elements) . " element(s) found");

		// return the elements
		return $elements;
	}

	public function getElementsByText($text, $tags = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$tag = $this->convertTagsToString($tags);
		$log = $st->startAction("get '{$tag}' elements with text '{$text}'");

		// shorthand
		$topElement = $this->getTopElement();

		// prepare the list of tags
		if (is_string($tags)) {
			$tags = array($tags);
		}

		// build up the xpath to use
		$xpathList = array();
		foreach ($tags as $tag) {
			$xpathList[] = 'descendant::' . $tag . '[normalize-space(text()) = "' . $text . '"]|descendant::' . $tag . '/*[normalize-space(string(.)) = "' . $text . '"]|descendant::input[@value = "' . $text . '"]|descendant::input[@placeholder = "' . $text . '"]';
		}

		// find the matches
		$elements = $this->getElementsByXpath($xpathList);

		// log the result
		$log->endAction(count($elements) . " element(s) found");

		// return the elements
		return $elements;
	}

	public function getElementsByXpath($xpathList)
	{
		// shorthand
		$st = $this->st;
		$topElement = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("search the browser's DOM using a list of XPath queries");

		// our set of elements to return
		$return = array();

		try{
			foreach ($xpathList as $xpath) {
				$elements = $log->addStep("find elements using xpath '{$xpath}'", function() use($topElement, $xpath) {
					return $topElement->getElements('xpath', $xpath);
				});

				if (count($elements) > 0) {
					// add these elements to the total list
					$return = array_merge($return, $elements);
				}
			}
		}
		catch (Exception $e) {
			// log the result
			$log->endAction("no matching elements");

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// if we get here, we found a match
		$log->endAction("found " . count($return) . " element(s)");
		return $return;
	}

	// ==================================================================
	//
	// Tests for elements go here
	//
	// ------------------------------------------------------------------

	public function has()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {

			$log = $st->startAction("check the current page for $elementDesc '$elementName'");
			if (is_object($element)) {
				$log->endAction('found it');
				return true;
			}

			$log->endAction('could not find it');
			return false;
		};

		return new TargettedBrowserSearch(
			$this->st,
			$action,
			"has",
			$this->getTopElement()
		);
	}

	public function get()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {

			$log = $st->startAction("retrieve the $elementDesc '$elementName'");
			$log->endAction();
			return $element;
		};

		return new TargettedBrowserAction(
			$this->st,
			$action,
			"get",
			$this->getTopElement()
		);
	}

	public function getName()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {

			$log = $st->startAction("retrieve the name of the $elementDesc '$elementName'");
			$log->endAction('name is: ' . $element->attribute('name'));
			return $element->attribute('name');
		};

		return new TargettedBrowserAction(
			$this->st,
			$action,
			"getName",
			$this->getTopElement()
		);
	}

	public function getNames()
	{
		$action = function(StoryTeller $st, $elements, $elementName, $elementDesc) {

			$log = $st->startAction("retrieve the names of the $elementDesc '$elementName'");
			if (!is_array($elements)) {
				$log->endAction('1 element found');
				return $element->attribute('name');
			}

			$return = array();
			foreach ($elements as $element) {
				$return[] = $element->attribute('name');
			}

			$log->endAction(count($return) . ' element(s) found');
			return $return;
		};

		return new TargettedBrowserAction(
			$this->st,
			$action,
			"getNames",
			$this->getTopElement()
		);
	}

	public function getOptions()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {

			$log = $st->startAction("retrieve the options of them $elementDesc '$elementName'");
			// get the elements
			$optionElements = $element->getElements('xpath', "descendant::option");

			// extract their values
			$return = array();
			foreach ($optionElements as $optionElement) {
				$return[] = $optionElement->text();
			}

			// all done
			$log->endAction(count($return) . ' option(s) found');
			return $return;
		};

		return new TargettedBrowserAction(
			$this->st,
			$action,
			'getOptions',
			$this->getTopElement()
		);
	}

	public function getTag()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {
			$log = $st->startAction("retrieve the tagname of the $elementDesc '$elementName'");
			$log->endAction("tag is: " . $element->name());
			return $element->name();
		};

		return new TargettedBrowserAction(
			$this->st,
			$action,
			"getTag",
			$this->getTopElement()
		);
	}

	public function getText()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {
			$log = $st->startAction("retrieve the text of the $elementDesc '$elementName'");
			$log->endAction("text is: " . $element->text());
			return $element->text();
		};

		return new TargettedBrowserAction(
			$this->st,
			$action,
			"getName",
			$this->getTopElement()
		);
	}

	public function getValue()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {
			$log = $st->startAction("retrieve the value of the $elementDesc '$elementName'");

			// is this a select box?
			switch($element->name()) {
				case 'select':
					// get the option that is selected
					try {
						$option = $element->getElement('xpath', 'option[@selected]');
						$log->endAction("value is: " . $option->text());
						return $option->text();
					}
					catch (Exception $e) {
						// return the top option from the list
						$option = $element->getElement('xpath', 'option[1]');
						$log->endAction("value is: " . $option->text());
						return $option->text();
					}
					break;

				default:
					$log->endAction("value is: " . $element->text());
					return $element->text();
			}
		};

		return new TargettedBrowserAction(
			$this->st,
			$action,
			"getValue",
			$this->getTopElement()
		);
	}

	// ==================================================================
	//
	// Retrievers of page metadata
	//
	// ------------------------------------------------------------------

	public function getTitle()
	{
		// some shorthand to make things easier to read
		$st      = $this->st;
		$browser = $st->getRunningWebBrowser();

		$log = $st->startAction("retrieve the current page title");
		$log->endAction("title is: " . $browser->title());

		return $browser->title();
	}

	// ==================================================================
	//
	// Retrievers of browser metadata
	//
	// ------------------------------------------------------------------

	public function getCurrentWindowSize()
	{
		// shorthand
		$st      = $this->st;
		$browser = $st->getRunningWebBrowser();

		// what are we doing?
		$log = $st->startAction("retrieve the current browser window's dimensions");

		// get the dimensions
		$dimensions = $browser->window()->getSize();

		// all done
		$log->endAction("width: '{$dimensions['width']}'; height: '{$dimensions['height']}'");
		return array('width' => $dimensions['width'], 'height' => $dimensions['height']);
	}
}