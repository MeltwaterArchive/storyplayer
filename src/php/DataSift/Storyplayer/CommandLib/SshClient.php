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
 * @package   Storyplayer/CommandLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\CommandLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use Phix_Project\ContractLib2\Contract;

/**
 * helpers for using SSH to interact with the supported operating system
 *
 * @category  Libraries
 * @package   Storyplayer/CommandLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class SshClient
{
	/**
	 *
	 * @var StoryTeller
	 */
	protected $st;

	/**
	 *
	 * @var string
	 */
	protected $ipAddress;

	/**
	 *
	 * @var string
	 */
	protected $sshKey = '';

	/**
	 * @var string
	 */
	protected $sshUsername;

	/**
	 *
	 * @var array<string>
	 */
	protected $sshOptions = [];

	public function __construct(StoryTeller $st, $sshOptions = array())
	{
		// remember for future use
		$this->st = $st;

		// set the default SSH options
		$sshOptions = $this->getDefaultSshOptions();

		// add in the options
		foreach ($sshOptions as $option) {
			$this->addSshOption($option);
		}
	}

	/**
	 *
	 * @return string
	 */
	public function getIpAddress()
	{
		return $this->ipAddress;
	}

	/**
	 *
	 * @return boolean
	 */
	public function hasIpAddress()
	{
		if (empty($this->ipAddress)) {
			return false;
		}

		return true;
	}

	/**
	 *
	 * @param string $ipAddress
	 */
	public function setIpAddress($ipAddress)
	{
		// vet our inputs
		Contract::RequiresValue($ipAddress, is_string($ipAddress));
		Contract::RequiresValue($ipAddress, !empty($ipAddress));

		// save the result
		$this->ipAddress = $ipAddress;
	}

	public function getDefaultSshOptions()
	{
		return [
			'-n' // attach stdin to /dev/null - req to run SSH when not
			     // connected to a terminal
		];
	}

	/**
	 *
	 * @return array<string>
	 */
	public function getSshOptions()
	{
		return $this->sshOptions;
	}

	/**
	 *
	 * @return string
	 */
	public function getSshOptionsForUse()
	{
		return implode(' ', $this->sshOptions);
	}

	/**
	 *
	 * @param string $option
	 * @return void
	 */
	public function addSshOption($option)
	{
		// vet our input
		Contract::RequiresValue($option, is_string($option));
		Contract::RequiresValue($option, !empty($option));

		// add this option to our collection
		$this->sshOptions[] = $option;
	}

	/**
	 *
	 * @return string
	 */
	public function getSshUsername()
	{
		return $this->sshUsername;
	}

	/**
	 *
	 * @return boolean
	 */
	public function hasSshUsername()
	{
		if (empty($this->sshUsername)) {
			return false;
		}

		return true;
	}

	/**
	 *
	 * @param string $username
	 * @return void
	 */
	public function setSshUsername($username)
	{
		// vet our input
		Contract::RequiresValue($username, is_string($username));
		Contract::RequiresValue($username, !empty($username));

		// save the username
		$this->sshUsername = $username;
	}

	/**
	 *
	 * @return string
	 */
	public function getSshKey()
	{
		return $this->sshKey;
	}

	/**
	 *
	 * @return string
	 */
	public function getSshKeyForUse()
	{
		if (empty($this->sshKey)) {
			return '';
		}

		return "-i '" . $this->sshKey . "'";
	}

	/**
	 *
	 * @param string $path
	 * @return void
	 */
	public function setSshKey($path)
	{
		// vet our input
		Contract::RequiresValue($path, is_string($path));
		Contract::RequiresValue($path, !empty($path));

		// set the option
		$this->sshKey = $path;
	}

	/**
	 *
	 * @param  string $params
	 * @return string
	 * @deprecated
	 */
	public function convertParamsForUse($params)
	{
		// vet our input
		Contract::RequiresValue($params, is_string($params));
		// we don't mind if the params are empty

		// our list of what to convert from
		$convertFrom = [
			'\'',
			'*'
		];

		// our list of what to convert to
		$convertTo = [
			'\\\'',
			'\'*\''
		];

		// our return value
		$result = str_replace($convertFrom, $convertTo, $params);

		// all done
		return rtrim($result);
	}

	/**
	 *
	 * @param  string $command
	 * @return CommandResult
	 */
	public function runCommand($command)
	{
		// vet our input
		Contract::RequiresValue($command, is_string($command));
		Contract::RequiresValue($command, !empty($command));

		// shorthand
		$st = $this->st;

		// make the params printable / executable
		// $printableParams = $this->convertParamsForUse($params);

		// what are we doing?
		$log = $st->startAction("run command '{$command}' against host ");

		// do we actually have everything we need to run the command?
		if (!$this->hasSshUsername()) {
			throw new E4xx_NeedSshUsername();
		}
		if (!$this->hasIpAddress()) {
			throw new E4xx_NeedIpAddress();
		}

		// build the full command
		//
		// the options that we pass (by default) to SSH:
		//
		// -o StrictHostKeyChecking=no
		//    do not verify the SSH host key (avoids an interactive prompt)
		// -i <private_key>
		//    use specified private key to access the remote/guest OS
		// <username>@<hostname>
		//    who we are logging in as (should never be root)
		// <additional SSH options>
		//    any other flags, such as -n to force non-interactive session
		// <command> <command-args>
		//    the command to run on the remote/guest OS
		//    (assumes the command will be globbed by the remote shell)
		$fullCommand = 'ssh -o StrictHostKeyChecking=no'
					 . ' ' . $this->getSshKeyForUse()
					 . ' ' . $this->getSshOptionsForUse()
					 . ' ' . $this->getSshUsername() . '@' . $this->getIpAddress()
					 . ' "' . str_replace('"', '\"', $command) . '"';

		// run the command
		//$log->startStep("run command via SSH: {$fullCommand}");
		$commandRunner = $st->getNewCommandRunner();
		$result = $commandRunner->runSilently($st, $fullCommand);
		//$log->endStep();

		// all done
		$log->endAction("return code was '{$result->returnCode}'");
		return $result;
	}
}