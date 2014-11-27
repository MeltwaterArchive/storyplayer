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
 * @package   Storyplayer/OsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\OsLib;

use DataSift\Storyplayer\CommandLib\SshClient;
use DataSift\Storyplayer\HostLib\SupportedHost;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * the things you can do / learn about a machine running one of our
 * supported operatating systems
 *
 * @category  Libraries
 * @package   Storyplayer/OsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
abstract class OsBase implements SupportedOs
{
	/**
	 *
	 * @var DataSift\Storyplayer\PlayerLib\StoryTeller;
	 */
	protected $st;

	public function __construct(StoryTeller $st)
	{
		// remember for future use
		$this->st = $st;
	}

	/**
	 *
	 * @param  HostDetails $hostDetails
	 * @param  SupportedHost $host
	 * @return string
	 */
	abstract public function determineIpAddress($hostDetails, SupportedHost $host);

	/**
	 *
	 * @param  HostDetails $hostDetails
	 * @param  string $packageName
	 * @return BaseObject
	 */
	abstract public function getInstalledPackageDetails($hostDetails, $packageName);

	/**
	 *
	 * @param  HostDetails $hostDetails
	 * @param  string $processName
	 * @return boolean
	 */
	abstract public function getProcessIsRunning($hostDetails, $processName);

	/**
	 *
	 * @param  HostDetails $hostDetails
	 * @param  string $processName
	 * @return integer
	 */
	abstract public function getPid($hostDetails, $processName);

	/**
	 * @param HostDetails $hostDetails
	 * @param string $command
	 *
	 * @return \DataSift\Storyplayer\CommandLib\CommandResult
	 */
	public function runCommand($hostDetails, $command)
	{
		// get an SSH client
		$client = $this->getClient($this->st, $hostDetails);

		// run the command
		return $client->runCommand($command);
	}
}