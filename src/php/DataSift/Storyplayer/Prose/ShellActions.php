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
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\Prose;
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
class ShellActions extends Prose
{
	public function runCommand($command)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run command: {$command}");

		// run the command
		$returnCode = null;
		$output = system($command, $returnCode);

		// all done
		$log->endAction("return code was '{$returnCode}'; output was '{$output}'");

		$return = new CommandResult($returnCode, $output);
		return $return;
	}

	public function startInScreen($screenName, $commandLine)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run process '{$screenName}' ({$commandLine}) in the background");

		// we're going to save some info in the environment
		$env = $st->getEnvironment();

		// this is the data that we're going to store
		$appData = new BaseObject();
		if (!isset($env->screen)) {
			$env->screen = new BaseObject();
		}
		if (!isset($env->screen->sessions)) {
			$env->screen->sessions = new BaseObject();
		}
		$env->screen->sessions->$screenName = $appData;

		// we need to create a unique screen name
		$appData->screenName = $screenName . '_' . date('YmdHis');

		// build up our command to run
		$appData->commandLine = "screen -d -m -S " . $appData->screenName
		         . ' bash -c "' . $commandLine . ' && sleep 5"';

		// run our command
		//
		// this creates a detached screen session called $appData->screenName
		$log->addStep("run commandline '{$appData->commandLine}'", function() use($appData) {
			passthru($appData->commandLine);
		});

		// find the PID of the screen session, for future use
		$appData->pid = trim(`screen -ls | grep {$appData->screenName} | awk -F. '{print $1}'`);

		// did the process start, or has it already terminated?
		if (empty($appData->pid)) {
			$log->endAction("process failed to start");
			throw new E5xx_ActionFailed(__METHOD__, "failed to start process '{$screenName}'");
		}

		// all done
		$log->endAction("process running as '{$appData->screenName}' ({$appData->pid})");
	}

	public function stopInScreen($screenName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop process '{$screenName}'");

		// get the app details
		$appData = $st->fromShell()->getScreenSessionDetails($screenName);

		// stop the process
		$st->usingShell()->stopProcess($appData->pid);

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
		$apps = $st->fromShell()->getAllScreenSessions();

		// stop the process
		foreach ($apps as $appData) {
			$st->usingShell()->stopProcess($appData->pid);
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