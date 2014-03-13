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
 * @package   Storyplayer/ProvisioningLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\ProvisioningLib\Provisioners;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Storyplayer\ProvisioningLib\ProvisioningDefinition;

/**
 * support for provisioning via Ansible
 *
 * @category  Libraries
 * @package   Storyplayer/ProvisioningLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class AnsibleProvisioner extends Provisioner
{
	public function __construct(StoryTeller $st)
	{
		// remember for the future
		$this->st = $st;
	}

	public function provisionHosts(ProvisioningDefinition $hosts)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("use Ansible to provision host(s)");

		// our reverse list of roles => hosts
		$rolesToHosts = array();

		// process each host
		foreach($hosts as $hostName => $hostProps) {
			// what is the host's IP address?
			$ipAddress   = $st->fromHost($hostName)->getIpAddress();
			$sshUsername = $st->fromHost($hostName)->getSshUsername();
			$sshKeyFile  = $st->fromHost($hostName)->getSshKeyFile();

			// do we have any vars to write?
			if (!isset($hostProps->params) || $hostProps->params === null || (is_array($hostProps) && count($hostProps) == 0)) {
				// we'd better remove any host_vars file that exists,
				// in case what's there (if anything) is left over from
				// a different test run
				$this->removeHostVarsFile($ipAddress);
			}
			else {
				// write the host vars file
				$this->writeHostVarsFile($ipAddress, $hostProps->params);
			}

			// add the host to the required roles
			if (isset($hostProps->roles)) {
				foreach ($hostProps->roles as $role) {
					if (!isset($rolesToHosts[$role])) {
						$rolesToHosts[$role] = array();
					}
					$rolesToHosts[$role][] = $ipAddress;
				}
			}
		}

		// at this point, we know which roles need applying to which hosts
		//
		// build up the inventory file
		$inventory = "";
		foreach ($rolesToHosts as $role => $hosts) {
			// add the role marker
			$inventory .= "[{$role}]" . PHP_EOL;

			// add the list of hosts
			foreach ($hosts as $host) {
				$inventory .= $host . PHP_EOL;
			}

			// add an extra blank line for readability
			$inventory .= PHP_EOL;
		}

		// write out the inventory
		$inventoryFile = $this->writeInventoryFile($inventory);

		// get our ansible configuration
		$ansibleSettings = $st->fromEnvironment()->getAppSettings('ansible');

		// build the command for Ansible
		$command = 'ansible-playbook -i "' . $inventoryFile . '"'
		         . ' "--private-key=' . $sshKeyFile . '"'
		         . ' "--user=' . $sshUsername . '"';

		$command .= ' "' . $ansibleSettings->dir . DIRECTORY_SEPARATOR . $ansibleSettings->playbook . '"';

		// let's run the command
		//
		// this looks like a hack, but it is the only way to work with Ansible
		// if there's an ansible.cfg in the root of the playbook :(
		$cwd = getcwd();
		chdir($ansibleSettings->dir);
		$result = $st->usingShell()->runCommand($command);
		chdir($cwd);

		// what happened?
		if (!$result->didCommandSucceed()) {
			throw new E5xx_ActionFailed(__METHOD__, "provisioning failed");
		}

		// all done
		$log->endAction();
	}

	protected function removeHostVarsFile($ipAddress)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("remove host_vars file for '{$ipAddress}'");

		// what is the path to the file?
		$filename = $this->getHostVarsFilename($ipAddress);

		// remove the file
		if (file_exists($filename)) {
			unlink($filename);
			$log->endAction("removed file '{$filename}'");
		}
		else {
			$log->endAction("no file to remove; skipping");
		}

		// all done
	}

	protected function writeHostVarsFile($ipAddress, $vars)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("write host_vars file for '{$ipAddress}'");

		// what is the path to the file?
		$filename = $this->getHostVarsFilename($ipAddress);

		// write the data
		$st->usingYamlFile($filename)->writeDataToFile($vars);

		// all done
		$log->endAction("written to file '{$filename}'");
	}

	protected function writeInventoryFile($inventory)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("write temporary inventory file");

		// what are we going to call the inventory file?
		$filename = $st->fromFile()->getTmpFilename();

		// write the data
		file_put_contents($filename, $inventory);

		// all done
		$log->endAction("written to file '{$filename}'");
		return $filename;
	}

	protected function getHostVarsFilename($hostName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("determine host_vars filename for '{$hostName}'");

		// get our ansible settings
		$ansibleSettings = $st->fromEnvironment()->getAppSettings('ansible');

		// is there an Ansible.cfg file?
		$cfgFile = $ansibleSettings->dir . DIRECTORY_SEPARATOR . 'ansible.cfg';
		$invDir = '';
		if (file_exists($cfgFile)) {
			$ansibleCfg = parse_ini_file($cfgFile);
			if (is_array($ansibleCfg) && isset($ansibleCfg['defaults'], $ansibleCfg['defaults']['inventory'])) {
				$invDir = $ansibleSettings->dir . DIRECTORY_SEPARATOR . $ansibleCfg['defaults']['inventory'] . DIRECTORY_SEPARATOR;
				if (!is_dir($invDir)) {
					$invDir = '';
				}
			}
		}

		// what is the path to the file?
		$filename = $ansibleSettings->dir . DIRECTORY_SEPARATOR . $invDir . 'host_vars' . DIRECTORY_SEPARATOR . $hostName;

		// all done
		$log->endAction("filename is: " . $filename);
		return $filename;
	}
}
