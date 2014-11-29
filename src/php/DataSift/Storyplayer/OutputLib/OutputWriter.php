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

use Phix_Project\ConsoleDisplayLib4\ConsoleColor;

/**
 * represents something that we want to send output content to
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class OutputWriter
{
    public $argStyle = null;
    public $commandStyle = null;
    public $commentStyle = null;
    public $errorStyle = null;
    public $exampleStyle = null;
    public $highlightStyle = null;
    public $normalStyle = null;
    public $switchStyle = null;
    public $urlStyle = null;
    public $successStyle = null;
    public $failStyle = null;
    public $skippedStyle = null;
    public $nameStyle = null;
    public $durationStyle = null;
    public $punctuationStyle = null;
    public $miniActivityStyle = null;
    public $miniPhaseNameStyle = null;
    public $timeStyle = null;
    public $successSummaryStyle = null;
    public $failSummaryStyle = null;
    public $puzzledSummaryStyle = null;
    public $argumentsHeadingStyle = null;
    public $failedPhaseStyle = null;

	protected $outputHandles  = [];

	public function __construct()
	{
		// generate our colour styles
		$this->setupColourStyles();
	}

	// ==================================================================
	//
	// Support for outputting to various places
	//
	// ------------------------------------------------------------------

	public function addOutputToStdout()
	{
		$handle = fopen('php://stdout', 'w');
		$colour = true;

		// special case - check for writing to a pipe
		if (!function_exists('posix_isatty') || !posix_isatty($handle)) {
			$colour = false;
		}

		$this->outputHandles['stdout'] = [
			'handle' => $handle,
			'colour' => $colour
		];
	}

	public function addOutputToStderr()
	{
		$handle = fopen('php://stderr', 'w');
		$colour = true;

		// special case - check for writing to a pipe
		if (!function_exists('posix_isatty') || !posix_isatty($handle)) {
			$colour = false;
		}

		$this->outputHandles['stderr'] = [
			'handle' => $handle,
			'colour' => $colour
		];
	}

	public function addOutputToFile($filename)
	{
		// can we open the file?
		$fp = fopen($filename, 'w');
		if (!$fp) {
			throw new E4xx_CannotOpenOutputFile($filename);
		}

		// add the file to our list to write to
		$this->outputHandles[$filename] = [
			'handle' => $fp,
			'colour' => false
		];
	}

	public function write($output, $style = null)
	{
		foreach ($this->outputHandles as $outputHandle) {
			// do we need to colour the output?
			if ($style && $outputHandle['colour']) {
				$msg = $this->colourize($output, $style);
			}
			else {
				$msg = $output;
			}

			// send the output
			fwrite($outputHandle['handle'], $msg);

			// force the output to appear, in case where we are sending
			// it to has some sort of buffering in operation
			fflush($outputHandle['handle']);
		}
	}

	// ==================================================================
	//
	// Colour support
	//
	// ------------------------------------------------------------------

	public function setColourMode($mode)
	{
		switch ($mode)
		{
			case OutputPlugin::COLOUR_MODE_OFF:
				foreach ($this->outputHandles as $index => $outputHandle) {
					if ($outputHandle['colour']) {
						$this->outputHandles[$index]['colour'] = false;
					}
				}
				break;

			case OutputPlugin::COLOUR_MODE_ON:
				foreach ($this->outputHandles as $index => $outputHandle) {
					switch ($index)
					{
						case 'stdout':
						case 'stderr':
							$this->outputHandles[$index]['colour'] = true;
					}
				}
				break;
		}
	}

    protected function setupColourStyles()
    {
        // set the colours to use for our styles
        $this->argStyle = array(ConsoleColor::BOLD, ConsoleColor::BLUE_FG);
        $this->commandStyle = array(ConsoleColor::BOLD, ConsoleColor::GREEN_FG);
        $this->commentStyle = array(ConsoleColor::BOLD, ConsoleColor::GRAY_FG);
        $this->errorStyle = array(ConsoleColor::BOLD, ConsoleColor::RED_FG);
        $this->exampleStyle = array(ConsoleColor::BOLD, ConsoleColor::YELLOW_FG);
        $this->highlightStyle = array(ConsoleColor::BOLD, ConsoleColor::GREEN_FG);
        $this->normalStyle = array(ConsoleColor::NONE);
        $this->switchStyle = array(ConsoleColor::BOLD, ConsoleColor::YELLOW_FG);
        $this->urlStyle = array(ConsoleColor::BOLD, ConsoleColor::BLUE_FG);

        $this->successStyle = array(ConsoleColor::GREEN_FG);
        $this->failStyle = array(ConsoleColor::RED_FG);
        $this->skippedStyle = array(ConsoleColor::YELLOW_FG);
        $this->activityStyle = [ConsoleColor::GREEN_FG];
        $this->nameStyle = [ConsoleColor::WHITE_FG];
        $this->durationStyle = [ConsoleColor::YELLOW_FG];
        $this->punctuationStyle = [ConsoleColor::BOLD, ConsoleColor::GRAY_FG];
        $this->miniActivityStyle = [ConsoleColor::BOLD, ConsoleColor::GRAY_FG];
        $this->miniPhaseNameStyle = [ConsoleColor::BOLD, ConsoleColor::GRAY_FG];
        $this->timeStyle = [ConsoleColor::YELLOW_FG];

        $this->successSummaryStyle = [ConsoleColor::GREEN_BG, ConsoleColor::BLACK_FG];
        $this->failSummaryStyle = [ConsoleColor::RED_BG, ConsoleColor::WHITE_FG];
        $this->puzzledSummaryStyle = [ConsoleColor::YELLOW_BG, ConsoleColor::BLACK_FG];

        $this->argumentsHeadingStyle = [ConsoleColor::YELLOW_FG];
        $this->failedPhaseStyle = [ConsoleColor::GREEN_FG];
    }

	protected function colourize($output, $style)
	{
		// if we get here, then yes we do
        return sprintf(ConsoleColor::ESCAPE_SEQUENCE, \implode(';', $style))
               . $output
               . sprintf(ConsoleColor::ESCAPE_SEQUENCE, ConsoleColor::NONE);
	}

	// ==================================================================
	//
	// Additional helpers for testing etc
	//
	// ------------------------------------------------------------------

	public function getIsUsingStdout()
	{
		if (isset($this->outputHandles['stdout'])) {
			return true;
		}

		return false;
	}

	public function getIsUsingStderr()
	{
		if (isset($this->outputHandles['stderr'])) {
			return true;
		}

		return false;
	}

	public function getIsUsingOutputFile($filename)
	{
		if (isset($this->outputHandles[$filename])) {
			return true;
		}

		return false;
	}
}
