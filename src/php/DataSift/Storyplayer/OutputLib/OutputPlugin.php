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
 * the base class for output plugins
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
abstract class OutputPlugin
{
	protected $writer = null;

	public function __construct()
	{
		$this->writer = new OutputWriter();
	}

	// ==================================================================
	//
	// Support for outputting to various places
	//
	// ------------------------------------------------------------------

	public function addOutputToStdout()
	{
		$this->writer->addOutputToStdout();
	}

	public function addOutputToStderr()
	{
		$this->writer->addOutputToStderr();
	}

	public function addOutputFile($filename)
	{
		// make sure $filename isn't a reserved name
		switch($filename)
		{
			case 'stdout':
			case 'stderr':
			case 'null':
				throw new E4xx_OutputFilenameIsAReservedName($filename);
		}

		$this->writer->addOutputFile($filename);
	}

	public function write($output, $style = null)
	{
		$this->writer->write($output, $style);
	}

	// ==================================================================
	//
	// These are the methods that Storyplayer will call as things
	// happen ...
	//
	// ------------------------------------------------------------------

	/**
	 * @param string $version
	 * @param string $url
	 * @param string $copyright
	 * @param string $license
	 * @return void
	 */
	abstract public function startStoryplayer($version, $url, $copyright, $license);

	/**
	 * @return void
	 */
	abstract public function endStoryplayer();

	abstract public function resetSilent();
	abstract public function setSilent();

	abstract public function startPhaseGroup($name);
	abstract public function endPhaseGroup($name, PhaseGroup_Result $result);

	/**
	 * @param string $storyName
	 * @param string $storyCategory
	 * @param string $storyGroup
	 * @param string $envName
	 * @param string $deviceName
	 * @return void
	 */
	abstract public function startStory($storyName, $storyCategory, $storyGroup, $envName, $deviceName);

	/**
	 * @return void
	 */
	abstract public function endStory(Story_Result $storyResult);

	/**
	 * @param string $phaseName
	 * @param integer $phaseType
	 * @return void
	 */
	abstract public function startPhase($phaseName, $phaseType);

	/**
	 * @param string $phaseName
	 * @param integer $phaseType
	 * @return void
	 */
	abstract public function endPhase($phaseName, $phaseType);

	/**
	 * @param string $msg
	 * @return void
	 */
	abstract public function logPhaseActivity($msg);

	/**
	 * @param string $phaseName
	 * @param string $msg
	 * @return void
	 */
	abstract public function logPhaseError($phaseName, $msg);

	/**
	 * @param string $phaseName
	 * @param string $msg
	 * @return void
	 */
	abstract public function logPhaseSkipped($phaseName, $msg);

	/**
	 * @param string $msg
	 *
	 * @return void
	 */
	abstract public function logCliWarning($msg);

	/**
	 * @param string $msg
	 *
	 * @return void
	 */
	abstract public function logCliError($msg);

	/**
	 *
	 * @param  string $msg
	 * @param  Exception $e
	 * @return void
	 */
	abstract public function logCliErrorWithException($msg, $e);

	/**
	 * @param string $msg
	 *
	 * @return void
	 */
	abstract public function logCliInfo($msg);

	/**
	 * @param string $name
	 *
	 * @return void
	 */
	abstract public function logVardump($name, $var);
}
