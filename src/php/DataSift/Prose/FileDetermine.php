<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class FileDetermine extends ProseActions
{
	public function getTmpFileName()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("generate a temporary filename");

		// create it
		$filename = tempnam(null, 'storyteller-data-');

		// log it
		$log->endAction("'{$filename}'");

		// all done
		return $filename;
	}
}