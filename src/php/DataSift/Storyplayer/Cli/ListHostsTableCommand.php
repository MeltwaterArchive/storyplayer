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
 * A command to list the
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ListHostsTableCommand extends CliCommand
{
	public function __construct()
	{
		// define the command
		$this->setName('list-hoststable');
		$this->setShortDescription('list the current contents of the hoststable');
		$this->setLongDescription(
			"Use this command to get a list of all of the machines (physical or VM)"
			. " that are currently listed in Storyplayer's hoststable."
			.PHP_EOL .PHP_EOL
			."This can help you to identify VMs that have been left running after "
			."a test has completed."
			.PHP_EOL
		);
		$this->setSwitches(array(
			new HostTypeSwitch("list only hosts of a given type", "a comma-separated list of the types of hosts to include in the output")
		));
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

		// are there any hosts in the table?
		if (!isset($runtimeConfig->hosts)) {
			// we're done
			return new CliResult(0);
		}

		// let's walk through the table
		foreach ($runtimeConfig->hosts as $hostName => $details) {
			// is this in the list we are filtering against?
			if (!in_array(strtolower($details->type), $engine->options->hosttype)) {
				continue;
			}

			echo "{$details->name}:{$details->ipAddress}:{$details->type}:{$details->osName}\n";
		}

		// all done
		return new CliResult(0);
	}
}