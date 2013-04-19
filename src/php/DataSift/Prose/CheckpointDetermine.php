<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\StoryTeller\ProseLib\E5xx_ActionFailed;

class CheckpointDetermine extends ProseActions
{
	public function get($fieldName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("[ get value of checkpoint field '{$fieldName}']");

		// get the checkpoint
		$checkpoint = $st->getCheckpoint();

		// does the value exist?
		if (!isset($checkpoint->$fieldName)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done
		$log->endAction();

		return $checkpoint->$fieldName;
	}
}