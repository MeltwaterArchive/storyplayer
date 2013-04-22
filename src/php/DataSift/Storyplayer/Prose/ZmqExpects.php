<?php

namespace DataSift\Storyplayer\Prose;

use ZMQ;

use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\JsonObject;

class ZmqExpects extends ProseActions
{
	public function canSendmultiNonBlocking($socket, $message)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure ZMQ::sendmulti() does not block");

		// send the data
		$sent = $socket->sendmulti($message, ZMQ::MODE_NOBLOCK);

		// would it have blocked?
		if (!$sent) {
			throw new E5xx_ExpectFailed(__METHOD__, "sendmulti() would not block", "sendmulti() would have blocked");
		}

		// all done
		$log->endAction();
	}
}