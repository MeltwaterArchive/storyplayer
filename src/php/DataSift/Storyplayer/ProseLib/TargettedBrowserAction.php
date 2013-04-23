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
 * @package   Storyplayer/ProseLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
namespace DataSift\Storyplayer\ProseLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * Helper class that allows us to write Prose where the action comes before
 * we say what DOM element we want to act upon
 *
 * @category  Libraries
 * @package   Storyplayer/ProseLib
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