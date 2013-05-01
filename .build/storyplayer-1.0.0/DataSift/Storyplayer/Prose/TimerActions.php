<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Prose;

use Exception;

use DataSift\Storyplayer\ProseLib\Prose;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Stone\TimeLib\DateInterval;

/**
 * perform delayed actions
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TimerActions extends Prose
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

		$log = $this->st->startAction("sleeping for {$timeout}; reason is: '{$reason}'");
		sleep($seconds);
		$log->endAction("finished sleeping");
	}
}