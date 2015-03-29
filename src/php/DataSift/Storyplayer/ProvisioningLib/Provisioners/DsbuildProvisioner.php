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
 * @author    Nicola Asuni <nicola.asuni@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\ProvisioningLib\Provisioners;

use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Storyplayer\CommandLib\CommandResult;
use DataSift\Storyplayer\CommandLib\CommandRunner;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\ProvisioningLib\ProvisioningDefinition;
use Prose\E5xx_ActionFailed;

/**
 * support for provisioning via dsbuild
 *
 * @category  Libraries
 * @package   Storyplayer/ProvisioningLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class DsbuildProvisioner extends Provisioner
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
		$log = usingLog()->startAction("build dsbuild provisioning definition");

		// add in each machine in the environment
		foreach ($env->details->machines as $hostId => $machine) {
			usingProvisioningDefinition($provDef)->addHost($hostId);

			foreach ($machine->roles as $role) {
				usingProvisioningDefinition($provDef)->addRole($role)->toHost($hostId);
			}

			if (isset($machine->params)) {
				$params = [];
				foreach ($machine->params as $paramName => $paramValue) {
					$params[$paramName]  = fromConfig()->get('hosts.' . $hostId . '.params.'.$paramName);
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

	public function provisionHosts(ProvisioningDefinition $hosts, $provConfig)
	{
		// what are we doing?
		$log = usingLog()->startAction("use dsbuild to provision host(s)");

		// the params file that we are going to output
		$dsbuildParams = new BaseObject;

		// build up the list of settings to write out
		foreach($hosts as $hostId => $hostProps) {
			// what is the host's IP address?
			$ipAddress = fromHost($hostId)->getIpAddress();

			$propName = $hostId . '_ipv4Address';
			$dsbuildParams->$propName = $ipAddress;
			if (isset($hostProps->params)) {
				$dsbuildParams->mergeFrom($hostProps->params);
			}
		}

		// add in all the config settings that we know about
		$dsbuildParams->storyplayer_ipv4Address = fromConfig()->get('storyplayer.ipAddress');
		$dsbuildParams->mergeFrom($this->flattenData($this->st->getActiveConfig()->getData('')));

		// write them out
		$this->writeDsbuildParamsShellFile((array)$dsbuildParams);
		$this->writeDsbuildParamsYamlFile((array)$dsbuildParams);

		// at this point, we are ready to attempt provisioning
		//
		// provision each host in the order that they're listed
		foreach($hosts as $hostId => $hostProps) {
			// which dsbuildfile are we going to run?
			$dsbuildFilename = $this->getDsbuildFilename($provConfig, $hostId);
			if ($dsbuildFilename === null) {
				// there is no dsbuildfile at all to run
				$log->endAction("cannot find dsbuildfile to run :(");
				throw new E5xx_ActionFailed(__METHOD__, "no dsbuildfile to run");
			}

			// at this point, we are ready to provision
			$commandRunner = new CommandRunner();

			// copy the dsbuildparams files to the target machine using scp
			// NOTE: the "vagrant rsync" command seems not working with some Vagrant provisioners (e.g. OpenStack)
			$command = 'scp'
				.' '.$dsbuildParams->{'hosts_'.$hostId.'_scpOptions_0'}
				.' '.$dsbuildParams->{'hosts_'.$hostId.'_scpOptions_1'}
				.' dsbuildparams.*'
				.' '.$dsbuildParams->{'hosts_'.$hostId.'_sshUsername'}
				.'@'.$dsbuildParams->{'hosts_'.$hostId.'_ipAddress'}
				.':/vagrant/';
			$result = $commandRunner->runSilently($command);

			if (!$result->didCommandSucceed()) {
				// try to rsync folders in case of scp fail
				$command = 'vagrant rsync ' . $hostId;
				$commandRunner->runSilently($command);
			}

			// provision
			$command = 'vagrant ssh -c "sudo bash /vagrant/' . $dsbuildFilename . '" "' . $hostId . '"';
			$result = $commandRunner->runSilently($command);

			// what happened?
			if (!$result->didCommandSucceed()) {
				throw new E5xx_ActionFailed(__METHOD__, "provisioning failed");
			}
		}

		// all done
		$log->endAction();
	}

	/**
	 * @param string $inventoryFolder
	 */
	protected function writeDsbuildParamsYamlFile($vars)
	{
		// what are we doing?
		$log = usingLog()->startAction("write dsbuildparams.yml");

		// what is the path to the file?
		$filename = "dsbuildparams.yml";

		// write the data
		usingYamlFile($filename)->writeDataToFile($vars);

		// all done
		$log->endAction();
	}

	/**
	 * @param string $inventoryFolder
	 */
	protected function writeDsbuildParamsShellFile($vars)
	{
		// what are we doing?
		$log = usingLog()->startAction("write dsbuildparams.sh");

		// what is the path to the file?
		$filename = "dsbuildparams.sh";

		// build the data to write
		$output = "";
		foreach ($vars as $name => $value) {
			$name = str_replace("-", "_", $name);
			$output .= strtoupper($name) . "='" . $value . "';" . PHP_EOL;
		}

		// write the data
		file_put_contents($filename, $output);

		// all done
		$log->endAction();
	}

	/**
	 * converts a tree of data into underscore_notation
	 *
	 * @param  mixed $inputData
	 *         the data to flatten
	 * @param  string $prefix
	 *         the path to the parent of the inputData
	 * @return array
	 *         the flattened data
	 */
	protected function flattenData($inputData, $prefix="")
	{
		$retval = [];

		foreach ($inputData as $name => $dataToFlatten)
		{
			if (is_object($dataToFlatten) || is_array($dataToFlatten)) {
				$retval = array_merge($retval, $this->flattenData($dataToFlatten, $prefix . $name . "_"));
			}
			else {
				$retval[$prefix . $name] = $dataToFlatten;
			}
		}

		return $retval;
	}

	/**
	 * find the provisioning script to run for a given hostId
	 *
	 * @param  BaseObject $provConfig
	 *         the "provisioning" section from the test environment config
	 * @param  string $hostId
	 *         the ID of the host that we are provisioning
	 * @return string
	 *         path to the file to execute
	 */
	protected function getDsbuildFilename($provConfig, $hostId)
	{
		if (isset($provConfig->execute)) {
			$basename = dirname($provConfig->execute) . "/" . basename($provConfig->execute, '.sh');
		}
		else {
			$basename = "dsbuildfile";
		}

		$candidateFilenames = [
			$basename . "-" . $hostId . '.sh',
			$basename . "-" . $hostId,
			$basename . ".sh",
			$basename,
		];

		foreach ($candidateFilenames as $candidateFilename) {
			if (file_exists($candidateFilename)) {
				return $candidateFilename;
			}
		}

		// no file found
		return null;
	}
}
