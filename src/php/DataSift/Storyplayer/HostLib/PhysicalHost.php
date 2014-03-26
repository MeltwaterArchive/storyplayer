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

use DataSift\Storyplayer\OsLib;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;

/**
 * the things you can do / learn about a physical host
 *
 * @category  Libraries
 * @package   Storyplayer/HostLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class PhysicalHost implements SupportedHost
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
	 * @param  PhysicalHostDetails $vmDetails
	 * @param  array $provisioningVars
	 * @return void
	 */
	public function createHost($vmDetails, $provisioningVars = array())
	{
		throw new E5xx_ActionFailed(__METHOD__, "cannot create a physical host");
	}

	/**
	 *
	 * @param  PhysicalHostDetails $vmDetails
	 * @return void
	 */
	public function startHost($vmDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "cannot start a physical host");
	}

	/**
	 *
	 * @param  PhysicalHostDetails $vmDetails
	 * @return void
	 */
	public function stopHost($vmDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "cannot stop a physical host");
	}

	/**
	 *
	 * @param  PhysicalHostDetails $vmDetails
	 * @return void
	 */
	public function restartHost($vmDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "cannot restart a physical host");
	}

	/**
	 *
	 * @param  PhysicalHostDetails $vmDetails
	 * @return void
	 */
	public function powerOffHost($vmDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "cannot power off a physical host");
	}

	/**
	 *
	 * @param  PhysicalHostDetails $vmDetails
	 * @return void
	 */
	public function destroyHost($vmDetails)
	{
		throw new E5xx_ActionFailed(__METHOD__, "cannot destroy a physical host");
	}

	/**
	 *
	 * @param  PhysicalHostDetails $vmDetails
	 * @return void
	 */
	public function runCommandAgainstHostManager($vmDetails, $command)
	{
		throw new E5xx_ActionFailed(__METHOD__, "no host manager to run commands against");
	}

	/**
	 *
	 * @param  PhysicalHostDetails $vmDetails
	 * @return void
	 */
	public function runCommandViaHostManager($vmDetails, $command)
	{
		throw new E5xx_ActionFailed(__METHOD__, "no host manager to run commands via");
	}

	/**
	 *
	 * @param  PhysicalHostDetails $vmDetails
	 * @return boolean
	 */
	public function isRunning($vmDetails)
	{
		return true;
	}

	/**
	 *
	 * @param  PhysicalHostDetails $vmDetails
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
}