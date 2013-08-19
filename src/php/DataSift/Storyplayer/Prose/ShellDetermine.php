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

use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * get information from the UNIX shell
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ShellDetermine extends Prose
{
	public function getIsScreenRunning($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("check if process '{$processName}' is still running in screen");

		// get the details
		$appData = $st->fromShell()->getScreenSessionDetails($processName);

		// is it still running?
		$isRunning = $st->fromShell()->getIsProcessRunning($appData->pid);

		// all done
		if ($isRunning) {
			$log->endAction("still running");
			return true;
		}
		else {
			$log->endAction("not running");
			return false;
		}
	}

	public function getIsProcessRunning($pid)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is process w/ PID '{$pid}' running?");

		// do we *have* a valid PID?
		if (empty($pid)) {
			$log->endAction("process has no PID; did not start?");
			return false;
		}

		// is the process running at all?
		if (!posix_kill($pid, 0)) {
			$log->endAction("process is not running");
			return false;
		}

		$log->endAction("process is running");
		return true;
	}

	public function getScreenSessionDetails($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get details about process '{$processName}'");

		// are there any details?
		$processesTable = $st->fromProcessesTable()->getProcessesTable();
		foreach ($processesTable as $processDetails) {
			if (isset($processDetails->processName) && $processDetails->processName == $processName) {
				// success!
				$log->endAction();
				return $processDetails;
			}
		}

		// we don't have this process
		$msg = "no process with the screen name '{$processName}'";
		$log->endAction($msg);
		throw new E5xx_ActionFailed(__METHOD__, $msg);
	}

	public function getAllScreenSessions()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get details about all screen processes");

		// our return data
		$return = array();

		// are there any details?
		$processesTable = $st->fromProcessesTable()->getProcessesTable();
		foreach ($processesTable as $processDetails) {
			if (isset($processDetails->screenName)) {
				$return[] = $processDetails;
			}
		}

		// all done
		$log->endAction("found " . count($return) . " screen process(es)");

		// all done
		return $return;
	}
}