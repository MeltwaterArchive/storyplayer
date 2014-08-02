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
use Phix_Project\CliEngine\CliSwitch;
use Phix_Project\CliEngine\CliResult;

/**
 * A switch to list the processes we have previously started
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ListProcesses_Switch extends CliSwitch
{
	public function __construct()
	{
		// define the command
		$this->setName('list-processes');
		$this->setShortDescription('list any background processes that are currently running');
		$this->setLongDesc(
			"Use this command to get a list of all of the processes that Storyplayer "
			."has started in the background."
			.PHP_EOL .PHP_EOL
			."This can help you to identify processes that have been left running after "
			."a test has completed."
			.PHP_EOL .PHP_EOL
			."You can use the '--kill-processes' switch to stop these processes."
		);

		// what are the long switches?
		$this->addLongSwitch('list-processes');

		// we are actually a command, pretending to be a switch
		$this->setSwitchActsAsCommand();
	}

	/**
	 *
	 * @param  CliEngine $engine
	 * @param  array     $params
	 * @param  mixed     $additionalContext
	 * @return CliResult
	 */
	public function process(CliEngine $engine, $invokes = 1, $params = array(), $isDefaultParam = false, $additionalContext = null)
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
			if (isset($details->screenName)) {
				echo "{$details->pid}:{$details->processName}:{$details->screenName}\n";
			}
			else {
				echo "{$details->pid}:{$details->processName}\n";
			}
		}

		// all done
		return new CliResult(CliResult::PROCESS_COMPLETE);
	}
}