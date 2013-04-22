<?php

namespace DataSift\Storyplayer\Prose;

use Exception;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class UuidDetermine extends ProseActions
{
	public function getUuid($length = 32)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("generate a UUID");

		// generate some random data
		//
		// we're not trying to create a cryptographically-strong amount of
		// entropy here ... just enough to give us different UUIDs if we
		// are called multiple times
		$entropy = '';
		for ($i = 0; $i < 5; $i++) {
			$entropy .= uniqid(mt_rand(), true);
		}

		// use the entropy to generate our hash
		if ($length <= 32) {
			$hash = md5($entropy);
		}
		else if ($length <= 40) {
			$hash = sha1($entropy);
		}

		// chop the hash down to size
		$uuid = substr($hash, 0, $length);

		// log it
		$log->endAction("'{$uuid}'");

		// all done
		return $uuid;
	}
}
