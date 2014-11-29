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

use DataSift\Storyplayer\OsLib;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * do things with vagrant
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingHost extends HostBase
{
	public function runCommand($command)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run command '{$command}' on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $hostDetails->osName);

		// run the command in the guest operating system
		$result = $host->runCommand($hostDetails, $command);

		// did the command succeed?
		if ($result->didCommandFail()) {
			$msg = "command failed with return code '{$result->returnCode}' and output '{$result->output}'";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// all done
		$log->endAction();
		return $result;
	}

	public function runCommandAsUser($command, $user)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run command '{$command}' as user '{$user}' on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $hostDetails->osName);

		// make a copy of the hostDetails, so that we can override them
		$myHostDetails = clone $hostDetails;
		$myHostDetails->sshUsername = $user;

		// run the command in the guest operating system
		$result = $host->runCommand($myHostDetails, $command);

		// did the command succeed?
		if ($result->didCommandFail()) {
			$msg = "command failed with return code '{$result->returnCode}' and output '{$result->output}'";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// all done
		$log->endAction();
		return $result;
	}

	public function runCommandAndIgnoreErrors($command)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run command '{$command}' on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $hostDetails->osName);

		// run the command in the guest operating system
		$result = $host->runCommand($hostDetails, $command);

		// all done
		$log->endAction();
		return $result;
	}

	public function runCommandAsUserAndIgnoreErrors($command, $user)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run command '{$command}' as user '{$user}' on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $hostDetails->osName);

		// make a copy of the hostDetails, so that we can override them
		$myHostDetails = clone $hostDetails;
		$myHostDetails->sshUsername = $user;

		// run the command in the guest operating system
		$result = $host->runCommand($myHostDetails, $command);

		// all done
		$log->endAction();
		return $result;
	}

	public function startInScreen($processName, $commandLine)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run process '{$processName}' ({$commandLine}) in the background on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// build up the process data structure
		$processDetails = new BaseObject();

		// remember where we are running this
		$processDetails->hostname = $hostDetails->name;

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
		$this->runCommand($processDetails->commandLine);

		// find the PID of the screen session, for future use
		$cmd = "screen -ls | grep {$processDetails->screenName} | awk -F. '{print $1}'";
		$result = $this->runCommand($cmd);
		$processDetails->pid = trim(rtrim($result->output));

		// did the process start, or has it already terminated?
		if (empty($processDetails->pid)) {
			$log->endAction("process failed to start");
			throw new E5xx_ActionFailed(__METHOD__, "failed to start process '{$processName}'");
		}

		// remember this process
		$st->usingProcessesTable()->addProcess($this->args[0], $processDetails);

		// all done
		$log->endAction("process running as '{$processDetails->screenName}' ({$processDetails->pid})");
	}

	public function stopInScreen($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop screen process '{$processName}' on host '{$this->args[0]}'");

		// get the process details
		$processDetails = $st->fromShell()->getScreenSessionDetails($processName);

		// stop the process
		$st->usingHost($this->args[0])->stopProcess($processDetails->pid);

		// remove the process from the processes table
		$st->usingProcessesTable()->removeProcess($this->args[0],$processDetails);

		// all done
		$log->endAction();
	}

	public function stopAllScreens()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop all running screen processes on host '{$this->args[0]}'");

		// get the app details
		$processes = $st->fromHost($this->args[0])->getAllScreenSessions();

		// stop the process
		foreach ($processes as $processDetails) {
			$st->usingHost($this->args[0])->stopProcess($processDetails->pid);
			$st->usingProcessesTable()->removeProcess($this->args[0], $processDetails);
		}

		// all done
		$log->endAction();
	}

	public function stopProcess($pid)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop process '{$pid}' on host '{$this->args[0]}'");

		// is the process running at all?
		if (!$st->fromHost($this->args[0])->getPidIsRunning($pid)) {
			$log->endAction("process is not running");
			return;
		}

		// yes it is, so stop it
		// send a TERM signal to the screen session
		$log->addStep("send SIGTERM to process '{$pid}'", function() use ($pid) {
			if ($this->getIsLocalhost()) {
				posix_kill($pid, SIGTERM);
			}
			else {
				$st->usingHost($this->args[0])->runCommand("kill {$pid}");
			}
		});

		// has this worked?
		$log->addStep("wait for process to terminate", function() use($st, $pid) {
			for($i = 0; $i < 2; $i++) {
				if ($st->fromHost($this->args[0])->getProcessIsRunning($pid)) {
					// process still exists
					sleep(1);
				}
			}
		});

		if (posix_kill($pid, 0)) {
			$log->addStep("send SIGKILL to process '{$pid}'", function() use($pid) {
				if ($this->getIsLocalhost()) {
					posix_kill($pid, SIGKILL);
				}
				else {
					$this->usingHost($this->args[0])->runCommand("kill -9 {$pid}");
				}
				sleep(1);
			});
		}

		// success?
		if ($st->fromHost($this->args[0])->getProcessIsRunning($pid)) {
			$log->endAction("process is still running :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done
		$log->endAction("process has finished");
	}

	public function uploadFile($sourceFilename, $destFilename)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("upload file '{$sourceFilename}' to '{$this->args[0]}':'{$destFilename}'");

		// does the source file exist?
		if (!is_file($sourceFilename)) {
			$log->endAction("file '{$sourceFilename}' not found :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $hostDetails->osName);

		// upload the file
		$result = $host->uploadFile($hostDetails, $sourceFilename, $destFilename);

		// did the command used to upload succeed?
		if ($result->didCommandFail()) {
			$msg = "upload failed with return code '{$result->returnCode}' and output '{$result->output}'";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// all done
		$log->endAction();
		return $result;
	}
}