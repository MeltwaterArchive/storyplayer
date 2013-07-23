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
use DataSift\Storyplayer\HostLib\Ec2Vm;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * do things with Amazon EC2
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Ec2Actions extends Prose
{
	public function __construct(StoryTeller $st, $args = array())
	{
		// call the parent constructor
		parent::__construct($st, $args);
	}

	public function createVm($vmName, $osName, $amiId, $instanceType, $securityGroup)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("start EC2 VM '{$vmName}', running guest OS '{$osName}', using AMI ID '{$amiId}' and security group '{$securityGroup}'");

		// get the aws settings
		$awsSettings = $st->fromEnvironment()->getAppSettings('aws');

		// put the details into an array
		$vmDetails = new BaseObject();
		$vmDetails->name          = $vmName;
		$vmDetails->environment   = $st->getEnvironmentName();
		$vmDetails->osName        = $osName;
		$vmDetails->amiId         = $amiId;
		$vmDetails->type          = 'Ec2Vm';
		$vmDetails->instanceType  = $instanceType;
		$vmDetails->securityGroup = $securityGroup;
		$vmDetails->keyPairName   = $awsSettings->ec2->keyPairName;
		$vmDetails->sshUsername   = $awsSettings->ec2->sshUsername;
		$vmDetails->sshKeyFile    = $awsSettings->ec2->sshKeyFile;
		$vmDetails->sshOptions    = array (
			"-i '" . $awsSettings->ec2->sshKeyFile . "'"
		);

		// create our host adapter
		$host = new Ec2Vm($this->st);

		// create our virtual machine
		$host->createHost($vmDetails);

		// all done
		$log->endAction();
	}

	public function destroyVm($vmName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("destroy EC2 VM '{$vmName}'");

		// get the VM details
		$vmDetails = $st->fromHost($vmName)->getDetails();

		// create our host adapter
		$host = new Ec2Vm($st);

		// stop the VM
		$host->destroyHost($vmDetails);

		// all done
		$log->endAction();
	}

	public function stopVm($vmName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop EC2 VM '{$vmName}'");

		// get the VM details
		$vmDetails = $st->fromHost($vmName)->getDetails();

		// create our host adapter
		$host = new Ec2Vm($st);

		// stop the VM
		$host->stopHost($vmDetails);

		// all done
		$log->endAction();
	}

	public function powerOffVm($vmName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("power off EC2 VM '{$vmName}'");

		// get the VM details
		$vmDetails = $st->fromHost($vmName)->getDetails();

		// create our host adapter
		$host = new EC2Vm($st);

		// stop the VM
		$host->stopHost($vmDetails);

		// all done
		$log->endAction();
	}

	public function restartVm($vmName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("restart start EC2 VM '{$vmName}'");

		// get the VM details
		$vmDetails = $st->fromHost($vmName)->getDetails();

		// create our host adapter
		$host = new EC2Vm($this->st);

		// restart our virtual machine
		$host->restartHost($vmDetails);

		// all done
		$log->endAction();
	}

	public function startVm($vmName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("restart start EC2 VM '{$vmName}'");

		// get the VM details
		$vmDetails = $st->fromHost($vmName)->getDetails();

		// create our host adapter
		$host = new Ec2Vm($this->st);

		// restart our virtual machine
		$host->startHost($vmDetails);

		// all done
		$log->endAction();
	}
}