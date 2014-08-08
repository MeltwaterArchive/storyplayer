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
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\OutputLib;

use DataSift\Storyplayer\PlayerLib\Story_Result;
use DataSift\Storyplayer\PlayerLib\PhaseGroup_Result;

/**
 * the API for output plugins
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
interface OutputPlugin
{
	/**
	 * @param string $version
	 * @param string $url
	 * @param string $copyright
	 * @param string $license
	 * @return void
	 */
	public function startStoryplayer($version, $url, $copyright, $license);

	/**
	 * @return void
	 */
	public function endStoryplayer();

	public function resetSilent();
	public function setSilent();

	/**
	 * @param integer $verbosityLevel
	 * @return void
	 */
	public function setVerbosity($verbosityLevel);

	public function startPhaseGroup($name);
	public function endPhaseGroup($name, PhaseGroup_Result $result);

	/**
	 * @param string $storyName
	 * @param string $storyCategory
	 * @param string $storyGroup
	 * @param string $envName
	 * @param string $deviceName
	 * @return void
	 */
	public function startStory($storyName, $storyCategory, $storyGroup, $envName, $deviceName);

	/**
	 * @return void
	 */
	public function endStory(Story_Result $storyResult);

	/**
	 * @param string $phaseName
	 * @param integer $phaseType
	 * @return void
	 */
	public function startPhase($phaseName, $phaseType);

	/**
	 * @param string $phaseName
	 * @param integer $phaseType
	 * @return void
	 */
	public function endPhase($phaseName, $phaseType);

	/**
	 * @param integer $level
	 * @param string $msg
	 * @return void
	 */
	public function logPhaseActivity($level, $msg);

	/**
	 * @param string $phaseName
	 * @param string $msg
	 * @return void
	 */
	public function logPhaseError($phaseName, $msg);

	/**
	 * @param string $phaseName
	 * @param string $msg
	 * @return void
	 */
	public function logPhaseSkipped($phaseName, $msg);

	/**
	 * @param string $msg
	 *
	 * @return void
	 */
	public function logCliWarning($msg);

	/**
	 * @param string $msg
	 *
	 * @return void
	 */
	public function logCliError($msg);

	/**
	 *
	 * @param  string $msg
	 * @param  Exception $e
	 * @return void
	 */
	public function logCliErrorWithException($msg, $e);

	/**
	 * @param string $msg
	 *
	 * @return void
	 */
	public function logCliInfo($msg);

	/**
	 * @param string $name
	 *
	 * @return void
	 */
	public function logVardump($name, $var);

	/**
	 * called when we start to create a test environment
	 *
	 * @param  string $testEnvName
	 * @return void
	 */
	public function startTestEnvironmentCreation($testEnvName);

	/**
	 * called when we have finished making the test environment
	 *
	 * @param  string $testEnvName
	 * @return void
	 */
	public function endTestEnvironmentCreation($testEnvName);

	/**
	 * called when we start to destroy a test environment
	 *
	 * @param  string $testEnvName
	 * @return void
	 */
	public function startTestEnvironmentDestruction($testEnvName);

	/**
	 * called when we have finished destroying a test environment
	 *
	 * @param  string $testEnvName
	 * @return void
	 */
	public function endTestEnvironmentDestruction($testEnvName);
}
