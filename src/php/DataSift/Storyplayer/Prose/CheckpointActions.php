<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;

use DataSift\Stone\DataLib\DataPrinter;

class CheckpointActions extends ProseActions
{
	public function set($fieldName, $value)
	{
		// shorthand
		$st = $this->st;

		// convert $value into something that can appear in our logs
		$convertor = new DataPrinter();
		$printable = $convertor->convertToString($value);

		// what are we doing?
		$log = $st->startAction("[ set checkpoint field '{$fieldName}' to '{$printable}' ]");

		// get the checkpoint
		$checkpoint = $st->getCheckpoint();

		// set the value
		$checkpoint->$fieldName = $value;

		// all done
		$log->endAction();
	}
}