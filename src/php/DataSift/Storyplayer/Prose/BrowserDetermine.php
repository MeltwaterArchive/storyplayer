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
	// ==================================================================
	//
	// Element finders go here
	//
	// ------------------------------------------------------------------

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

	public function getElementByClass($class, $tag = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '${tag}' element by '{$class}' class )");

		$elements = $this->getElementsByClass($class, $tag);
		return $this->returnFirstVisibleElement(
			$log,
			$elements,
			__METHOD__,
			"found one",
			"no matching elements"
		);
	}

	public function getElementById($id, $tag = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '{$tag}' element with id '{$id}' )");

		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// is there an element with this text?
		try {
			$xpath = 'descendant::' . $tag . '[@id = "' . $id . '"]';
			$elements = $log->addStep("( find element using xpath '{$xpath}' )", function() use($topElement, $xpath) {
				return $topElement->elements('xpath', $xpath);
			});
		}
		catch (Exception $e) {
			// log the result
			$log->endAction($failureMsg);

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}

		return $this->returnFirstVisibleElement(
			$log, $elements, __METHOD__, $successMsg, $failureMsg
		);
	}

	public function getElementByLabel($labelText, $tag = 'label')
	{
		// shorthand
		$st         = $this->st;
		$topElement = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("( get element for label '{$labelText}' )");

		try {
			$xpath = 'descendant::label[normalize-space(text()) = "' . $labelText . '"]';
			$labelElement = $log->addStep("( find the label with text '{$labelText}' using xpath '{$xpath}' )", function () use($xpath, $topElement){
				return $topElement->element('xpath', $xpath);
			});
		}
		catch (Exception $e) {
			$log->endAction("did not find label '{$labelText}'");

			throw $e;
		}

		try {
			$inputElementId = $log->addStep("( determine id of corresponding input element )", function() use($labelElement) {
				return $labelElement->attribute('for');
			});
		}
		catch (Exception $e) {
			$log->endAction("label '{$labelText}' is missing the 'for' attribute");

			throw $e;
		}

		try{
			$inputElement = $log->addStep("( find the input element with the id '{$inputElementId}' )", function() use($topElement, $inputElementId) {
				return $topElement->element('id', $inputElementId);
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

	public function getElementByLabelIdOrName($searchTerm, $tag = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '{$tag}' with label, id or name '{$searchTerm}' )");

		// can we find this puppy by its label?
		try {
			$return = $this->getElementByLabel($searchTerm, $tag);
			$log->endAction("found one by its label");
			return $return;
		}
		catch (Exception $e) {
			// do nothing
		}

		// okay, so can we find it by its id instead?
		try {
			$return = $this->getElementById($searchTerm, $tag);
			$log->endAction("found one by its id");
			return $return;
		}
		catch (Exception $e) {
			// do nothing
		}

		// last chance - can we find it by its text?
		$return = $this->getElementByName($searchTerm, $tag);
		$log->endAction("found one by its name");
		return $return;
	}

	public function getElementByName($name, $tag = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '{$tag}' element with name '{$name}' )");

		// what goes right and wrong
		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// is there an element with this text?
		try {
			$xpath = 'descendant::' . $tag . '[@name = "' . $name . '"]';
			$elements = $log->addStep("( find elements using xpath '{$xpath}' )", function() use($topElement, $xpath) {
				return $topElement->elements('xpath', $xpath);
			});
		}
		catch (Exception $e) {
			// log the result
			$log->endAction($failureMsg);

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}

		return $this->returnFirstVisibleElement(
			$log, $elements, __METHOD__, $successMsg, $failureMsg
		);
	}

	public function getElementByPlaceholder($text, $tag = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '{$tag}' element with placeholder '{$text}' )");

		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// is there an element with this text?
		try {
			$xpath = 'descendant::' . $tag . '[@placeholder = "' . $text . '"]';
			$elements = $log->addStep("( find elements using xpath '{$xapth}' )", function() use($topElement, $xpath) {
				return $topElement->elements('xpath', $xpath);
			});

		}
		catch (Exception $e) {
			// log the result
			$log->endAction($failureMsg);

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}

		return $this->returnFirstVisibleElement(
			$log, $elements, __METHOD__, $successMsg, $failureMsg
		);
	}

	public function getElementByText($text, $tag = '*')
	{
		// short hand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '{$tag}' element with text '{$text}' )");

		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// is there an element with this text?
		try {
			$xpathList = array(
				'descendant::' . $tag . '[normalize-space(text()) = "' . $text . '"]',
				'descendant::' . $tag . '[normalize-space(string(.)) = "' . $text . '"]',
				'descendant::' . $tag . '/*[normalize-space(string(.)) = "' . $text . '"]/parent::' . $tag
			);
			if ($tag == '*' || $tag == 'input') {
				$xpathList[] = 'descendant::input[normalize-space(@value) = "' . $text . '"]';
				$xpathList[] = 'descendant::input[normalize-space(@placeholder) = "' . $text . '"]';
			}

			foreach ($xpathList as $xpath) {
				$elements = $log->addStep("( find elements using xpath '{$xpath}' )", function() use($topElement, $xpath) {
					return $topElement->elements('xpath', $xpath);
				});

				if (count($elements) > 0) {
					break;
				}
			}
		}
		catch (Exception $e) {
			// log the result
			$log->endAction($failureMsg);

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}

		return $this->returnFirstVisibleElement(
			$log, $elements, __METHOD__, $successMsg, $failureMsg
		);

	}

	public function getElementByTitle($title, $tag = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '{$tag}' element with title '{$title}' )");

		$successMsg = "found one";
		$failureMsg = "no matching elements";

		// shorthand
		$topElement = $this->getTopElement();

		// is there an element with this text?
		try {
			$xpath = 'descendant::' . $tag . '[@title = "' . $title . '"]';

			$element = $log->addStep("( find element using xpath '{$xpath}' )", function() use($topElement, $xpath) {
				return $topElement->element('xpath', $xpath);
			});

			// all done
			$log->endAction($successMsg);

			// return the element
			return $element;
		}
		catch (Exception $e) {
			// log the result
			$log->endAction($failureMsg);

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}

	}

	public function getElementsByClass($class, $tag = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '{$tag}' elements with CSS class '{$class}' )");

		// shorthand
		$topElement = $this->getTopElement();

		try {
			$xpath = 'descendant::' . $tag . '[contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ")]';
			$elements = $log->addStep("( find elements using xpath '{$xpath}' )", function() use($topElement, $xpath) {
				return $topElement->elements('xpath', $xpath);
			});

			// all done
			$log->endAction(count($elements) . " element(s) found");

			// return the elements
			return $elements;
		}
		catch (Exception $e) {
			// log the result
			$log->endAction("no matching elements");

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}
	}

	public function getElementsById($id, $tag = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '{$tag}' elements with id '{$id}' )");

		// shorthand
		$topElement = $this->getTopElement();

		// is there an element with this text?
		try {
			$xpath = 'descendant::' . $tag . '[@id = "' . $id . '"]';
			$elements = $log->addStep("( find elements using xpath '{$xpath}' )", function() use($topElement, $xpath) {
				return $topElement->elements('xpath', $xpath);
			});

			// log the result
			$log->endAction(count($elements) . " element(s) found");

			// return the element
			return $elements;
		}
		catch (Exception $e) {
			// log the result
			$log->endAction("no matching elements");

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}
	}

	public function getElementsByName($name, $tag = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '{$tag}' elements with name '{$name}' )");

		// shorthand
		$topElement = $this->getTopElement();

		// is there an element with this text?
		try {
			$xpath = 'descendant::' . $tag . '[@name = "' . $name . '"]';
			$elements = $log->addStep("( find elements using xpath '{$xpath}' )", function() use($topElement, $xpath) {
				$topElement->elements('xpath', $xpath);
			});

			// log the result
			$log->endAction(count($elements) . " element(s) found");

			// return the element
			return $elements;
		}
		catch (Exception $e) {
			// log the result
			$log->endAction("no matching elements");

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}
	}

	public function getElementsByText($text, $tag = '*')
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("( get '{$tag}' elements with text '{$text}' )");

		// shorthand
		$topElement = $this->getTopElement();

		// is there an element with this text?
		try {
			$xpath = 'descendant::' . $tag . '[normalize-space(text()) = "' . $text . '"]|descendant::' . $tag . '/*[normalize-space(string(.)) = "' . $text . '"]|descendant::input[@value = "' . $text . '"]|descendant::input[@placeholder = "' . $text . '"]';
			$elements = $log->addStep("( find elements using xpath '{$xpath}' )", function() use($topElement, $xpath) {
				$topElement->elements('xpath', $xpath);
			});

			// log the result
			$log->endAction(count($elements) . " element(s) found");

			// return the element
			return $elements;
		}
		catch (Exception $e) {
			// log the result
			$log->endAction("no matching elements");

			// report the failure
			throw new E5xx_ActionFailed(__METHOD__);
		}
	}

	// ==================================================================
	//
	// Tests for elements go here
	//
	// ------------------------------------------------------------------

	public function has()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {

			$log = $st->startAction("[ check the current page for $elementDesc '$elementName' ]");
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

			$log = $st->startAction("[ retrieve the $elementDesc '$elementName' ]");
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

			$log = $st->startAction("[ retrieve the name of the $elementDesc '$elementName' ]");
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

			$log = $st->startAction("[ retrieve the names of the $elementDesc '$elementName' ]");
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

			$log = $st->startAction("[ retrieve the options of them $elementDesc '$elementName' ]");
			// get the elements
			$optionElements = $element->elements('xpath', "descendant::option");

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
			$log = $st->startAction("[ retrieve the tagname of the $elementDesc '$elementName' ]");
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
			$log = $st->startAction("[ retrieve the text of the $elementDesc '$elementName' ]");
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
			$log = $st->startAction("[ retrieve the value of the $elementDesc '$elementName' ]");

			// is this a select box?
			switch($element->name()) {
				case 'select':
					// get the option that is selected
					try {
						$option = $element->element('xpath', 'option[@selected]');
						$log->endAction("value is: " . $option->text());
						return $option->text();
					}
					catch (Exception $e) {
						// return the top option from the list
						$option = $element->element('xpath', 'option[1]');
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
		$browser = $st->getWebBrowser();

		$log = $st->startAction("[ retrieve the current page title ]");
		$log->endAction("title is: " . $browser->title());

		return $browser->title();
	}
}