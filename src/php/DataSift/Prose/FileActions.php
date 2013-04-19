<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\ProseActions;

class FileActions extends ProseActions
{
	public function removeFile($filename)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("delete the file '{$filename}'");

		// remove the file
		if (file_exists($filename)) {
			unlink($filename);
		}
		else {
			$log->endAction("file not found");
		}

		// all done
		$log->endAction();
		return $this;
	}
}