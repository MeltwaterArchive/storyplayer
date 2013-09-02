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

use DataSift\Storyplayer\CommandLib\CommandResult;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * do things with the UNIX shell (such as start background processes)
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingShell extends Prose
{
	public function runCommand($command)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run command: {$command}");

		// run the command
		$returnCode = null;
		$lines = array();
		exec($command, $lines, $returnCode);
		$output = implode(PHP_EOL, $lines);

		// all done
		$log->endAction("return code was '{$returnCode}'; output was '{$output}'");

		$return = new CommandResult($returnCode, $output);
		return $return;
	}

	public function startInScreen($processName, $commandLine)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run process '{$processName}' ({$commandLine}) in the background");

		// build up the process data structure
		$processDetails = new BaseObject();

		// remember how users will refer to this
		$processDetails->processName = $processName;

		// we need to create a unique screen name
		$processDetails->screenName = $processName . '_' . date('YmdHis');

		// build up our command to run
		$processDetails->commandLine = "screen -L -d -m -S " . $processDetails->screenName
		         . ' bash -c "' . $commandLine . '"';

		// run our command
		//
		// this creates a detached screen session called $appData->screenName
		$log->addStep("run commandline '{$processDetails->commandLine}'", function() use($processDetails) {
			passthru($processDetails->commandLine);
		});

		// find the PID of the screen session, for future use
		$processDetails->pid = trim(`screen -ls | grep {$processDetails->screenName} | awk -F. '{print $1}'`);

		// did the process start, or has it already terminated?
		if (empty($processDetails->pid)) {
			$log->endAction("process failed to start");
			throw new E5xx_ActionFailed(__METHOD__, "failed to start process '{$processName}'");
		}

		// remember this process
		$st->usingProcessesTable()->addProcess($processDetails);

		// all done
		$log->endAction("process running as '{$processDetails->screenName}' ({$processDetails->pid})");
	}

	public function stopInScreen($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop process '{$processName}'");

		// get the process details
		$processDetails = $st->fromShell()->getScreenSessionDetails($processName);

		// stop the process
		$st->usingShell()->stopProcess($processDetails->pid);

		// remove the process from the processes table
		$st->usingProcessesTable()->removeProcess($processDetails->pid);

		// all done
		$log->endAction();
	}

	public function stopAllScreens()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop all running screen processes");

		// get the app details
		$processes = $st->fromShell()->getAllScreenSessions();

		// stop the process
		foreach ($processes as $processDetails) {
			$st->usingShell()->stopProcess($processDetails->pid);
			$st->usingProcessesTable()->removeProcess($processDetails->pid);
		}

		// all done
		$log->endAction();
	}

	public function stopProcess($pid)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop process '{$pid}'");

		// is the process running at all?
		if (!$st->fromShell()->getIsProcessRunning($pid)) {
			$log->endAction("process is not running");
			return;
		}

		// yes it is, so stop it
		// send a TERM signal to the screen session
		$log->addStep("send SIGTERM to process '{$pid}'", function() use ($pid) {
			posix_kill($pid, SIGTERM);
		});

		// has this worked?
		$log->addStep("wait for process to terminate", function() use($st, $pid) {
			for($i = 0; $i < 2; $i++) {
				if ($st->fromShell()->getIsProcessRunning($pid)) {
					// process still exists
					sleep(1);
				}
			}
		});

		if (posix_kill($pid, 0)) {
			$log->addStep("send SIGKILL to process '{$pid}'", function() use($pid) {
				posix_kill($pid, SIGKILL);
				sleep(1);
			});
		}

		// success?
		if ($st->fromShell()->getIsProcessRunning($pid)) {
			$log->endAction("process is still running :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done
		$log->endAction("process has finished");
	}
}
