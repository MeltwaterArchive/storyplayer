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

use DataSift\Storyplayer\CommandLib\CommandResult;
use DataSift\Storyplayer\CommandLib\CommandRunner;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\ProvisioningLib\ProvisioningDefinition;
use Prose\E5xx_ActionFailed;

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

	public function buildDefinitionFor($env)
	{
		// our return value
		$provDef = new ProvisioningDefinition;

		// what are we doing?
		$log = usingLog()->startAction("build Ansible provisioning definition");

		// add in each machine in the environment
		foreach ($env->details->machines as $hostId => $machine) {
			usingProvisioningDefinition($provDef)->addHost($hostId);

			foreach ($machine->roles as $role) {
				usingProvisioningDefinition($provDef)->addRole($role)->toHost($hostId);
			}

			if (isset($machine->params)) {
				$params = [];
				foreach ($machine->params as $paramName => $paramValue) {
					$params[$paramName] = fromConfig()->get('hosts.' . $hostId . '.params.'.$paramName);
				}
				if (count($params)) {
					usingProvisioningDefinition($provDef)->addParams($params)->toHost($hostId);
				}
			}
		}

		// all done
		$log->endAction($provDef);
		return $provDef;
	}

	public function provisionHosts(ProvisioningDefinition $hosts, $provConf)
	{
		// what are we doing?
		$log = usingLog()->startAction("use Ansible to provision host(s)");

		// get our ansible configuration
		$ansibleSettings = fromConfig()->getModuleSetting('ansible');

		// our reverse list of roles => hosts
		$rolesToHosts = array();

		// build up the list of roles
		foreach($hosts as $hostId => $hostProps) {
			// what is the host's IP address?
			$ipAddress = fromHost($hostId)->getIpAddress();

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
		foreach ($rolesToHosts as $role => $hostsForRole) {
			// add the role marker
			$inventory .= "[{$role}]" . PHP_EOL;

			// add the list of hosts
			foreach ($hostsForRole as $host) {
				$inventory .= $host . PHP_EOL;
			}

			// add an extra blank line for readability
			$inventory .= PHP_EOL;
		}

		// write out the inventory
		$inventoryFile = $this->writeInventoryFile($inventory);

		// where should we create the host_vars?
		$inventoryFolder = dirname($inventoryFile);

		// now we need to write out the host files
		foreach($hosts as $hostId => $hostProps) {
			// what is the host's IP address?
			$ipAddress   = fromHost($hostId)->getIpAddress();
			$sshUsername = fromHost($hostId)->getSshUsername();
			$sshKeyFile  = fromHost($hostId)->getSshKeyFile();

			// do we have any vars to write?
			if (!isset($hostProps->params) || $hostProps->params === null || (is_array($hostProps) && count($hostProps) == 0)) {
				// we'd better remove any host_vars file that exists,
				// in case what's there (if anything) is left over from
				// a different test run
				$this->removeHostVarsFile($inventoryFolder, $ipAddress);
			}
			else {
				// write the host vars file
				$this->writeHostVarsFile($inventoryFolder, $ipAddress, $hostProps->params);
			}
		}

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
		$commandRunner = new CommandRunner();
		$result = $commandRunner->runSilently($command);
		chdir($cwd);

		// what happened?
		if (!$result->didCommandSucceed()) {
			throw new E5xx_ActionFailed(__METHOD__, "provisioning failed");
		}

		// all done
		$log->endAction();
	}

	/**
	 * @param string $inventoryFolder
	 */
	protected function removeHostVarsFile($inventoryFolder, $ipAddress)
	{
		// what are we doing?
		$log = usingLog()->startAction("remove host_vars file for '{$ipAddress}'");

		// what is the path to the file?
		$filename = $this->getHostVarsFilename($inventoryFolder, $ipAddress);

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

	/**
	 * @param string $inventoryFolder
	 */
	protected function writeHostVarsFile($inventoryFolder, $ipAddress, $vars)
	{
		// what are we doing?
		$log = usingLog()->startAction("write host_vars file for '{$ipAddress}'");

		// what is the path to the file?
		$filename = $this->getHostVarsFilename($inventoryFolder, $ipAddress);

		// does the target folder exist?
		$hostVarsFolder = dirname($filename);
		if (!file_exists($hostVarsFolder)) {
			mkdir ($hostVarsFolder);
		}

		// write the data
		usingYamlFile($filename)->writeDataToFile($vars);

		// all done
		$log->endAction("written to file '{$filename}'");
	}

	/**
	 * @param string $inventory
	 *
	 * @return string
	 */
	protected function writeInventoryFile($inventory)
	{
		// what are we doing?
		$log = usingLog()->startAction("write temporary inventory file");

		// what are we going to call the inventory file?
		$filename = fromFile()->getTmpFilename();

		// write the data
		file_put_contents($filename, $inventory);

		// all done
		$log->endAction("written to file '{$filename}'");
		return $filename;
	}

	/**
	 * @param string $inventoryFolder
	 */
	protected function getHostVarsFilename($inventoryFolder, $hostId)
	{
		// what are we doing?
		$log = usingLog()->startAction("determine host_vars filename for host '{$hostId}'");

		// get our ansible settings
		$ansibleSettings = fromConfig()->getModuleSetting('ansible');

		// get our inventory folder
		$invFolder = $this->getInventoryFolder($ansibleSettings, $inventoryFolder);

		// what is the path to the file?
		$filename = $invFolder . DIRECTORY_SEPARATOR . 'host_vars' . DIRECTORY_SEPARATOR . $hostId;

		// all done
		$log->endAction("filename is: " . $filename);
		return $filename;
	}

	/**
	 * @param string $inventoryFolder
	 */
	protected function getInventoryFolder($ansibleSettings, $inventoryFolder)
	{
		// is there an Ansible.cfg file?
		$cfgFile = $ansibleSettings->dir . DIRECTORY_SEPARATOR . 'ansible.cfg';

		if (!file_exists($cfgFile)) {
			return $inventoryFolder;
		}

		// if we get here, there is a config file to parse
		$ansibleCfg = parse_ini_file($cfgFile, true);

		if (!is_array($ansibleCfg)) {
			// we can't parse the file
			return $inventoryFolder;
		}

		if (!isset($ansibleCfg['defaults'], $ansibleCfg['defaults']['hostfile'])) {
			// there's no inventory in the config file
			return $inventoryFolder;
		}

		// is the inventory a folder?
		$invDir = $inventoryFolder . DIRECTORY_SEPARATOR . $ansibleCfg['defaults']['hostfile'];
		if (is_dir($invDir)) {
			// this is where we need to write our variables to
			return $invDir;
		}

		// give up
		return $inventoryFolder;
	}
}
