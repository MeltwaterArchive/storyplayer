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
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Phases;

/**
 * the Action phase
 *
 * @category  Libraries
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class CheckBlacklistedPhase extends InternalPrePhase
{
	public function doPhase()
	{
		// shorthand
		$st      = $this->st;
		$story   = $st->getStory();
		$env     = $st->getEnvironment();
		$envName = $st->getEnvironmentName();

		// our result object
		$phaseResult = new PhaseResult();

		// is this story allowed to run on the current environment?
		$blacklistedEnvironment = false;
		if (isset($env->mustBeWhitelisted) && $env->mustBeWhitelisted) {
			// by default, stories are not allowed to run on this environment
			$blacklistedEnvironment = true;

			// is this story allowed to run?
			$whitelistedEnvironments = $story->getWhitelistedEnvironments();
			if (isset($whitelistedEnvironments[$envName]) && $whitelistedEnvironments[$envName]) {
				$blacklistedEnvironment = false;
			}
		}

		// are we allowed to proceed?
		if ($blacklistedEnvironment) {
			// no, we are not
			$phaseResult->setSkipPhases(
				PhaseResult::BLACKLISTED,
				"Cannot run story against the environment '{$envName}'"
			);
			return $phaseResult;
		}

		// if we get here, all is well
		$phaseResult->setContinuePlaying();
		return $phaseResult;
	}
}