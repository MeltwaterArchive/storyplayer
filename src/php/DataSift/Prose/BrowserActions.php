<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ContainedAction;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\TargettedAction;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class BrowserActions extends ProseActions
{
	// ==================================================================
	//
	// Input actions go here
	//
	// ------------------------------------------------------------------

	public function check()
	{
		$topElement = $this->getTopElement();

		$action = new ContainedAction($this->st, $topElement);
		return $action->check();
	}

	public function clear()
	{
		$topElement = $this->getTopElement();

		$action = new ContainedAction($this->st, $topElement);
		return $action->clear();
	}

	public function click()
	{
		$topElement = $this->getTopElement();

		$action = new ContainedAction($this->st, $topElement);
		return $action->click();
	}

	public function select($label)
	{
		$topElement = $this->getTopElement();

		$action = new ContainedAction($this->st, $topElement);
		return $action->select($label);
	}

	public function type($text)
	{
		$topElement = $this->getTopElement();

		$action = new ContainedAction($this->st, $topElement);
		return $action->type($text);
	}

	public function typeSpecial($text)
	{
		$topElement = $this->getTopElement();

		$action = new ContainedAction($this->st, $topElement);
		return $action->typeSpecial($text);
	}

	public function fromElement($element)
	{
		return new ContainedAction($this->st, $element);
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
		$browser = $st->getWebBrowser();
		$env     = $st->getEnvironment();

		// relative, or absolute URL?
		if (substr($url, 0, 1) == '/') {
			// relative URL
			$url = $env->url . $url;
		}

		$log = $st->startAction("[ goto URL: $url ]");
		$browser->open($url);
		$log->endAction();
	}

	public function waitForOverlay($id)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("[ wait for the overlay to appear ]");

		// check for the overlay
		$st->usingTimer()->waitFor(function() use($st, $id) {
			$st->expectsCurrentPage()->has()->elementWithId($id);
		});

		// all done
		$log->endAction();
	}

	public function waitForTitle($title, $failedTitle = null)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("[ check that the the right page has loaded ]");

		// check the title
		$st->usingTimer()->waitFor(function() use($st, $title, $failedTitle) {
			// have we already failed?
			if ($failedTitle && $st->fromCurrentPage()->getTitle() == $failedTitle) {
				return false;
			}

			// we have not failed yet
			$st->expectsCurrentPage()->title($title);
		});

		// all done
		$log->endAction();
	}

	public function waitForTitles($titles)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("[ check that the the right page has loaded ]");

		// check the title
		$st->usingTimer()->waitFor(function() use($st, $titles) {
			$st->expectsCurrentPage()->titles($titles);
		});

		// all done
		$log->endAction();
	}
}