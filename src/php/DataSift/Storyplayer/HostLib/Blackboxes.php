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
use DataSift\Stone\ObjectLib\BaseObject;
use Prose\E5xx_ActionFailed;

/**
 * the things you can do / learn about a group of machines (possibly
 * physical, possibly virtual, possibly mixed) that you can't log into
 *
 * @category  Libraries
 * @package   Storyplayer/HostLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Blackboxes implements SupportedHost
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
		// what are we doing?
		$log = usingLog()->startAction('register blackbox(es)');

		// make sure we like the provided details
		if (!isset($envDetails->machines)) {
			throw new E5xx_ActionFailed(__METHOD__, "missing envDetails->machines");
		}
		if (empty($envDetails->machines)) {
			throw new E5xx_ActionFailed(__METHOD__, "envDetails->machines cannot be empty");
		}
		foreach($envDetails->machines as $hostId => $machine) {
			// TODO: it would be great to autodetect this one day
			if (!isset($machine->roles)) {
				throw new E5xx_ActionFailed(__METHOD__, "missing envDetails->machines['$hostId']->roles");
			}
		}

		// remove any existing hosts table entry
		foreach ($envDetails->machines as $hostId => $machine) {
			usingHostsTable()->removeHost($hostId);

			// remove any roles
			usingRolesTable()->removeHostFromAllRoles($hostId);
		}

		// there's nothing to start ... we assume that each host is
		// already up and running
		//
		// if it is not, that is NOT our responsibility

		// store the details
		foreach($envDetails->machines as $hostId => $machine)
		{
			// we want all the details from the config file
			$vmDetails = clone $machine;

			// this allows the story to perform actions against a single
			// machine if required
			//
			// that said, there isn't much you can do with a PhysicalHost
			$vmDetails->type        = 'PhysicalHost';

			// remember the name of this machine
			$vmDetails->hostId      = $hostId;

			// mark the box as provisioned
			//
			// this stops Storyplayer thinking that the machine is an
			// invalid host
			$vmDetails->provisioned = true;

			// remember this blackbox
			usingHostsTable()->addHost($vmDetails->hostId, $vmDetails);
			foreach ($vmDetails->roles as $role) {
				usingRolesTable()->addHostToRole($vmDetails, $role);
			}
		}

		// all done
		$log->endAction(count($envDetails->machines) . ' machine(s) registered');
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return void
	 */
	public function startHost($envDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return void
	 */
	public function stopHost($envDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return void
	 */
	public function restartHost($envDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return void
	 */
	public function powerOffHost($envDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return void
	 */
	public function destroyHost($envDetails)
	{
		// what are we doing?
		$log = usingLog()->startAction("de-register blackbox(es)");

		// de-register all the hosts
		foreach ($envDetails->machines as $hostId => $machine)
		{
			foreach ($machine->roles as $role) {
				usingRolesTable()->removeHostFromAllRoles($hostId);
			}
			usingHostsTable()->removeHost($hostId);
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
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @param  string $command
	 * @return CommandResult
	 */
	public function runCommandViaHostManager($envDetails, $command)
	{
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $vmDetails
	 * @return boolean
	 */
	public function isRunning($vmDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}

	/**
	 *
	 * @param  stdClass $envDetails
	 * @return string
	 */
	public function determineIpAddress($envDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "unsupported operation");
	}
}