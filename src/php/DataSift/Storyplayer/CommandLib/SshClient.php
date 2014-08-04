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
	protected $sshOptions = array("-n");

	public function __construct(StoryTeller $st, $sshOptions = array())
	{
		// remember for future use
		$this->st = $st;

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
	 * @param string $ipAddress
	 */
	public function setIpAddress($ipAddress)
	{
		$this->ipAddress = $ipAddress;
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
	 * @param string $username
	 * @return void
	 */
	public function setSshUsername($username)
	{
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
	 * @param string $path
	 * @return void
	 */
	public function setSshKey($path)
	{
		$this->sshKey = "-i '{$path}'";
	}

	/**
	 *
	 * @param  string $params
	 * @return string
	 * @deprecated
	 */
	public function convertParamsForUse($params)
	{
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
		// shorthand
		$st = $this->st;

		// make the params printable / executable
		// $printableParams = $this->convertParamsForUse($params);

		// what are we doing?
		$log = $st->startAction("run command '{$command}' against host ");

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
					 . ' ' . $this->getSshKey()
					 . ' ' . $this->getSshOptionsForUse()
					 . ' ' . $this->getSshUsername() . '@' . $this->getIpAddress()
					 . ' "' . str_replace('"', '\"', $command) . '"';

		// run the command
		$result = $log->addStep("run command via SSH: {$fullCommand}", function() use($st, $fullCommand) {
			$commandRunner = new CommandRunner();
			return $commandRunner->runSilently($st, $fullCommand);
		});

		// all done
		$log->endAction("return code was '{$result->returnCode}'");
		return $result;
	}
}