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
class FromHost extends HostBase
{
	public function getDetails()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("retrieve details for host '{$this->args[0]}'");

		// get the host details
		$hostDetails = $this->getHostDetails();

		// we already have details - are they valid?
		if (isset($hostDetails->invalidHost) && $hostDetails->invalidHost) {
			$msg = "there are no details about host '{$hostDetails->name}'";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// return the details
		$log->endAction();
		return $hostDetails;
	}

	public function getHostIsRunning()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is host '{$this->args[0]}' running?");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = HostLib::getHostAdapter($st, $hostDetails->type);

		// if the box is running, it should have a status of 'running'
		$result = $host->isRunning($hostDetails);

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
		$log = $st->startAction("get IP address of host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// all done
		$log->endAction("IP address is '{$hostDetails->ipAddress}'");
		return $hostDetails->ipAddress;
	}

	public function getInstalledPackageDetails($packageName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get details for package '{$packageName}' installed on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $hostDetails->osName);

		// get the information
		$return = $host->getInstalledPackageDetails($hostDetails, $packageName);

		// all done
		$log->endAction();
		return $return;
	}

	public function getPidIsRunning($pid)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is process PID '{$pid}' running on VM '{$this->args[0]}'?");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $hostDetails->osName);

		// get the information
		$return = $host->getPidIsRunning($hostDetails, $pid);

		// did it work?
		if ($return) {
			$log->endAction("'{$pid}' is running");
			return true;
		}

		$log->endAction("'{$pid}' is not running");
		return false;
	}

	public function getProcessIsRunning($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is process '{$processName}' running on VM '{$this->args[0]}'?");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $hostDetails->osName);

		// get the information
		$return = $host->getProcessIsRunning($hostDetails, $processName);

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
		$log = $st->startAction("get id of process '{$processName}' running on VM '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $hostDetails->osName);

		// get the information
		$return = $host->getPid($hostDetails, $processName);

		// success
		$log->endAction("pid is '{$return}'");
		return $return;
	}

	public function getSshUsername()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get username to use with SSH to host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get the information
		$return = $hostDetails->sshUsername;

		// all done
		$log->endAction("username is '{$return}'");
		return $return;
	}

	public function getSshKeyFile()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get key file to use with SSH to host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// get the information
		$return = $hostDetails->sshKeyFile;

		// all done
		$log->endAction("key file is '{$return}'");
		return $return;
	}

	public function getIsScreenRunning($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("check if process '{$processName}' is still running in screen");

		// get the details
		$appData = $st->fromHost($this->args[0])->getScreenSessionDetails($processName);

		// is it still running?
		$isRunning = $st->fromHost($this->args[0])->getPidIsRunning($appData->pid);

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

	public function getAppSettings($appName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get settings for '{$appName}' from host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// do we have any app settings?
		if (!isset($hostDetails->appSettings, $hostDetails->appSettings->$appName)) {
			$log->endAction("... setting does not exist :(");
			throw new E4xx_ActionFailed(__METHOD__);
		}

		// yes we do
		$value = $hostDetails->appSettings->$appName;

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("settings for '{$appName}' are '{$logValue}'");

		// all done
		return $value;
	}

	public function getAppSetting($appName, $settingName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get $settingName for '{$appName}' from host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// do we have any app settings?
		if (!isset($hostDetails->appSettings, $hostDetails->appSettings->$appName, $hostDetails->appSettings->$settingName)) {
			$log->endAction("... setting does not exist :(");
			throw new E4xx_ActionFailed(__METHOD__);
		}

		// yes we do
		$value = $hostDetails->appSettings->$appName->$settingName;

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("setting for '{$appName}' is '{$logValue}'");

		// all done
		return $value;
	}
}