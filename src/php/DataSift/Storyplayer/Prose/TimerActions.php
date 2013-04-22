<?php

namespace DataSift\Storyplayer\Prose;

use Exception;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;
use DataSift\Stone\TimeLib\DateInterval;

class TimerActions extends ProseActions
{
	public function waitFor($callback, $timeout = 5) {
		$now = time();
		$end = $now + $timeout;

		while ($now < $end) {
			try {
				$log = $this->st->startAction("[ polling ]");
				$result = $callback();

				// if we get here, the actions inside the callback
				// may have worked
				if ($result || $result === null) {
					$log->endAction();
					return;
				}
			}
			catch (Exception $e) {
				// do nothing
				$log->endAction();
			}

			// has the action actually failed?
			if (isset($result) && !$result) {
				$log->endAction();
				throw new E5xx_ActionFailed(__METHOD__);
			}

			// we need to give the browser time to catch up
			sleep(1);

			// update the timeout
			$now = time();
		}

		// if we get here, then the timeout happened
		throw new E5xx_ActionFailed('timer()->waitFor()');
	}

	public function waitWhile($callback, $timeout = 5) {
		$now = time();
		$end = $now + $timeout;

		while ($now < $end) {
			try {
				$remaining = $end - $now;
				$log = $this->st->startAction("[ polling; remaining time is {$remaining} seconds ]");
				$result = $callback();

				// if we get here, the actions inside the callback
				// may have worked
				$log->endAction();
			}
			catch (Exception $e) {
				// do nothing
				$log->endAction();
				return;
			}
			sleep(1);

			$now = time();
		}

		// if we get here, then the timeout happened
		throw new E5xx_ActionFailed('timer()->waitWhile()');
	}

	public function wait($timeout = 'PT01M', $reason = "waiting for everything to catch up") {
		$interval = new DateInterval($timeout);
		$seconds  = $interval->getTotalSeconds();

		Log::write(Log::LOG_DEBUG, "sleeping for {$timeout}; reason is: '{$reason}'");
		sleep($seconds);
		Log::write(Log::LOG_DEBUG, "finished sleeping");
	}
}