<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;

class ReportingActions extends ProseActions
{
	public function reportNotRequired()
	{
		// shorthand
		$st  = $this->st;

		// what are we doing?
		$log = $st->startAction("this phase is not required by this story");

		// all done
		$log->endAction();
	}

	public function reportShouldAlwaysSucceed()
	{
		// shorthand
		$st  = $this->st;

		// what are we doing?
		$log = $st->startAction("this story is expected to always succeed");

		// all done
		$log->endAction();
	}

}