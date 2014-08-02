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
class UsingBrowser extends Prose
{
	protected function initActions()
	{
		$this->initDevice();
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
		$browser = $this->device;

		// relative, or absolute URL?
		if (substr($url, 0, 1) == '/') {
			// only absolute URLs are supported
			throw new E5xx_ActionFailed(__METHOD__, 'only absolute URLs are supported');
		}

		// parse the URL
		$urlParts = parse_url($url);

		// if we have no host, we cannot continue
		if (isset($urlParts['host'])) {
			// do we have any HTTP AUTH credentials to merge in?
			if ($st->fromBrowser()->hasHttpBasicAuthForHost($urlParts['host'])) {
				$adapter = $st->getDeviceAdapter();

				// the adapter *might* embed the authentication details
				// into the URL
				$url = $adapter->applyHttpBasicAuthForHost($urlParts['host'], $url);
			}
		}

		// what are we doing?
		$log = $st->startAction("goto URL: $url");

		// tell the browser to move to the page we want
		$browser->open($url);

		// all done
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
		$browser = $this->device;

		// what are we doing?
		$log = $st->startAction("change the current browser window size to be {$width} x {$height} (w x h)");

		// resize the window
		$browser->window()->postSize(array("width" => $width, "height" => $height));

		// all done
		$log->endAction();
	}

	public function switchToWindow($name)
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->device;

		// what are we doing?
		$log = $st->startAction("switch to browser window called '{$name}'");

		// get the list of available window handles
		$handles = $browser->window_handles();

		// we have to iterate over them, to find the window that we want
		foreach ($handles as $handle) {
			// switch to the window
			$browser->focusWindow($handle);

			// is this the window that we want?
			$title = $browser->title();
			if ($title == $name) {
				// all done
				$log->endAction();
				return;
			}
		}

		// if we get here, then we could not find the window we wanted
		// the browser might be pointing at ANY of the open windows,
		// and it might be pointing at no window at all
		throw new E5xx_ActionFailed(__METHOD__, "No such window '{$name}'");
	}

	public function closeCurrentWindow()
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->device;

		// what are we doing?
		$log = $st->startAction("close the current browser window");

		// close the current window
		$browser->deleteWindow();

		// all done
		$log->endAction();
	}

	// ==================================================================
	//
	// IFrame actions go here
	//
	// ------------------------------------------------------------------

	public function switchToIframe($id)
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->device;

		// what are we doing?
		$log = $st->startAction("switch to working inside the iFrame with the id '{$id}'");

		// switch to the iFrame
		$browser->frame(array('id' => $id));

		// all done
		$log->endAction();
	}

	public function switchToMainFrame()
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->device;

		// what are we doing?
		$log = $st->startAction("switch to working with the main frame");

		// switch to the iFrame
		$browser->frame(array('id' => null));

		// all done
		$log->endAction();
	}

	// ==================================================================
	//
	// Authentication actions go here
	//
	// ------------------------------------------------------------------

	public function setHttpBasicAuthForHost($hostname, $username, $password)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("set HTTP basic auth for host '{$hostname}': user: '{$username}'; password: '{$password}'");

		try {
			// get the browser adapter
			$adapter = $st->getDeviceAdapter();

			// set the details
			$adapter->setHttpBasicAuthForHost($hostname, $username, $password);
		}
		catch (Exception $e)
		{
			throw new E5xx_ActionFailed(__METHOD__, "unable to set HTTP basic auth; error is: " . $e->getMessage());
		}

		// all done
		$log->endAction();
	}
}