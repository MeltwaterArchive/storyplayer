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

use DataSift\Storyplayer\CommandLib\CommandRunner;
use DataSift\Storyplayer\CommandLib\CommandResult;
use DataSift\Storyplayer\OsLib;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * the things you can do / learn about a group of Vagrant virtual machines
 *
 * @category  Libraries
 * @package   Storyplayer/HostLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class VagrantVms implements SupportedHost
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
	 * @param  stdClass $envDetails
	 * @param  array $provisioningVars
	 * @return void
	 */
	public function createHost($envDetails, $provisioningVars = array())
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction('create new VM');

		// make sure we like the provided details
		if (!isset($envDetails->homeFolder)) {
			throw new E5xx_ActionFailed(__METHOD__, "missing envDetails->homeFolder");
		}
		if (!isset($envDetails->machines)) {
			throw new E5xx_ActionFailed(__METHOD__, "missing envDetails->machines");
		}
		if (empty($envDetails->machines)) {
			throw new E5xx_ActionFailed(__METHOD__, "envDetails->machines cannot be empty");
		}
		foreach($envDetails->machines as $name => $machine) {
			// TODO: it would be great to autodetect this one day
			if (!isset($machine->osName)) {
				throw new E5xx_ActionFailed(__METHOD__, "missing envDetails->machines['$name']->osName");
			}
			if (!isset($machine->roles)) {
				throw new E5xx_ActionFailed(__METHOD__, "missing envDetails->machines['$name']->roles");
			}
		}

		// make sure the folder exists
		$vagrantDir = $st->fromConfig()->getModuleSetting('vagrant', 'dir');
		$pathToHomeFolder = $vagrantDir . '/' . $envDetails->homeFolder;
		if (!is_dir($pathToHomeFolder)) {
			throw new E5xx_ActionFailed(__METHOD__, "VM dir '{$pathToHomeFolder}' does not exist");
		}

		// remember where the Vagrantfile is
		$envDetails->dir = $pathToHomeFolder;

		// make sure the VM is stopped, if it is running
		$log->addStep("stop vagrant VM in '{$pathToHomeFolder}' if already running", function() use($envDetails) {
			$command = "vagrant destroy --force";
			$this->runCommandAgainstHostManager($envDetails, $command);
		});

		// remove any existing hosts table entry
		foreach ($envDetails->machines as $name => $machine) {
			$st->usingHostsTable()->removeHost($name);

			// remove any roles
			$st->usingRolesTable()->removeHostFromAllRoles($name);
		}

		// work out which network interface to use
		$bridgedIface = $this->determineBridgedInterface();
		putenv('VAGRANT_BRIDGE_ADAPTER=' . $bridgedIface);

		// let's start the VM
		$command = "vagrant up";
		$result = $log->addStep("create vagrant VM in '{$pathToHomeFolder}'", function() use($envDetails, $command) {
			return $this->runCommandAgainstHostManager($envDetails, $command);
		});

		// did it work?
		if ($result->returnCode != 0) {
			$log->endAction("VM failed to start or provision :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// yes it did!!

		// store the details
		foreach($envDetails->machines as $name => $machine)
		{
			// we want all the details from the config file
			$vmDetails = clone $machine;

			// this allows the story to perform actions against a single
			// machine if required
			$vmDetails->type        = 'VagrantVm';

			// new in v2.x:
			//
			// when provisioning a folder of vagrant vms, we now use
			// the same name for the VM that vagrant uses
			$vmDetails->name        = $name;

			// remember where the machine lives
			$vmDetails->dir         = $pathToHomeFolder;

			// we need to remember how to SSH into the box
			$vmDetails->sshUsername = 'vagrant';
			$vmDetails->sshKeyFile  = getenv('HOME') . "/.vagrant.d/insecure_private_key";
			$vmDetails->sshOptions  = [
				"-i '" . getenv('HOME') . "/.vagrant.d/insecure_private_key'",
				"-o StrictHostKeyChecking=no",
				"-o UserKnownHostsFile=/dev/null",
				"-o LogLevel=quiet",
			];
			$vmDetails->scpOptions = [
				"-i '" . getenv('HOME') . "/.vagrant.d/insecure_private_key'",
				"-o StrictHostKeyChecking=no",
			];

			// remember how to connect to the machine via the network
			$vmDetails->ipAddress   = $this->determineIpAddress($vmDetails);

			// mark the box as provisioned
			// we will use this in stopBox() to avoid destroying VMs that failed
			// to provision
			$vmDetails->provisioned = true;

			// remember this vm, now that it is running
			$st->usingHostsTable()->addHost($vmDetails->name, $vmDetails);
			foreach ($vmDetails->roles as $role) {
				$st->usingRolesTable()->addHostToRole($vmDetails, $role);
			}

			// now, let's get this VM into our SSH known_hosts file, to avoid
			// prompting people when we try and provision this VM
			$log->addStep("get the VM into the SSH known_hosts file", function() use($st, $vmDetails) {
				$st->usingHost($vmDetails->name)->runCommand("ls");
			});
		}

		// all done
		$log->endAction();
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return void
	 */
	public function startHost($envDetails)
	{
		// if you really want to do this from your story, use
		// $st->usingVagrantVm()->startHost()
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return void
	 */
	public function stopHost($envDetails)
	{
		// if you really want to do this from your story, use
		// $st->usingVagrantVm()->stopHost()
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return void
	 */
	public function restartHost($envDetails)
	{
		// if you really want to do this from your story, use
		// $st->usingVagrantVm()->restartHost()
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return void
	 */
	public function powerOffHost($envDetails)
	{
		// if you really want to do this from your story, use
		// $st->usingVagrantVm()->powerOffHost()
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return void
	 */
	public function destroyHost($envDetails)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("destroy VM(s)");

		// stop all the VMs, one by one
		foreach ($envDetails->machines as $name => $machine) {
			// get the machine details
			$vmDetails = $st->fromHostsTable()->getDetailsForHost($name);
			if ($vmDetails) {
				// is the VM actually running?
				if ($st->fromHost($name)->getHostIsRunning()) {
					// delete the VM from disk
					//
					// this will also deregister the host from the
					// HostsTable and RolesTable
					$st->usingVagrant()->destroyVm($name);
				}
			}
		}

		// all done
		$log->endAction();
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @param  string $command
	 * @return CommandResult
	 */
	public function runCommandAgainstHostManager($envDetails, $command)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run vagrant command '{$command}'");

		// build the command
		$fullCommand = "cd '{$envDetails->dir}' && $command 2>&1";

		// run the command
		$commandRunner = new CommandRunner();
		$result = $commandRunner->runSilently($st, $fullCommand);

		// all done
		$log->endAction("return code was '{$result->returnCode}'");
		return $result;
	}

	/**
	 *
	 * @param  stdClass $vmDetails
	 * @param  string $command
	 * @return CommandResult
	 */
	public function runCommandViaHostManager($vmDetails, $command)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run vagrant command '{$command}'");

		// build the command
		$fullCommand = "cd '{$vmDetails->dir}' && vagrant ssh -c \"$command\"";

		// run the command
		$commandRunner = new CommandRunner();
		$result = $commandRunner->runSilently($st, $fullCommand);

		// all done
		$log->endAction("return code was '{$result->returnCode}'");
		return $result;
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return boolean
	 */
	public function isRunning($envDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $vmDetails
	 * @return string
	 */
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

	public function determineBridgedInterface()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("determine bridged network interface for Vagrant VM");

		// VBoxManage can actually tell us what we need to know
		$command = 'VBoxManage list bridgedifs';
		$commandRunner = new CommandRunner();
		$result = $commandRunner->runSilently($st, $command);
		if ($result->returnCode != 0) {
			$log->endAction('unable to get list of bridgable network interfaces from VBoxManage :(');
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// now we just need to make sense of it all
		$lines = explode("\n", $result->output);
		$iface = null;
		$matches = [];
		foreach($lines as $line) {
			if (preg_match("|Name:[\s]+(.*)|", $line, $matches)) {
				$iface = $matches[1];
			}
			else if ($iface && preg_match("|IPAddress:[\s]+(.*)|", $line, $matches)) {
				// our network interface contains an IPAddress - it is likely
				// to be one that works
				if ($matches[1] != '0.0.0.0') {
					return $iface;
				}
			}
		}
	}
}