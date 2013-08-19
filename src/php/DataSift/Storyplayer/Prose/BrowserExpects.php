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
	protected function initActions()
	{
		$this->initBrowser();
	}

	public function doesntHave()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {

			$log = $st->startAction("$elementDesc '$elementName' must not exist");

			if (is_object($element)) {
				$log->endAction();
				return true;
			}

			throw new E5xx_ExpectFailed(__METHOD__, 'element does not exist', 'element exists');
		};

		return new TargettedBrowserSearch(
			$this->st,
			$action,
			"doesntHave",
			$this->getTopElement()
		);
	}

	public function has()
	{
		$action = function(StoryTeller $st, $element, $elementName, $elementDesc) {

			$log = $st->startAction("$elementDesc '$elementName' must exist");

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

	public function hasField($searchTerm)
	{
		// shorthand
		$st = $this->st;

		// how do we find the element to test?
		$action = function() use ($st, $searchTerm) {
			$element = $st->fromBrowser()->getElementByLabelIdOrName($searchTerm);
		};

		return new TargettedBrowserExpects($st, $action, $searchTerm, 'field');
	}

	public function hasTitle($title)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("page title must be {$title}");

		// get the browser title
		$browserTitle = $this->st->fromBrowser()->getTitle();

		if ($title != $browserTitle) {
			throw new E5xx_ExpectFailed('BrowserExpects::title', $title, $browserTitle);
		}

		// all done
		$log->endAction();
	}

	public function hasTitles($titles)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$titlesString = implode('; or ', $titles);
		$log = $st->startAction("page title must be one of: {$titlesString}");

		// get the browser title
		$browserTitle = $this->st->fromBrowser()->getTitle();

		if (!in_array($browserTitle, $titles)) {
			throw new E5xx_ExpectFailed(__METHOD__, $titlesString, $browserTitle);
		}

		// all done
		$log->endAction();
	}

	public function currentWindowSizeIs($width, $height)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("current browser window dimensions must be '{$width}' x '{$height}' (w x h)");

		// get the dimensions
		$dimensions = $st->fromBrowser()->getCurrentWindowSize();

		// are they right?
		if ($dimensions['width'] != $width || $dimensions['height'] != $height) {
			throw new E5xx_ExpectFailed(__METHOD__, "$width x $height", "{$dimensions['width']} x {$dimensions['height']}");
		}

		// all done
		$log->endAction();
	}
}