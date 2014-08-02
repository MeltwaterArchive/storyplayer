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
		$log = $st->startAction("run command '{$command}' on host '{$this->hostDetails->name}'");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $this->hostDetails->osName);

		// run the command in the guest operating system
		$result = $host->runCommand($this->hostDetails, $command);

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
		$log = $st->startAction("run command '{$command}' as user '{$user}' on host '{$this->hostDetails->name}'");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $this->hostDetails->osName);

		// make a copy of the hostDetails, so that we can override them
		$myHostDetails = clone $this->hostDetails;
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
		$log = $st->startAction("run command '{$command}' on host '{$this->hostDetails->name}'");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $this->hostDetails->osName);

		// run the command in the guest operating system
		$result = $host->runCommand($this->hostDetails, $command);

		// all done
		$log->endAction();
		return $result;
	}

	public function runCommandAsUserAndIgnoreErrors($command, $user)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run command '{$command}' as user '{$user}' on host '{$this->hostDetails->name}'");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $this->hostDetails->osName);

		// make a copy of the hostDetails, so that we can override them
		$myHostDetails = clone $this->hostDetails;
		$myHostDetails->sshUsername = $user;

		// run the command in the guest operating system
		$result = $host->runCommand($myHostDetails, $command);

		// all done
		$log->endAction();
		return $result;
	}
}