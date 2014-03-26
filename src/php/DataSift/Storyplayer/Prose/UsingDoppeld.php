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

/**
 * Do things with doppelgangerd - DataSift's mocking daemon
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingDoppeld extends Prose
{
	public function start($testCaseName, $testCase)
	{
		// shorthand
		$st  = $this->st;
		$env = $st->getEnvironment();

		// what are we doing?
		$log = $st->startAction("start doppeld with test scenario '{$testCaseName}' ({$testCase})");

		// make sure this environment is configured for doppeld
		if (!isset($env->doppeld)) {
			throw new E5xx_ActionFailed(__METHOD__, "environment has no configuration for doppeld");
		}
		if (!isset($env->doppeld->dir)) {
			throw new E5xx_ActionFailed(__METHOD__, "doppeld configuration has no 'dir' setting");
		}
		$doppelDir = $env->doppeld->dir;

		// build up the command to run
		$command = "cd '{$doppelDir}' && node ./server.js {$testCase}";

		// run the command
		$log->addStep("start doppeld with command '{$command}'", function() use($st, $command, $testCaseName) {
			$st->usingShell()->startInScreen($testCaseName, $command);

			// wait before continuing
			sleep(1);
		});

		// make sure that it's running
		$st->expectsShell()->isRunningInScreen($testCaseName);

		// all done
		$log->endAction();
	}

	public function stop($testCaseName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop doppeld running test scenario '{$testCaseName}'");

		// stop it
		$st->usingShell()->stopInScreen($testCaseName);

		// all done
		$log->endAction();
	}
}