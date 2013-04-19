<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;

class CheckpointExpects extends ProseActions
{
	public function equals($expected)
	{
		// shorthand
		$st = $this->st;
		$fieldName = $this->args[0];

		// what are we doing?
		$log = $st->startAction("[ checkpoint field '{$fieldName}' must contain '{$expected}' ]");

		// does this field exist?
		$checkpoint = $st->getCheckpoint();
		if (!isset($checkpoint->$fieldName)) {
			throw new E5xx_ExpectFailed(__METHOD__, "field {$field} exists in checkpoint", "field does not exist");
		}

		// extract the actual value
		$actual = $st->getCheckpoint()->$fieldName;

		if (is_string($expected)) {
			if ($expected !== $actual) {
				throw new E5xx_ExpectFailed(__METHOD__, $expected, $actual);
			}
		}
		else if (is_integer($expected) || is_float($expected)) {
			if ($expected != $actual) {
				throw new E5xx_ExpectFailed(__METHOD__, $expected, $actual);
			}
		}
		else {
			throw new E5xx_ExpectFailed(__METHOD__ , $expected, "Unsupported data type for \$expected");
		}

		// all done
		$log->endAction();
	}

	public function exists()
	{
		// shorthand
		$st = $this->st;
		$fieldName = $this->args[0];

		// what are we doing?
		$log = $st->startAction("[ checkpoint field '{$fieldName}' must exist ]");

		if (!isset($st->getCheckpoint()->$fieldName)) {
			throw new E5xx_ExpectFailed(__METHOD__, 'field exists', 'field does not exist');
		}

		// all done
		$log->endAction();
	}

	/*
	public function hasMatchingElements($expected)
	{
		// we expect all of the elements named in $expected to exist
		// in $actual

		foreach ($expected as $value) {
			if (!in_array($value, $actual)) {
				throw new E5xx_ExpectFailed(__METHOD__, $value, null);
			}
		}
	}
	*/
}