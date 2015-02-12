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

namespace Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * Helper class for when we know what the action is, but don't yet know
 * what to apply the action to.
 *
 * This class makes it possible to write the following Prose:
 *
 *     $st->usingBrowser()->click()->buttonLabelled('Login');
 *
 * The 'click()' method lives in BrowserActions class, and behind the
 * scenes it creates a ContainedBrowserAction object and then calls
 * our 'click()' method.
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ContainedBrowserAction
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

		return new TargettedBrowserAction(
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

		return new TargettedBrowserAction(
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

		return new TargettedBrowserAction(
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
			$option = $element->getElement('xpath', 'option[normalize-space(text()) = "' . $label . '" ]');

			// select it
			$option->click();

			// all done
			$log->endAction();
		};

		return new TargettedBrowserAction(
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

		return new TargettedBrowserAction(
			$this->st,
			$action,
			"type",
			$this->topElement
		);
	}
}