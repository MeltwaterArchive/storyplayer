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
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Storyplayer\ProseLib\TargettedBrowserExpects;
use DataSift\Storyplayer\ProseLib\TargettedBrowserSearch;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * Test the current contents of the browser
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class BrowserExpects extends Prose
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

		return new TargettedBrowserSearch(
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

		return new TargettedBrowserExpects($st, $action, $searchTerm, 'field');
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