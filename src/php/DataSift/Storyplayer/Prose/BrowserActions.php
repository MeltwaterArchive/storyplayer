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

/**
 * Do things using the web browser
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class BrowserActions extends Prose
{
	protected function initActions()
	{
		$this->initBrowser();
	}

	// ==================================================================
	//
	// Input actions go here
	//
	// ------------------------------------------------------------------

	public function check()
	{
		$topElement = $this->getTopElement();

		$action = new ContainedBrowserAction($this->st, $topElement);
		return $action->check();
	}

	public function clear()
	{
		$topElement = $this->getTopElement();

		$action = new ContainedBrowserAction($this->st, $topElement);
		return $action->clear();
	}

	public function click()
	{
		$topElement = $this->getTopElement();

		$action = new ContainedBrowserAction($this->st, $topElement);
		return $action->click();
	}

	public function select($label)
	{
		$topElement = $this->getTopElement();

		$action = new ContainedBrowserAction($this->st, $topElement);
		return $action->select($label);
	}

	public function type($text)
	{
		$topElement = $this->getTopElement();

		$action = new ContainedBrowserAction($this->st, $topElement);
		return $action->type($text);
	}

	public function fromElement($element)
	{
		return new ContainedBrowserAction($this->st, $element);
	}

	// ==================================================================
	//
	// Navigation actions go here
	//
	// ------------------------------------------------------------------

	public function gotoPage($url)
	{
		// some shorthand to make things easier to read
		$st      = $this->st;
		$browser = $st->getRunningWebBrowser();
		$env     = $st->getEnvironment();

		// relative, or absolute URL?
		if (substr($url, 0, 1) == '/') {
			// relative URL
			$url = $env->url . $url;
		}

		$log = $st->startAction("goto URL: $url");
		$browser->open($url);
		$log->endAction();
	}

	public function waitForOverlay($timeout, $id)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("wait for the overlay with id '{$id}' to appear");

		// check for the overlay
		$st->usingTimer()->waitFor(function() use($st, $id) {
			$st->expectsBrowser()->has()->elementWithId($id);
		}, $timeout);

		// all done
		$log->endAction();
	}

	public function waitForTitle($timeout, $title, $failedTitle = null)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("check that the the right page has loaded");

		// check the title
		$st->usingTimer()->waitFor(function() use($st, $title, $failedTitle) {
			// have we already failed?
			if ($failedTitle && $st->fromBrowser()->getTitle() == $failedTitle) {
				return false;
			}

			// we have not failed yet
			$st->expectsBrowser()->hasTitle($title);
		}, $timeout);

		// all done
		$log->endAction();
	}

	public function waitForTitles($timeout, $titles)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("check that the the right page has loaded");

		// check the title
		$st->usingTimer()->waitFor(function() use($st, $titles) {
			$st->expectsBrowser()->hasTitles($titles);
		}, $timeout);

		// all done
		$log->endAction();
	}

	// ==================================================================
	//
	// Window actions go here
	//
	// ------------------------------------------------------------------

	public function resizeCurrentWindow($width, $height)
	{
		// shorthand
		$st      = $this->st;
		$browser = $st->getRunningWebBrowser();

		// what are we doing?
		$log = $st->startAction("change the current browser window size to be {$width} x {$height} (w x h)");

		// resize the window
		$browser->window()->postSize(array("width" => $width, "height" => $height));

		// all done
		$log->endAction();
	}
}