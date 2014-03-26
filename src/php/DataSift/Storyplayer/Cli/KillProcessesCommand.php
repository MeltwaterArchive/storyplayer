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
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use Phix_Project\CliEngine\CliResult;

/**
 * A command to kill any previously started background processes
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class KillProcessesCommand extends CliCommand
{
	public function __construct()
	{
		// define the command
		$this->setName('kill-processes');
		$this->setShortDescription('kill any currently-running background processes');
		$this->setLongDescription(
			"Use this command to stop any background processes that Storyplayer "
			."has previously started in the background."
			.PHP_EOL
		);
	}

	/**
	 *
	 * @param  CliEngine $engine
	 * @param  array     $params
	 * @param  mixed     $additionalContext
	 * @return Phix_Project\CliEngine\CliResult
	 */
	public function processCommand(CliEngine $engine, $params = array(), $additionalContext = null)
	{
		// shorthand
		$runtimeConfig = $additionalContext->runtimeConfig;

		// are there any processes in the table?
		if (!isset($runtimeConfig->processes)) {
			// we're done
			return new CliResult(0);
		}

		// let's walk through the table
		foreach ($runtimeConfig->processes as $details) {
			$this->killProcess($details->pid, $details->processName);
		}

		// all done
		return new CliResult(0);
	}

	/**
	 *
	 * @param  integer $pid
	 * @param  string  $processName
	 * @return void
	 */
	public function killProcess($pid, $processName)
	{
		// is the process running?
		if (!posix_kill($pid, 0)) {
			return;
		}

		echo "Killing $pid ($processName) ... ";
		posix_kill($pid, SIGTERM);
		if (posix_kill($pid, 0)) {
			sleep(1);
			posix_kill($pid, SIGKILL);
		}
		if (posix_kill($pid, 0)) {
			echo "could not kill\n";
		}
		else {
			echo "killed\n";
		}
	}
}