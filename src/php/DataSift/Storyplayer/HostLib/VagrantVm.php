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
 * @package   Storyplayer/HostLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\HostLib;

use DataSift\Storyplayer\CommandLib\CommandResult;
use DataSift\Storyplayer\OsLib;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * the things you can do / learn about Vagrant virtual machine
 *
 * @category  Libraries
 * @package   Storyplayer/HostLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class VagrantVm implements SupportedHost
{
	protected $st;

	public function __construct(StoryTeller $st)
	{
		// remember
		$this->st = $st;
	}

	public function createHost($vmDetails, $provisioningVars = array())
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction('provision new VM');

		// make sure we like the provided details
		foreach(array('name', 'osName', 'homeFolder') as $param) {
			if (!isset($vmDetails->$param)) {
				throw new E5xx_ActionFailed(__METHOD__, "missing vmDetails['{$param}']");
			}
		}

		// make sure the folder exists
		$env = $st->getEnvironment();
		if (!isset($env->vagrant)) {
			throw new E5xx_ActionFailed(__METHOD__, "'vagrant' section missing in configuration for current environment");
		}
		if (!isset($env->vagrant->dir)) {
			throw new E5xx_ActionFailed(__METHOD__, "'dir' setting missing from 'vagrant' section of environment config");
		}

		$pathToHomeFolder = $env->vagrant->dir . '/' . $vmDetails->homeFolder;
		if (!is_dir($pathToHomeFolder)) {
			throw new E5xx_ActionFailed(__METHOD__, "VM dir '{$pathToHomeFolder}' does not exist");
		}

		// remember where the Vagrantfile is
		$vmDetails->dir = $pathToHomeFolder;

		// make sure the VM is stopped, if it is running
		$log->addStep("stop vagrant VM in '{$pathToHomeFolder}' if already running", function() use($vmDetails) {
			$command = "vagrant destroy --force";
			$this->runCommandAgainstHostManager($vmDetails, $command);
		});

		// remove any existing hosts table entry
		$st->usingHostsTable()->removeHost($vmDetails->name);

		// let's start the VM
		$command = "cd '{$pathToHomeFolder}' && vagrant up";
		$retVal = 1;
		$log->addStep("create vagrant VM in '{$pathToHomeFolder}'", function() use($command, &$retVal) {
			passthru($command, $retVal);
		});

		// did it work?
		if ($retVal !== 0) {
			$log->endAction("VM failed to start or provision :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// yes it did!!
		//
		// now, we need its IP address
		$ipAddress = $this->determineIpAddress($vmDetails);

		// store the IP address for future use
		$vmDetails->ipAddress = $ipAddress;

		// mark the box as provisioned
		// we will use this in stopBox() to avoid destroying VMs that failed
		// to provision
		$vmDetails->provisioned = true;

		// remember this vm, now that it is running
		$st->usingHostsTable()->addHost($vmDetails->name, $vmDetails);

		// now, let's get this VM into our SSH known_hosts file, to avoid
		// prompting people when we try and provision this VM
		$log->addStep("get the VM into the SSH known_hosts file", function() use($st, $vmDetails) {
			$st->usingHost($vmDetails->name)->runCommand("ls");
		});

		// all done
		$log->endAction("VM successfully started; IP address is {$ipAddress}");
	}

	public function startHost($vmDetails)
	{
		// shorthand
		$st = $this->st;
		$vmName = $vmDetails->name;

		// what are we doing?
		$log = $st->startAction("start VM");

		// is the VM actually running?
		if ($this->isRunning($vmDetails)) {
			// yes it is ... nothing to do
			//
			// we've decided not to treat this as an error ... that might
			// change in a future release
			$log->endAction("VM is already running");
			return;
		}

		// let's start the VM
		$command = "cd '{$vmDetails->dir}' && vagrant up";
		$retVal = 1;
		passthru($command, $retVal);

		// did it work?
		if ($retVal !== 0) {
			$log->endAction("VM failed to start or re-provision :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// yes it did!!
		//
		// now, we need its IP address, which may have changed
		$ipAddress = $this->determineIpAddress($vmDetails);

		// store the IP address for future use
		$vmDetails->ipAddress = $ipAddress;

		// all done
		$log->endAction("VM successfully started; IP address is {$ipAddress}");
	}

	public function stopHost($vmDetails)
	{
		// shorthand
		$st = $this->st;
		$vmName = $vmDetails->name;

		// what are we doing?
		$log = $st->startAction("stop VM");

		// is the VM actually running?
		if (!$this->isRunning($vmDetails)) {
			// we've decided not to treat this as an error ... that might
			// change in a future release
			$log->endAction("VM was already stopped or destroyed");
			return;
		}

		// yes it is ... shut it down
		$command = "cd '{$vmDetails->dir}' && vagrant halt";
		$retVal = 1;
		passthru($command, $retVal);

		// did it work?
		if ($retVal !== 0) {
			$log->endAction("VM failed to shutdown :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done - success!
		$log->endAction("VM successfully stopped");
	}

	public function restartHost($vmDetails)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("restart VM");

		// stop and start
		$this->stopHost($vmDetails);
		$this->startHost($vmDetails);

		// all done
		$log->endAction("VM successfully restarted");
	}

	public function powerOffHost($vmDetails)
	{
		// shorthand
		$st = $this->st;
		$vmName = $vmDetails->name;

		// what are we doing?
		$log = $st->startAction("power off VM");

		// is the VM actually running?
		if (!$this->isRunning($vmDetails)) {
			// we've decided not to treat this as an error ... that might
			// change in a future release
			$log->endAction("VM was already stopped or destroyed");
			return;
		}

		// yes it is ... shut it down
		$command = "cd '{$vmDetails->dir}' && vagrant halt --force";
		$retVal = 1;
		passthru($command, $retVal);

		// did it work?
		if ($retVal !== 0) {
			$log->endAction("VM failed to power off :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done - success!
		$log->endAction("VM successfully powered off");
	}

	public function destroyHost($vmDetails)
	{
		// shorthand
		$st = $this->st;
		$vmName = $vmDetails->name;

		// what are we doing?
		$log = $st->startAction("destroy VM");

		// is the VM actually running?
		if ($this->isRunning($vmDetails)) {
			// yes it is ... shut it down

			$command = "cd '{$vmDetails->dir}' && vagrant destroy --force";
			$retVal = 1;
			passthru($command, $retVal);

			// did it work?
			if ($retVal !== 0) {
				$log->endAction("VM failed to shutdown :(");
				throw new E5xx_ActionFailed(__METHOD__);
			}
		}

		// if we get here, we need to forget about this VM
		$st->usingHostsTable()->removeHost($vmDetails->name);

		// all done
		$log->endAction();
	}

	public function runCommandAgainstHostManager($vmDetails, $command)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run vagrant command '{$command}'");

		// build the command
		$fullCommand = "cd '{$vmDetails->dir}' && $command";

		// run the command
		$returnCode = 0;
		$output = system($fullCommand, $returnCode);

		// build the result
		$result = new CommandResult($returnCode, $output);

		// all done
		$log->endAction("return code was '{$returnCode}'; output was '{$output}'");
		return $result;
	}

	public function runCommandViaHostManager($vmDetails, $command)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run vagrant command '{$command}'");

		// build the command
		$fullCommand = "cd '{$vmDetails->dir}' && vagrant ssh -c \"$command\"";

		// run the command
		$returnCode = 0;
		$output = system($fullCommand, $returnCode);

		// build the result
		$result = new CommandResult($returnCode, $output);

		// all done
		$log->endAction("return code was '{$returnCode}'; output was '{$output}'");
		return $result;
	}

	public function isRunning($vmDetails)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("determine status of Vagrant VM '{$vmDetails->name}'");

		// if the box is running, it should have a status of 'running'
		$command = "vagrant status | grep default | head -n 1 | awk '{print \$2'}";
		$result  = $this->runCommandAgainstHostManager($vmDetails, $command);

		if ($result->output != 'running') {
			$log->endAction("VM is not running; state is '{$result->output}'");
			return false;
		}

		// all done
		$log->endAction("VM is running");
		return true;
	}

	public function determineIpAddress($vmDetails)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("determine IP address of Vagrant VM '{$vmDetails->name}'");

		// create an adapter to talk to the host operating system
		$host = OsLib::getHostAdapter($st, $vmDetails->osName);

		// get the IP address
		$ipAddress = $host->determineIpAddress($vmDetails, $this);

		// all done
		$log->endAction("IP address is '{$ipAddress}'");
		return $ipAddress;
	}
}