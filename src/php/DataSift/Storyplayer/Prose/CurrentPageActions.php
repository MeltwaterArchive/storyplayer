<?php

namespace DataSift\Storyplayer\Prose;

use Exception;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;

class CurrentPageActions extends BrowserActions
{
	public function clickCreateStream()
	{
		// shorthand
		$st	= $this->st;

		// what are we doing?
		$log = $st->startAction("click the 'Create Stream' button");

		// make sure that we *have* a create stream button
		$st->expectsCurrentPage()->hasCreateStream();

		// click it
		$st->usingCurrentPage()->click()->linkWithText('Create Stream');

		// all done
		$log->endAction();
	}

	public function gotoNextPage()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("go to the 'Next Page' if there is one");

		if ($st->fromCurrentPage()->has()->linkWithTitle("Page Next")) {
			$st->usingCurrentPage()->click()->linkWithTitle("Page Next");

			// wait for the animation to happen
			sleep(1);
		}

		// all done
		$log->endAction();
	}

	public function switchToTab($tabName, $sleepFor = 0)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("switch to the $tabName tab");

		// click the tab
		$this->click()->linkWithText($tabName);

		// wait for the animation
		if ($sleepFor > 0) {
			sleep($sleepFor);
		}

		// make sure it worked
		$st->expectsCurrentPage()->selectedTabIs($tabName);

		// all done
		$log->endAction();
	}
}