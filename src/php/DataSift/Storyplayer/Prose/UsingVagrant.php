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

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\HostLib;
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
class UsingVagrant extends VmActionsBase
{
	public function __construct(StoryTeller $st, $args = array())
	{
		// call the parent constructor
		parent::__construct($st, $args);
	}

	public function createVm($vmName, $osName, $homeFolder)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("start vagrant VM '{$vmName}', running guest OS '{$osName}', using Vagrantfile in '{$homeFolder}'");

		// put the details into an array
		$vmDetails = new BaseObject();
		$vmDetails->name        = $vmName;
		$vmDetails->osName      = $osName;
		$vmDetails->homeFolder  = $homeFolder;
		$vmDetails->type        = 'VagrantVm';
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

		// create our host adapter
		$host = HostLib::getHostAdapter($st, $vmDetails->type);

		// create our virtual machine
		$host->createHost($vmDetails);

		// all done
		$log->endAction();
	}

	public function determinePrivateKey($vmDetails)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("determine private key for Vagrant VM '{$vmDetails->name}'");

		// the key will be in one of two places, in this order:
		//
		// cwd()/.vagrant/machines/:name/virtualbox/private_key
		// $HOME/.vagrant.d/insecure_private_key
		//
		// we use the first that we can find
		$keyFilenames = [
			getcwd() . "/.vagrant/machines/{$vmDetails->name}/virtualbox/private_key",
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