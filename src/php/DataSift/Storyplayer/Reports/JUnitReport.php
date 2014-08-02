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
 * @package   Storyplayer/Reports
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Reports;

use DataSift\Storyplayer\PlayerLib\Story_Result;

/**
 * the plugin for JUnit-style reporting
 *
 * @category  Libraries
 * @package   Storyplayer/Reports
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class JUnitReport implements Report
{
	protected $filename;
	protected $testCount = 0;
	protected $tests = [];
	protected $verbosityLevel = 0;

	public function __construct($params)
	{
		$this->filename = $params['filename'];
	}

	/**
	 * @param string $version
	 * @param string $url
	 * @param string $copyright
	 * @param string $license
	 * @return void
	 */
	public function startStoryplayer($version, $url, $copyright, $license)
	{

	}

	/**
	 * @return void
	 */
	public function endStoryplayer()
	{
		// right, now we need to report on what we've seen
		$fp = fopen($this->filename, "w");
		if (!$fp) {
			throw new E5xx_CannotCreateReportFile($this->filename);
		}

		// write out the header
		fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
		fwrite($fp, "<testsuites>" . PHP_EOL);

		// write out each test in turn
		foreach ($this->tests as $storyResult) {
			switch ($storyResult->resultCode) {
				case $storyResult::PASS:
					$this->writeOkay($fp, $storyResult, 'Pass');
					break;

				case $storyResult::FAIL:
					$this->writeNotOkay($fp, $storyResult, 'Fail');
					break;

				case $storyResult::INCOMPLETE:
					$this->writeNotOkay($fp, $storyResult, 'Incomplete');
					break;

				case $storyResult::BLACKLISTED:
					$this->writeOkay($fp, $storyResult, 'Blacklisted');
					break;

				case $storyResult::ERROR:
				default:
					$this->writeNotOkay($fp, $storyResult, 'Error');
					break;
			}
		}

		// all done
		fclose($fp);
	}

	public function setVerbosity($verbosityLevel)
	{
		// doesn't really affect report
		$this->verbosityLevel = $verbosityLevel;
	}

	/**
	 * @param string $storyName
	 * @param string $storyCategory
	 * @param string $storyGroup
	 * @param string $envName
	 * @param string $deviceName
	 * @return void
	 */
	public function startStory($storyName, $storyCategory, $storyGroup, $envName, $deviceName)
	{
		// keep track of how many tests we have seen
		$this->testCount++;
	}

	/**
	 * @return void
	 */
	public function endStory(Story_Result $storyResult)
	{
		$this->tests[] = $storyResult;
	}

	/**
	 * @param string $phaseName
	 * @param integer $phaseType
	 * @return void
	 */
	public function startPhase($phaseName, $phaseType)
	{

	}

	/**
	 * @param string $phaseName
	 * @param integer $phaseType
	 * @return void
	 */
	public function endPhase($phaseName, $phaseType)
	{

	}

	/**
	 * @param integer $level
	 * @param string $msg
	 * @return void
	 */
	public function logPhaseActivity($level, $msg)
	{

	}

	/**
	 * @param string $phaseName
	 * @param string $msg
	 * @return void
	 */
	public function logPhaseError($phaseName, $msg)
	{

	}

	/**
	 * @param string $phaseName
	 * @param string $msg
	 * @return void
	 */
	public function logPhaseSkipped($phaseName, $msg)
	{

	}

	/**
	 * @param string $msg
	 *
	 * @return void
	 */
	public function logCliWarning($msg)
	{

	}

	/**
	 * @param string $msg
	 *
	 * @return void
	 */
	public function logCliError($msg)
	{

	}

	/**
	 * @param string $msg
	 *
	 * @return void
	 */
	public function logCliInfo($msg)
	{

	}

	/**
	 * @param string $name
	 *
	 * @return void
	 */
	public function logVardump($name, $var)
	{

	}

	protected function writeOkay($fp, $storyCounter, $storyResult, $reason)
	{
		fwrite($fp, 'ok ' . $storyCounter . ' - ' . $reason . ': ' . $storyResult->story->getName() . PHP_EOL);
	}

	protected function writeNotOkay($fp, $storyCounter, $storyResult, $reason)
	{
		fwrite($fp, 'not ok ' . $storyCounter . ' - ' . $reason . ': ' . $storyResult->story->getName() . PHP_EOL);
	}
}
