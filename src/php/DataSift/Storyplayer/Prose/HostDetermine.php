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

use DataSift\Storyplayer\HostLib;
use DataSift\Storyplayer\OsLib;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * get information about a given host
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class HostDetermine extends HostBase
{
	public function getDetails()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("retrieve details for host '{$this->hostDetails->name}'");

		// we already have details - are they valid?
		if (isset($this->hostDetails->invalidHost) && $this->hostDetails->invalidHost) {
			$msg = "there are no details about host '{$this->hostDetails->name}'";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// return the details
		$log->endAction();
		return $this->hostDetails;
	}

	public function getHostIsRunning()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is host '{$this->hostDetails->name}' running?");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get an object to talk to this host
		$host = HostLib::getHostAdapter($st, $this->hostDetails->type);

		// if the box is running, it should have a status of 'running'
		$result = $host->isRunning($this->hostDetails);

		if (!$result) {
			$log->endAction("host is not running");
			return false;
		}

		// all done
		$log->endAction("host is running");
		return true;
	}

	public function getIpAddress()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get IP address of host '{$this->hostDetails->name}'");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// all done
		$log->endAction("IP address is '{$this->hostDetails->ipAddress}'");
		return $this->hostDetails->ipAddress;
	}

	public function getInstalledPackageDetails($packageName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get details for package '{$packageName}' installed on host '{$this->hostDetails->name}'");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $this->hostDetails->osName);

		// get the information
		$return = $host->getInstalledPackageDetails($this->hostDetails, $packageName);

		// all done
		$log->endAction();
		return $return;
	}

	public function getProcessIsRunning($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is process '{$processName}' running on VM '{$this->hostDetails->name}'?");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $this->hostDetails->osName);

		// get the information
		$return = $host->getProcessIsRunning($this->hostDetails, $processName);

		// did it work?
		if ($return) {
			$log->endAction("'{$processName}' is running");
			return true;
		}

		$log->endAction("'{$processName}' is not running");
		return false;
	}

	public function getPid($processName)
	{
		// alias the storyteller object
		$st = $this->st;

		// log some info to the user
		$log = $st->startAction("get id of process '{$processName}' running on VM '{$this->hostDetails->name}'");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $this->hostDetails->osName);

		// get the information
		$return = $host->getPid($this->hostDetails, $processName);

		// success
		$log->endAction("pid is '{$return}'");
		return $return;
	}

	public function getSshUsername()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get username to use with SSH to host '{$this->hostDetails->name}'");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get the information
		$return = $this->hostDetails->sshUsername;

		// all done
		$log->endAction("username is '{$return}'");
		return $return;
	}

	public function getSshKeyFile()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get key file to use with SSH to host '{$this->hostDetails->name}'");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get the information
		$return = $this->hostDetails->sshKeyFile;

		// all done
		$log->endAction("key file is '{$return}'");
		return $return;
	}
}