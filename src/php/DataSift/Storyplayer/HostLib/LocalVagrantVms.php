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
class LocalVagrantVms implements SupportedHost
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

		// make sure we have a Vagrantfile
		if (!file_exists("Vagrantfile")) {
			throw new E5xx_ActionFailed(__METHOD__, "no Vagrantfile in current working directory");
		}
		$envDetails->dir = getcwd();

		// make sure we're happy with details about the machine
		foreach($envDetails->machines as $hostId => $machine) {
			// TODO: it would be great to autodetect this one day
			if (!isset($machine->osName)) {
				throw new E5xx_ActionFailed(__METHOD__, "missing envDetails->machines['$hostId']->osName");
			}
			if (!isset($machine->roles)) {
				throw new E5xx_ActionFailed(__METHOD__, "missing envDetails->machines['$hostId']->roles");
			}
		}

		// make sure the VM is stopped, if it is running
		$log->addStep("stop vagrant VM(s) if already running", function() use($envDetails) {
			$command = "vagrant destroy --force";
			$this->runCommandAgainstHostManager($envDetails, $command);
		});

		// remove any existing hosts table entry
		foreach ($envDetails->machines as $hostId => $machine) {
			$st->usingHostsTable()->removeHost($hostId);

			// remove any roles
			$st->usingRolesTable()->removeHostFromAllRoles($hostId);
		}

		// work out which network interface to use
		$bridgedIface = $this->determineBridgedInterface();
		putenv('VIRTUALBOX_BRIDGE_ADAPTER=' . $bridgedIface);

		// let's start the VM
		$command = "vagrant up";
		$result = $log->addStep("create vagrant VM(s)", function() use($envDetails, $command) {
			return $this->runCommandAgainstHostManager($envDetails, $command);
		});

		// did it work?
		if ($result->returnCode != 0) {
			$log->endAction("VM failed to start or provision :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// yes it did!!

		// store the details
		foreach($envDetails->machines as $hostId => $machine)
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
			$vmDetails->hostId      = $hostId;

			// remember where the machine lives
			$vmDetails->dir         = $envDetails->dir;

			// we need to remember how to SSH into the box
			$vmDetails->sshUsername = 'vagrant';
			$vmDetails->sshKeyFile  = $this->determinePrivateKey($vmDetails);
			$vmDetails->sshOptions  = [
				"-i '" . $vmDetails->sshKeyFile . "'",
				"-o StrictHostKeyChecking=no",
				"-o UserKnownHostsFile=/dev/null",
				"-o LogLevel=quiet",
			];
			$vmDetails->scpOptions  = [
				"-i '" . $vmDetails->sshKeyFile . "'",
				"-o StrictHostKeyChecking=no",
			];

			// remember how to connect to the machine via the network
			$vmDetails->ipAddress   = $this->determineIpAddress($vmDetails);

			// mark the box as provisioned
			// we will use this in stopBox() to avoid destroying VMs that failed
			// to provision
			$vmDetails->provisioned = true;

			// remember this vm, now that it is running
			$st->usingHostsTable()->addHost($vmDetails->hostId, $vmDetails);
			foreach ($vmDetails->roles as $role) {
				$st->usingRolesTable()->addHostToRole($vmDetails, $role);
			}

			// now, let's get this VM into our SSH known_hosts file, to avoid
			// prompting people when we try and provision this VM
			$log->addStep("get the VM into the SSH known_hosts file", function() use($st, $vmDetails) {
				$st->usingHost($vmDetails->hostId)->runCommand("ls");
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
		foreach ($envDetails->machines as $hostId => $machine) {
			// get the machine details
			$vmDetails = $st->fromHostsTable()->getDetailsForHost($hostId);
			if ($vmDetails) {
				// is the VM actually running?
				if ($st->fromHost($hostId)->getHostIsRunning()) {
					// delete the VM from disk
					//
					// this will also deregister the host from the
					// HostsTable and RolesTable
					$st->usingVagrant()->destroyVm($hostId);
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
		$log = $st->startAction("determine IP address of Vagrant VM '{$vmDetails->hostId}'");

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
		foreach($lines as $line) {
			$matches = [];
			if (preg_match("|Name:[\s]+(.*)|", $line, $matches)) {
				$iface = $matches[1];
			}
			else if ($iface && preg_match("|IPAddress:[\s]+(.*)|", $line, $matches)) {
				// our network interface contains an IPAddress - it is likely
				// to be one that works
				if ($matches[1] != '0.0.0.0') {
					$log->endAction($iface);
					return $iface;
				}
			}
		}

		// if we get here, then we haven't found a network interface to use
		$log->endAction("no bridgeable network interface found :(");
		throw new E5xx_ActionFailed(__METHOD__);
	}

	public function determinePrivateKey($vmDetails)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("determine private key for Vagrant VM '{$vmDetails->hostId}'");

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