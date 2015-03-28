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

namespace Prose;

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
		// what are we doing?
		$log = usingLog()->startAction("run command '{$command}' on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

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
		// what are we doing?
		$log = usingLog()->startAction("run command '{$command}' as user '{$user}' on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

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
		// what are we doing?
		$log = usingLog()->startAction("run command '{$command}' on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

		// run the command in the guest operating system
		$result = $host->runCommand($hostDetails, $command);

		// all done
		$log->endAction();
		return $result;
	}

	public function runCommandAsUserAndIgnoreErrors($command, $user)
	{
		// what are we doing?
		$log = usingLog()->startAction("run command '{$command}' as user '{$user}' on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

		// make a copy of the hostDetails, so that we can override them
		$myHostDetails = clone $hostDetails;
		$myHostDetails->sshUsername = $user;

		// run the command in the guest operating system
		$result = $host->runCommand($myHostDetails, $command);

		// all done
		$log->endAction();
		return $result;
	}

	public function startInScreen($sessionName, $commandLine)
	{
		// what are we doing?
		$log = usingLog()->startAction("start screen session '{$sessionName}' ({$commandLine}) on host '{$this->args[0]}'");

		// do we already have this session running on the host?
		expectsHost($this->args[0])->screenIsNotRunning($sessionName);

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// build up our command to run
		$commandLine = 'screen -L -d -m -S "' . $sessionName . '" bash -c "' . $commandLine . '"';

		// run our command
		//
		// this creates a detached screen session called $sessionName
		$this->runCommand($commandLine);

		// find the PID of the screen session, for future use
		$sessionDetails = fromHost($this->args[0])->getScreenSessionDetails($sessionName);

		// did the process start, or has it already terminated?
		if (empty($sessionDetails->pid)) {
			$log->endAction("session failed to start, or command exited quickly");
			throw new E5xx_ActionFailed(__METHOD__, "failed to start session '{$sessionName}'");
		}

		// all done
		$log->endAction("session running as PID {$sessionDetails->pid}");
	}

	public function stopInScreen($sessionName)
	{
		// what are we doing?
		$log = usingLog()->startAction("stop screen session '{$sessionName}' on host '{$this->args[0]}'");

		// get the process details
		$processDetails = fromHost($this->args[0])->getScreenSessionDetails($sessionName);

		// stop the process
		usingHost($this->args[0])->stopProcess($processDetails->pid);

		// all done
		$log->endAction();
	}

	public function stopAllScreens()
	{
		// what are we doing?
		$log = usingLog()->startAction("stop all running screen sessions on host '{$this->args[0]}'");

		// get the app details
		$processes = fromHost($this->args[0])->getAllScreenSessions();

		// stop the process
		foreach ($processes as $processDetails) {
			usingHost($this->args[0])->stopProcess($processDetails->pid);
			usingProcessesTable()->removeProcess($this->args[0], $processDetails);
		}

		// all done
		$log->endAction("stopped " . count($processes) . " session(s)");
	}

	public function stopProcess($pid, $grace = 5)
	{
		// what are we doing?
		$log = usingLog()->startAction("stop process '{$pid}' on host '{$this->args[0]}'");

		// is the process running at all?
		if (!fromHost($this->args[0])->getPidIsRunning($pid)) {
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
				usingHost($this->args[0])->runCommand("kill {$pid}");
			}
		});

		// has this worked?
		$isStopped = $log->addStep("wait for process to terminate", function() use($pid, $grace, $log) {
			for($i = 0; $i < $grace; $i++) {
				if (!fromHost($this->args[0])->getPidIsRunning($pid)) {
					return true;
				}

				// process still exists
				sleep(1);
			}

			return false;
		});

		// did the process stop?
		if ($isStopped) {
			$log->endAction();
			return;
		}

		$log->addStep("send SIGKILL to process '{$pid}'", function() use($pid) {
			if ($this->getIsLocalhost()) {
				posix_kill($pid, SIGKILL);
			}
			else {
				usingHost($this->args[0])->runCommand("kill -9 {$pid}");
			}
			sleep(1);
		});

		// success?
		if (fromHost($this->args[0])->getProcessIsRunning($pid)) {
			$log->endAction("process is still running :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done
		$log->endAction("process has finished");
	}

	public function uploadFile($sourceFilename, $destFilename)
	{
		// what are we doing?
		$log = usingLog()->startAction("upload file '{$sourceFilename}' to '{$this->args[0]}':'{$destFilename}'");

		// does the source file exist?
		if (!is_file($sourceFilename)) {
			$log->endAction("file '{$sourceFilename}' not found :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

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