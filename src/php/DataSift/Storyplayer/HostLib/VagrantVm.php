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
use DataSift\Storyplayer\CommandLib\CommandRunner;
use DataSift\Storyplayer\OsLib;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\ObjectLib\BaseObject;
use Prose\E5xx_ActionFailed;

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
	/**
	 *
	 * @var StoryTeller
	 */
	protected $st;

	/**
	 *
	 * @param StoryTeller $st
	 */
	public function __construct(StoryTeller $st)
	{
		// remember
		$this->st = $st;
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @param  array $provisioningVars
	 * @return void
	 */
	public function createHost($vmDetails, $provisioningVars = array())
	{
		// what are we doing?
		$log = usingLog()->startAction('provision new VM');

		// make sure we like the provided details
		foreach(array('name', 'osName', 'homeFolder') as $param) {
			if (!isset($vmDetails->$param)) {
				throw new E5xx_ActionFailed(__METHOD__, "missing vmDetails['{$param}']");
			}
		}

		// make sure the folder exists
		$config = $this->st->getConfig();
		if (!isset($config->storyplayer->modules->vagrant)) {
			throw new E5xx_ActionFailed(__METHOD__, "'vagrant' section missing in your storyplayer.json config file");
		}
		if (!isset($config->storyplayer->modules->vagrant->dir)) {
			throw new E5xx_ActionFailed(__METHOD__, "'dir' setting missing from 'vagrant' section of your storyplayer.json config file");
		}

		$pathToHomeFolder = $config->storyplayer->modules->vagrant->dir . '/' . $vmDetails->homeFolder;
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
		usingHostsTable()->removeHost($vmDetails->hostId);

		// remove any roles
		usingRolesTable()->removeHostFromAllRoles($vmDetails->hostId);

		// let's start the VM
		$command = "vagrant up";
		$result = $log->addStep("create vagrant VM in '{$pathToHomeFolder}'", function() use($command, $vmDetails) {
			return $this->runCommandAgainstHostManager($vmDetails, $command);
		});

		// did it work?
		if ($result->returnCode !== 0) {
			$log->endAction("VM failed to start or provision :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// yes it did!!

		// now, we need to know how to contact this VM
		$vmDetails->ipAddress = $this->determineIpAddress($vmDetails);
		$vmDetails->hostname  = $this->determineHostname($vmDetails);

		// mark the box as provisioned
		// we will use this in stopBox() to avoid destroying VMs that failed
		// to provision
		$vmDetails->provisioned = true;

		// remember this vm, now that it is running
		usingHostsTable()->addHost($vmDetails->hostId, $vmDetails);

		// now, let's get this VM into our SSH known_hosts file, to avoid
		// prompting people when we try and provision this VM
		$log->addStep("get the VM into the SSH known_hosts file", function() use($vmDetails) {
			usingHost($vmDetails->hostId)->runCommand("ls");
		});

		// all done
		$log->endAction("VM successfully started; IP address is {$vmDetails->ipAddress}");
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @return void
	 */
	public function startHost($vmDetails)
	{
		// what are we doing?
		$log = usingLog()->startAction("start VM");

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
		$command = "vagrant up";
		$result = $this->runCommandAgainstHostManager($vmDetails, $command);

		// did it work?
		if ($result->returnCode != 0) {
			$log->endAction("VM failed to start or re-provision :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// yes it did!!
		//
		// we need to know which SSH key to use
		$vmDetails->sshKeyFile = $this->determinePrivateKey($vmDetails);

		// now, we need to know how to contact this machine
		$vmDetails->ipAddress = $this->determineIpAddress($vmDetails);
		$vmDetails->hostname  = $this->determineHostname($vmDetails);

		// all done
		$log->endAction("VM successfully started; IP address is {$vmDetails->ipAddress}");
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @return void
	 */
	public function stopHost($vmDetails)
	{
		// what are we doing?
		$log = usingLog()->startAction("stop VM");

		// is the VM actually running?
		if (!$this->isRunning($vmDetails)) {
			// we've decided not to treat this as an error ... that might
			// change in a future release
			$log->endAction("VM was already stopped or destroyed");
			return;
		}

		// yes it is ... shut it down
		$command = "vagrant halt";
		$result = $this->runCommandAgainstHostManager($vmDetails, $command);

		// did it work?
		if ($result->returnCode != 0) {
			$log->endAction("VM failed to shutdown :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done - success!
		$log->endAction("VM successfully stopped");
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @return void
	 */
	public function restartHost($vmDetails)
	{
		// what are we doing?
		$log = usingLog()->startAction("restart VM");

		// stop and start
		$this->stopHost($vmDetails);
		$this->startHost($vmDetails);

		// all done
		$log->endAction("VM successfully restarted");
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @return void
	 */
	public function powerOffHost($vmDetails)
	{
		// what are we doing?
		$log = usingLog()->startAction("power off VM");

		// is the VM actually running?
		if (!$this->isRunning($vmDetails)) {
			// we've decided not to treat this as an error ... that might
			// change in a future release
			$log->endAction("VM was already stopped or destroyed");
			return;
		}

		// yes it is ... shut it down
		$command = "vagrant halt --force";
		$result = $this->runCommandAgainstHostManager($vmDetails, $command);

		// did it work?
		if ($result->returnCode != 0) {
			$log->endAction("VM failed to power off :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done - success!
		$log->endAction("VM successfully powered off");
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @return void
	 */
	public function destroyHost($vmDetails)
	{
		// what are we doing?
		$log = usingLog()->startAction("destroy VM");

		// is the VM actually running?
		if ($this->isRunning($vmDetails)) {
			// yes it is ... shut it down
			$command = "vagrant destroy --force";
			$result = $this->runCommandAgainstHostManager($vmDetails, $command);

			// did it work?
			if ($result->returnCode != 0) {
				$log->endAction("VM failed to shutdown :(");
				throw new E5xx_ActionFailed(__METHOD__);
			}
		}

		// if we get here, we need to forget about this VM
		usingHostsTable()->removeHost($vmDetails->hostId);

		// remove any roles
		usingRolesTable()->removeHostFromAllRoles($vmDetails->hostId);

		// all done
		$log->endAction();
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @param  string $command
	 * @return CommandResult
	 */
	public function runCommandAgainstHostManager($vmDetails, $command)
	{
		// what are we doing?
		$log = usingLog()->startAction("run vagrant command '{$command}'");

		// build the command
		$fullCommand = "cd '{$vmDetails->dir}' && $command 2>&1";

		// run the command
		$commandRunner = new CommandRunner();
		$result = $commandRunner->runSilently($fullCommand);

		// all done
		$log->endAction("return code was '{$result->returnCode}'");
		return $result;
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @param string $command
	 * @return CommandResult
	 */
	public function runCommandViaHostManager($vmDetails, $command)
	{
		// what are we doing?
		$log = usingLog()->startAction("run vagrant command '{$command}'");

		// build the command
		$fullCommand = "cd '{$vmDetails->dir}' && vagrant ssh -c \"$command\"";

		// run the command
		$commandRunner = new CommandRunner();
		$result = $commandRunner->runSilently($fullCommand);

		// all done
		$log->endAction("return code was '{$result->returnCode}'");
		return $result;
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @return boolean
	 */
	public function isRunning($vmDetails)
	{
		// what are we doing?
		$log = usingLog()->startAction("determine status of Vagrant VM '{$vmDetails->hostId}'");

		// if the box is running, it should have a status of 'running'
		$command = "vagrant status | grep {$vmDetails->hostId} | head -n 1 | awk '{print \$2'}";
		$result  = $this->runCommandAgainstHostManager($vmDetails, $command);

		$lines = explode("\n", $result->output);
		$state = trim($lines[0]);
		if ($state != 'running') {
			$log->endAction("VM is not running; state is '{$state}'");
			return false;
		}

		// all done
		$log->endAction("VM is running");
		return true;
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @return string
	 */
	public function determineIpAddress($vmDetails)
	{
		// what are we doing?
		$log = usingLog()->startAction("determine IP address of Vagrant VM '{$vmDetails->hostId}'");

		// create an adapter to talk to the host operating system
		$host = OsLib::getHostAdapter($this->st, $vmDetails->osName);

		// get the IP address
		$ipAddress = $host->determineIpAddress($vmDetails, $this);

		// all done
		$log->endAction("IP address is '{$ipAddress}'");
		return $ipAddress;
	}

	/**
	 *
	 * @param  VagrantVmDetails $vmDetails
	 * @return string
	 */
	public function determineHostname($vmDetails)
	{
		// what are we doing?
		$log = usingLog()->startAction("determine hostname of Vagrant VM '{$vmDetails->hostId}'");

		// create an adapter to talk to the host operating system
		$host = OsLib::getHostAdapter($this->st, $vmDetails->osName);

		// get the hostname
		$hostname = $host->determineHostname($vmDetails, $this);

		// all done
		$log->endAction("hostname is '{$hostname}'");
		return $hostname;
	}

	public function determinePrivateKey($vmDetails)
	{
		// what are we doing?
		$log = usingLog()->startAction("determine private key for Vagrant VM '{$vmDetails->hostId}'");

		// the key will be in one of two places, in this order:
		//
		// cwd()/.vagrant/machines/:name/virtualbox/private_key
		// $HOME/.vagrant.d/insecure_private_key
		//
		// we use the first that we can find
		$keyFilenames = [
			getcwd() . "/.vagrant/machines/{$vmDetails->hostId}/virtualbox/private_key",
			getenv("HOME") . "/.vagrant.d/insecure_private_key"
		];

		foreach ($keyFilenames as $keyFilename)
		{
			$st->usingLog()->writeToLog("checking if {$keyFilename} exists");
			if (file_exists($keyFilename)) {
				$log->endAction($keyFilename);
				return $keyFilename;
			}
		}

		// if we get here, then we do not know where the private key is
		$log->endAction("unable to find Vagrant private key for VM");
		throw new E5xx_ActionFailed(__METHOD__);
	}
}
