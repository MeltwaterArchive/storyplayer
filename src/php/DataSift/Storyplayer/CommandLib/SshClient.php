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
	protected $st;
	protected $ipAddress;
	protected $sshHostKeyPath;
	protected $sshUsername;
	protected $sshOptions = array("-n");

	public function __construct(StoryTeller $st)
	{
		// remember for future use
		$this->st = $st;
	}

	public function getIpAddress()
	{
		return $this->ipAddress;
	}

	public function setIpAddress($ipAddress)
	{
		$this->ipAddress = $ipAddress;
	}

	public function getSshHostKeyPath()
	{
		return $this->sshHostKeyPath;
	}

	public function getSshHostKeyPathForUse()
	{
		if (isset($this->sshHostKeyPath)) {
			return "-i '" . $this->sshHostKeyPath . "'";
		}

		// no specified host key to use
		return '';
	}

	public function setSshHostKeyPath($path)
	{
		$this->sshHostKeyPath = $path;
	}

	public function getSshOptions()
	{
		return $this->sshOptions;
	}

	public function getSshOptionsForUse()
	{
		return implode(' ', $this->sshOptions);
	}

	public function addSshOption($option)
	{
		$this->sshOptions[] = $option;
	}

	public function getSshUsername()
	{
		return $this->sshUsername;
	}

	public function setSshUsername($username)
	{
		$this->sshUsername = $username;
	}

	public function convertParamsForUse($params = array())
	{
		// our return value
		$result = '';

		// make the params usable
		foreach ($params as $param) {
			// convert to string first
			$stringParam = (string)$param;

			// special case - empty param
			if (strlen($stringParam) == 0) {
				$result .= "'' ";
				continue;
			}

			// general case
			//
			// by default, we want to make sure each parameter will be
			// subject to globbing by the remote shell (mimicing an
			// interactive shell), unless we've been given a hint
			// otherwise
			//
			// escaping any single quotes should (in theory) do the trick
			// just fine

			$result .= str_replace('\'', '\\\'', $stringParam) . ' ';
		}

		// all done
		return rtrim($result);
	}

	public function runCommand($command, $params = array())
	{
		// shorthand
		$st = $this->st;

		// make the params printable / executable
		$printableParams = $this->convertParamsForUse($params);

		// what are we doing?
		$log = $st->startAction("run command '{$command}' with params '{$printableParams}' against host ");

		// the switches we need to pass to SSH
		$sshOptions = explode(' ', $this->getSshOptions());

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
					 . " " . $this->getSshHostKeyPathForUse()
					 . $this->getSshUsername() . '@' . $this->getIpAddress()
					 . ' ' . $this->getSshOptionsForUse()
					 . " '" . $command . ' ' . $printableParams . "'";

		// run the command
		$output     = null;
		$returnCode = null;
		$log->addStep("run command via SSH: '{$fullCommand}'", function() use($fullCommand, &$output, &$returnCode) {
			$output = passthru($fullCommand, $returnCode);
		});

		// all done
		$log->endAction("return code was '{$returnCode}'; output was '{$output}'");

		$return = new CommandResult($returnCode, $output);
		return $return;
	}
}