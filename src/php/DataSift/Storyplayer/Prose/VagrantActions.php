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

use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\Prose;
use DataSift\Storyplayer\PlayerLib\StoryPlayer;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

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
class VagrantActions extends Prose
{
	public function createBox($boxName, $homeFolder, $playbookVars = array())
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("create VM '{$boxName}' using config in '{$homeFolder}'");

		// is the box already running?
		if ($st->getCurrentPhase() !== StoryPlayer::PHASE_ACTION) {
			if ($st->fromVagrant()->getBoxIsRunning($boxName)) {
				$log->endAction("box is already running; not re-creating");
				return;
			}
		}

		// make sure the folder exists
		$env = $st->getEnvironment();
		if (!isset($env->vagrant)) {
			throw new E5xx_ActionFailed(__METHOD__, "environment does not support vagrant");
		}
		if (!isset($env->vagrant->dir)) {
			throw new E5xx_ActionFailed(__METHOD__, "'dir' setting missing from 'vagrant' section of environment config");
		}

		$pathToHomeFolder = $env->vagrant->dir . '/' . $homeFolder;
		if (!is_dir($pathToHomeFolder)) {
			throw new E5xx_ActionFailed(__METHOD__, "VM dir '{$pathToHomeFolder}' does not exist");
		}

		// create some initial info in the runtime configsection
		$boxDetails = new BaseObject();

		$runtimeConfig = $st->getRuntimeConfig();
		if (!isset($runtimeConfig->vagrant)) {
			$runtimeConfig->vagrant = new BaseObject();
		}

		if (!isset($runtimeConfig->vagrant->vms)) {
			$runtimeConfig->vagrant->vms = new BaseObject();
		}
		$runtimeConfig->vagrant->vms->$boxName = $boxDetails;
		$boxDetails->name = $boxName;
		$boxDetails->dir  = $pathToHomeFolder;

		// make sure the VM is stopped, if it is running
		$command = "cd '{$pathToHomeFolder}' && vagrant destroy --force";
		$log->addStep("stop vagrant VM in '{$pathToHomeFolder}' if already running", function() use($command, &$retVal) {
			passthru($command);
		});

		// write out the playbook variables, so that we can tailor our
		// VM to suit this test
		$st->usingVagrant()->writePlaybookVars($pathToHomeFolder, $playbookVars);

		// let's start the VM
		$command = "cd '{$pathToHomeFolder}' && vagrant up";
		$retVal = 1;
		$log->addStep("start vagrant VM in '{$pathToHomeFolder}'", function() use($command, &$retVal) {
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
		$ipAddress = $st->fromVagrant()->getIpAddress($boxName);

		// store the IP address for future use
		$boxDetails->ipAddress = $ipAddress;

		// mark the box as provisioned
		// we will use this in stopBox() to avoid destroying VMs that failed
		// to provision
		$boxDetails->provisioned = true;

		// all done
		$log->endAction("VM successfully started; IP address is {$ipAddress}");
	}

	public function stopBox($boxName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop vagrant VM '{$boxName}'");

		// get the VM details
		$boxDetails = $st->fromVagrant()->getDetails($boxName);

		// does the box need inspecting?
		if (isset($boxDetails->provisioned) && $boxDetails->provisioned) {
			// stop the box
			$command = "cd '{$boxDetails->dir}' && vagrant destroy --force";
			passthru($command);
		}

		// forget about this box
		$runtimeConfig = $st->getRuntimeConfig();
		unset($runtimeConfig->vagrant->vms->$boxName);

		// all done
		$log->endAction();
	}

	public function runCommandInBox($boxName, $command)
	{
		// shorthand
		$st = $this->st;

		// escape any double quotes in the command
		//
		// do this before we log the command, so that the log output
		// is accurate
		$command = str_replace('"', '\"', $command);

		// what are we doing?
		$log = $st->startAction("run command '{$command}' in Vagrant VM '{$boxName}'");

		// get the VM's IP address
		$ipAddress = $st->fromVagrant()->getIpAddress($boxName);

		// build the command to execute
		//
		// the options we pass to SSH:
		//
		// -o StrictHostKeyChecking=no
		//    do not verify the SSH host key (avoids an interactive prompt)
		// -i .../insecure_private_key
		//    use Vagrant's SSH key to log into the host
		// vagrant@hostname
		//    we always login as the user 'vagrant'
		$fullCommand = "ssh -o StrictHostKeyChecking=no -i \$HOME/.vagrant.d/insecure_private_key vagrant@{$ipAddress} -n \"{$command}\"";

		// run the command
		$result = null;
		$log->addStep("run command against VM: '{$fullCommand}'", function() use ($fullCommand, &$result) {
			$result = trim(`$fullCommand`);
		});

		// all done
		$log->endAction("output was '{$result}'");
		return $result;
	}

	public function runVagrantCommand($boxName, $command)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run command '{$command}' in Vagrant VM '{$boxName}'");

		// get the VM details
		$boxData = $st->fromVagrant()->getDetails($boxName);

		// run the command
		$fullCommand = "cd '{$boxData->dir}' && {$command}";
		$result = null;
		$log->addStep("run command against VM: '{$fullCommand}'", function() use ($fullCommand, &$result) {
			$result = trim(`$fullCommand`);
		});

		// all done
		$log->endAction("output was '{$result}'");
		return $result;
	}

	public function writePlaybookVars($pathToVmHomeFolder, $playbookVars)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("write out ansible playbook vars");

		// where are we writing to?
		$parts = explode('-', $pathToVmHomeFolder);
		if (count($parts) < 2) {
			$log->endAction("cannot break folder path up to determine which OS we are running");
			throw new E5xx_ActionFailed(__METHOD__);
		}
		$os = $parts[count($parts) - 2] . '-' . $parts[count($parts) - 1];

		$storytellerVarsFilename = dirname(dirname($pathToVmHomeFolder)) . "/ansible-playbooks/vars/storyteller.yml";

		// make sure we have something to write
		if (count($playbookVars) == 0) {
			$playbookVars['dummy'] = 'true';
		}

		// make sure we ahve somewhere to write it to
		$log->addStep("create folder for ansible vars file", function() use($storytellerVarsFilename) {
			$storytellerVarsDirname = dirname($storytellerVarsFilename);
			if (!is_dir($storytellerVarsDirname)) {
				mkdir($storytellerVarsDirname);
			}
		});

		// save the data
		$log->addStep("write vars to file '{$storytellerVarsFilename}'", function() use ($storytellerVarsFilename, $playbookVars) {
			file_put_contents($storytellerVarsFilename, yaml_emit($playbookVars));
		});

		// all done
		$log->endAction();
	}
}