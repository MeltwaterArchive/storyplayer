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
 * @package   Storyplayer/OsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\OsLib;

use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Storyplayer\HostLib\SupportedHost;

/**
 * get information about vagrant
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class Centos5 extends OsBase
{
	public function determineIpAddress($hostDetails, SupportedHost $host)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("query " . basename(__CLASS__) . " for IP address");

		// how do we do this?
		foreach (array('eth1', 'eth0') as $iface) {
			$command = "/sbin/ifconfig {$iface} | grep 'inet addr' | awk -F : '{print \\\$2}' | awk '{print \\\$1}'";
			$result = $host->runCommandViaHostManager($hostDetails, $command);

			if ($result->didCommandSucceed()) {
				$ipAddress = trim($result->output);
				$log->endAction("IP address is '{$ipAddress}'");
				return $ipAddress;
			}
		}

		// if we get here, we do not know what the IP address is
		$msg = "could not determine IP address";
		$log->endAction($msg);
		throw new E5xx_ActionFailed(__METHOD__, $msg);
	}

	public function getInstalledPackageDetails($hostDetails, $packageName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get details for package '{$packageName}' installed in host '{$hostDetails->name}'");

		// get the details
		$sshClient = $this->getSshClient($hostDetails);
		$command   = "sudo yum list installed {$packageName} | grep '{$packageName}' | awk '{print \\\$1,\\\$2,\\\$3}'";
		$result    = $sshClient->runCommand($command);

		// any luck?
		if ($result->didCommandFail()) {
			$log->endAction("could not get details ... package not installed?");
			return new BaseObject();
		}

		// study the output
		$parts = explode(' ', $result->output);
		if (count($parts) < 3) {
			$log->endAction("could not get details ... package not installed?");
			return new BaseObject();
		}

		// we have some information to return
		$return = new BaseObject();
		$return->name = $parts[0];
		$return->version = $parts[1];
		$return->repo = $parts[2];

		// all done
		$log->endAction();
		return $return;
	}

	public function getProcessIsRunning($hostDetails, $processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is process '{$processName}' running on VM '{$hostDetails->name}'?");

		// SSH in and have a look
		$sshClient = $this->getSshClient($hostDetails);
		$command   = "ps -ef | awk '{ print \\\$8 }' | grep '[" . $processName{0} . "]" . substr($processName, 1) . "'";
		$result    = $sshClient->runCommand($command);

		// what did we find?
		if ($result->didCommandFail() || empty($result->output)) {
			$log->endAction("not running");
			return false;
		}

		// success
		$log->endAction("is running");
		return true;
	}

	public function getPid($hostDetails, $processName)
	{
		// alias the storyteller object
		$st = $this->st;

		// log some info to the user
		$log = $st->startAction("get memory usage for process '{$processName}' running on VM '{$hostDetails->name}'");

		// run the command to get the process id
 		$sshClient = $this->getSshClient($hostDetails);
		$command   = "ps -ef | grep '[" . $processName{0} . "]" . substr($processName, 1) . "' | awk '{print \\\$2}'";
		$result    = $sshClient->runCommand($command);

		// check that we got something
		if ($result->didCommandFail() || empty($result->output)) {
			$log->endAction("could not get pid ... is the process running?");
			return 0;
		}

		// check that we found exactly one process
		$pids = explode("\n", $result->output);
		if (count($pids) != 1) {
			$log->endAction("found more than one process but expecting only one ... is this correct?");
			return 0;
		}

		// we can now reason that we have the correct pid
		$pid = $pids[0];

		// all done
		$log->endAction("{$pid}");
		return $pid;
	}
}