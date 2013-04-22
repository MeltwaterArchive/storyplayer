<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\JsonObject;

class ShellExpects extends ProseActions
{
	public function isRunningInScreen($screenName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure process '{$screenName}' is running");

		// get the details
		$appData = $st->fromShell()->getScreenSessionDetails($screenName);

		// is this process still running?
		if (!$st->fromShell()->getIsProcessRunning($appData->pid)) {
			$log->endAction("process is not running");
			throw new E5xx_ExpectFailed(__METHOD__, "process {$appData->pid} running", "process {$appData->pid} not running");
		}

		// all done
		$log->endAction();
		return true;
	}
}