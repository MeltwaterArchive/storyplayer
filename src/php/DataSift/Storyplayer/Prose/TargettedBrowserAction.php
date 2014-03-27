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

use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * Helper class that allows us to write Prose where the action comes before
 * we say what DOM element we want to act upon
 *
 * @method void boxWithId(string $id)
 * @method void boxWithLabel(string $label)
 * @method void boxLabelled(string $label)
 * @method void boxWithName(string $name)
 * @method void boxNamed(string $name)
 * @method void boxWithClass(string $class)
 * @method void boxWithPlaceholder(string $text)
 * @method void boxWithTitle(string $title)
 * @method void boxWithLabelTextOrId(string $labelTextOrId)
 * @method void boxesWithId(string $id)
 * @method void boxesWithLabel(string $label)
 * @method void boxesLabelled(string $label)
 * @method void boxesWithName(string $name)
 * @method void boxesNamed(string $name)
 * @method void boxesWithClass(string $class)
 * @method void boxesWithPlaceholder(string $text)
 * @method void boxesWithTitle(string $title)
 * @method void boxesWithLabelTextOrId(string $labelTextOrId)
 * @method void buttonWithId(string $id)
 * @method void buttonWithLabel(string $label)
 * @method void buttonLabelled(string $label)
 * @method void buttonWithName(string $name)
 * @method void buttonNamed(string $name)
 * @method void buttonWithClass(string $class)
 * @method void buttonWithPlaceholder(string $text)
 * @method void buttonWithTitle(string $title)
 * @method void buttonWithLabelTextOrId(string $labelTextOrId)
 * @method void buttonsWithId(string $id)
 * @method void buttonsWithLabel(string $label)
 * @method void buttonsLabelled(string $label)
 * @method void buttonsWithName(string $name)
 * @method void buttonsNamed(string $name)
 * @method void buttonsWithClass(string $class)
 * @method void buttonsWithPlaceholder(string $text)
 * @method void buttonsWithTitle(string $title)
 * @method void buttonsWithLabelTextOrId(string $labelTextOrId)
 * @method void cellWithId(string $id)
 * @method void cellWithLabel(string $label)
 * @method void cellLabelled(string $label)
 * @method void cellWithName(string $name)
 * @method void cellNamed(string $name)
 * @method void cellWithClass(string $class)
 * @method void cellWithPlaceholder(string $text)
 * @method void cellWithTitle(string $title)
 * @method void cellWithLabelTextOrId(string $labelTextOrId)
 * @method void cellsWithId(string $id)
 * @method void cellsWithLabel(string $label)
 * @method void cellsLabelled(string $label)
 * @method void cellsWithName(string $name)
 * @method void cellsNamed(string $name)
 * @method void cellsWithClass(string $class)
 * @method void cellsWithPlaceholder(string $text)
 * @method void cellsWithTitle(string $title)
 * @method void cellsWithLabelTextOrId(string $labelTextOrId)
 * @method void dropdownWithId(string $id)
 * @method void dropdownWithLabel(string $label)
 * @method void dropdownLabelled(string $label)
 * @method void dropdownWithName(string $name)
 * @method void dropdownNamed(string $name)
 * @method void dropdownWithClass(string $class)
 * @method void dropdownWithPlaceholder(string $text)
 * @method void dropdownWithTitle(string $title)
 * @method void dropdownWithLabelTextOrId(string $labelTextOrId)
 * @method void dropdownsWithId(string $id)
 * @method void dropdownsWithLabel(string $label)
 * @method void dropdownsLabelled(string $label)
 * @method void dropdownsWithName(string $name)
 * @method void dropdownsNamed(string $name)
 * @method void dropdownsWithClass(string $class)
 * @method void dropdownsWithPlaceholder(string $text)
 * @method void dropdownsWithTitle(string $title)
 * @method void dropdownsWithLabelTextOrId(string $labelTextOrId)
 * @method void elementWithId(string $id)
 * @method void elementWithLabel(string $label)
 * @method void elementLabelled(string $label)
 * @method void elementWithName(string $name)
 * @method void elementNamed(string $name)
 * @method void elementWithClass(string $class)
 * @method void elementWithPlaceholder(string $text)
 * @method void elementWithTitle(string $title)
 * @method void elementWithLabelTextOrId(string $labelTextOrId)
 * @method void elementsWithId(string $id)
 * @method void elementsWithLabel(string $label)
 * @method void elementsLabelled(string $label)
 * @method void elementsWithName(string $name)
 * @method void elementsNamed(string $name)
 * @method void elementsWithClass(string $class)
 * @method void elementsWithPlaceholder(string $text)
 * @method void elementsWithTitle(string $title)
 * @method void elementsWithLabelTextOrId(string $labelTextOrId)
 * @method void fieldWithId(string $id)
 * @method void fieldWithLabel(string $label)
 * @method void fieldLabelled(string $label)
 * @method void fieldWithName(string $name)
 * @method void fieldNamed(string $name)
 * @method void fieldWithClass(string $class)
 * @method void fieldWithPlaceholder(string $text)
 * @method void fieldWithTitle(string $title)
 * @method void fieldWithLabelTextOrId(string $labelTextOrId)
 * @method void fieldsWithId(string $id)
 * @method void fieldsWithLabel(string $label)
 * @method void fieldsLabelled(string $label)
 * @method void fieldsWithName(string $name)
 * @method void fieldsNamed(string $name)
 * @method void fieldsWithClass(string $class)
 * @method void fieldsWithPlaceholder(string $text)
 * @method void fieldsWithTitle(string $title)
 * @method void fieldsWithLabelTextOrId(string $labelTextOrId)
 * @method void headingWithId(string $id)
 * @method void headingWithLabel(string $label)
 * @method void headingLabelled(string $label)
 * @method void headingWithName(string $name)
 * @method void headingNamed(string $name)
 * @method void headingWithClass(string $class)
 * @method void headingWithPlaceholder(string $text)
 * @method void headingWithTitle(string $title)
 * @method void headingWithLabelTextOrId(string $labelTextOrId)
 * @method void headingsWithId(string $id)
 * @method void headingsWithLabel(string $label)
 * @method void headingsLabelled(string $label)
 * @method void headingsWithName(string $name)
 * @method void headingsNamed(string $name)
 * @method void headingsWithClass(string $class)
 * @method void headingsWithPlaceholder(string $text)
 * @method void headingsWithTitle(string $title)
 * @method void headingsWithLabelTextOrId(string $labelTextOrId)
 * @method void linkWithId(string $id)
 * @method void linkWithLabel(string $label)
 * @method void linkLabelled(string $label)
 * @method void linkWithName(string $name)
 * @method void linkNamed(string $name)
 * @method void linkWithClass(string $class)
 * @method void linkWithPlaceholder(string $text)
 * @method void linkWithTitle(string $title)
 * @method void linkWithLabelTextOrId(string $labelTextOrId)
 * @method void linksWithId(string $id)
 * @method void linksWithLabel(string $label)
 * @method void linksLabelled(string $label)
 * @method void linksWithName(string $name)
 * @method void linksNamed(string $name)
 * @method void linksWithClass(string $class)
 * @method void linksWithPlaceholder(string $text)
 * @method void linksWithTitle(string $title)
 * @method void linksWithLabelTextOrId(string $labelTextOrId)
 * @method void orderedlistWithId(string $id)
 * @method void orderedlistWithLabel(string $label)
 * @method void orderedlistLabelled(string $label)
 * @method void orderedlistWithName(string $name)
 * @method void orderedlistNamed(string $name)
 * @method void orderedlistWithClass(string $class)
 * @method void orderedlistWithPlaceholder(string $text)
 * @method void orderedlistWithTitle(string $title)
 * @method void orderedlistWithLabelTextOrId(string $labelTextOrId)
 * @method void spanWithId(string $id)
 * @method void spanWithLabel(string $label)
 * @method void spanLabelled(string $label)
 * @method void spanWithName(string $name)
 * @method void spanNamed(string $name)
 * @method void spanWithClass(string $class)
 * @method void spanWithPlaceholder(string $text)
 * @method void spanWithTitle(string $title)
 * @method void spanWithLabelTextOrId(string $labelTextOrId)
 * @method void unorderedlistWithId(string $id)
 * @method void unorderedlistWithLabel(string $label)
 * @method void unorderedlistLabelled(string $label)
 * @method void unorderedlistWithName(string $name)
 * @method void unorderedlistNamed(string $name)
 * @method void unorderedlistWithClass(string $class)
 * @method void unorderedlistWithPlaceholder(string $text)
 * @method void unorderedlistWithTitle(string $title)
 * @method void unorderedlistWithLabelTextOrId(string $labelTextOrId)
 * @method void intoElement($element)
 * @method void inElement($element)
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TargettedBrowserAction extends TargettedBrowserBase
{
	protected $st;
	protected $action;
	protected $actionDesc;
	protected $baseElement;

	/**
	 * @param \Closure $action
	 * @param string $actionDesc
	 */
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
			if ($searchType == null) {				// we do not understand how to find the target field
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
			$searchObject = $this->st->fromBrowser();
			$searchObject->setTopElement($this->baseElement);

			$element = $searchObject->$searchMethod($searchTerm, $tag);
		}
		else {
			$element = $methodArgs[0];

			if (!is_object($element)) {
				throw new E5xx_ActionFailed(__CLASS__ . '::' . $methodName, "expected a WebDriverElement as 1st parameter to search term");
			}

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