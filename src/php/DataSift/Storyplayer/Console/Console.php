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
 * @package   Storyplayer/Console
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Console;

use DataSift\Storyplayer\PlayerLib\Phase_Result;
use DataSift\Storyplayer\PlayerLib\PhaseGroup_Result;
use DataSift\Storyplayer\PlayerLib\Story;
use DataSift\Storyplayer\PlayerLib\Story_Result;
use DataSift\Storyplayer\OutputLib\CodeFormatter;
use DataSift\Storyplayer\OutputLib\OutputPlugin;

/**
 * the API for console plugins
 *
 * @category  Libraries
 * @package   Storyplayer/Console
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
abstract class Console extends OutputPlugin
{
	/**
	 *
	 * @var array(PhaseGroup_Result)
	 */
	protected $results;

	/**
	 * called when Storyplayer exits
	 *
	 * @return void
	 */
	public function writeFinalReport($duration, $summary)
	{
		// keep count of what the final results are
		$succeededGroups = [];
		$skippedGroups   = [];
		$failedGroups    = [];

		// do we have any results?
		if (!isset($this->results)) {
			// huh - nothing happened at all
			$this->write("HUH - nothing appears to have happened. Time taken: ", $this->writer->puzzledSummaryStyle);
			$this->writeDuration($duration, $this->writer->puzzledSummaryStyle);
			$this->write(PHP_EOL . PHP_EOL);
			return;
		}

		// this is our opportunity to tell the user how our story(ies)
		// went in detail
		foreach ($this->results as $result)
		{
			// so what happened?
			switch ($result->resultCode)
			{
				case PhaseGroup_Result::OKAY:
					// this is a good result
					$succeededGroups[] = $result;
					break;

				case PhaseGroup_Result::SKIPPED:
				case PhaseGroup_Result::BLACKLISTED:
					// this can legitimately happen
					$skippedGroups[] = $result;
					break;

				default:
					// everything else is an error of some kind
					$failedGroups[] = $result;
			}
		}

		// what's the final tally?
		$this->write(PHP_EOL);
		if (empty($succeededGroups) && empty($skippedGroups) && empty($failedGroups)) {
			// huh - nothing happened at all
			$this->write("HUH - nothing appears to have happened. Time taken: ", $this->writer->puzzledSummaryStyle);
			$this->writeDuration($duration, $this->writer->puzzledSummaryStyle);
			$this->write(PHP_EOL . PHP_EOL);
			return;
		}
		if (empty($failedGroups)) {
			// nothing failed
			$this->write("SUCCESS - " . count($succeededGroups) . ' PASSED, ' . count($skippedGroups) . ' SKIPPED. Time taken: ', $this->writer->successSummaryStyle);
			$this->writeDuration($duration, $this->writer->successSummaryStyle);
			$this->write(PHP_EOL . PHP_EOL);
			return;
		}

		// if we get here, then at least one thing failed
		$this->write("FAILURE - "
			. count($succeededGroups) . ' PASSED, '
			. count($skippedGroups) . ' SKIPPED, '
			. count($failedGroups) . ' FAILED :( Time taken: ',
			$this->writer->failSummaryStyle);
		$this->writeDuration($duration, $this->writer->failSummaryStyle);
		$this->write(PHP_EOL . PHP_EOL);

		// write out a list of failed tests - someone will want to look
		// at them in detail
		$this->write("Here's the list of everything that failed:" . PHP_EOL . PHP_EOL);
		// foreach ($skippedGroups as $skippedGroup) {
		// 	$this->writePhaseGroupSkipped();
		// 	$this->write(' ' . $skippedGroup->activity, $this->writer->activityStyle);
		// 	$this->write(' ' . $skippedGroup->name . PHP_EOL, $this->writer->nameStyle);

		// 	if (isset($skippedGroup->filename)) {
		// 		$this->write('       (', $this->writer->punctuationStyle);
		// 		$this->write($skippedGroup->filename, $this->writer->punctuationStyle);
		// 		$this->write(')' . PHP_EOL, $this->writer->punctuationStyle);
		// 	}
		// }
		foreach ($failedGroups as $failedGroup) {
			$this->writePhaseGroupFailed();
			$this->write(' ' . $failedGroup->activity, $this->writer->activityStyle);
			$this->write(' ' . $failedGroup->name . PHP_EOL, $this->writer->nameStyle);

			if (isset($failedGroup->filename)) {
				$this->write('       (', $this->writer->punctuationStyle);
				$this->write($failedGroup->filename, $this->writer->punctuationStyle);
				$this->write(')' . PHP_EOL, $this->writer->punctuationStyle);
			}
		}
		$this->write(PHP_EOL);

		// do we stop here?
		if (!$summary) {
			// we're in dev mode, which means the error reports have
			// already been shown where they happened
			return;
		}

		// are we being run by hand?
		if (function_exists("posix_isatty") && posix_isatty(STDOUT)) {
			$this->write("See ");
			$this->write("storyplayer.log", $this->writer->argStyle);
			$this->write(" for details on what went wrong." . PHP_EOL . PHP_EOL);
		}
	}

	protected function writeDetailedErrorReport($result)
	{
		// did anything go wrong?
		if ($result->getPhaseGroupSucceeded() || $result->getPhaseGroupSkipped()) {
			// everything is fine
			return;
		}

		// sanity check: we should always have a failedPhase
		if (!$result->failedPhase instanceof Phase_Result) {
			throw new E5xx_MissingFailedPhase();
		}

		// is it a story?
		if ($result instanceof Story_Result) {
			$this->showActivityForPhase($result->story, $result->failedPhase);
		}
	}

	protected function writeActivity($message, $codeLine = null, $timestamp = null)
	{
		// when did this happen?
		if (!$timestamp) {
			$timestamp = time();
		}

		// prepare date/time for output
        $now = date('Y-m-d H:i:s', $timestamp);

        // do we have a codeLine to output?
        if ($codeLine) {
	        $this->write('[', $this->writer->punctuationStyle);
	        $this->write($now, $this->writer->timeStyle);
	        $this->write('] ', $this->writer->punctuationStyle);

	        // prepare the code for output
	        //
	        // if the code is longer than a single line, we want to display
	        // it on its own line, to make the output more readable
	        $parts = explode("\n", $codeLine['code']);
	        if (count($parts) > 1) {
	        	$codeLine['code'] = PHP_EOL . $codeLine['code'];
	        }
	        else {
	        	$codeLine['code'] = ' ' . $codeLine['code'];
	        }

	        // how many spaces do we need to write?
	        $shorterMsg = trim($message);
	        $indent = strlen($message) - strlen($shorterMsg);
	        $this->write(str_repeat(" ", $indent));
	        $this->write("code --", $this->writer->commentStyle);
        	$this->writeCodePointCode($codeLine, $this->writer->commentStyle);
        	$this->write(PHP_EOL);
        }

		// output date/time
        $this->write('[', $this->writer->punctuationStyle);
        $this->write($now, $this->writer->timeStyle);
        $this->write('] ', $this->writer->punctuationStyle);

        // output the message
        $this->write(rtrim($message) . PHP_EOL);
	}

	protected function writePhaseGroupSucceeded($msg = 'OKAY')
	{
		$this->write('[', $this->writer->punctuationStyle);
		$this->write($msg, $this->writer->successStyle);
		$this->write(']', $this->writer->punctuationStyle);
	}

	protected function writePhaseGroupFailed($msg = 'FAIL')
	{
		$this->write('[', $this->writer->punctuationStyle);
		$this->write($msg, $this->writer->failStyle);
		$this->write(']', $this->writer->punctuationStyle);
	}

	protected function writePhaseGroupSkipped($msg = 'SKIP')
	{
		$this->write('[', $this->writer->punctuationStyle);
		$this->write($msg, $this->writer->skippedStyle);
		$this->write(']', $this->writer->punctuationStyle);
	}

	/**
	 * @param Phase_Result $phaseResult
	 */
	protected function showActivityForPhase(Story $story, Phase_Result $phaseResult)
	{
		// what is the phase that we are dealing with?
		$phaseName = $phaseResult->getPhaseName();
		$activityLog = $phaseResult->activityLog;

		// our final messages to show the user
		$codePoints = [];
		$trace = null;

		// does the phase have an exception?
		$e = $phaseResult->getException();
		if ($e)
		{
			$stackTrace = $e->getTrace();
			foreach ($stackTrace as $stackEntry)
			{
				if (!isset($stackEntry['file'])) {
					continue;
				}

				// do we have any code for this?
				$code = $story->getStoryCodeFor($stackEntry['file'], $stackEntry['line']);
				if (!$code) {
					continue;
				}

				// because we chain multiple method calls on a single line,
				// a PHP stack entry can contain duplicate entries
				//
				// we don't want to show duplicate entries, so we use the
				// filename@line as a key in the array
				$key = $stackEntry['file'] . '@' . $stackEntry['line'];
				if (count($codePoints) > 0 && end($codePoints)['key'] == $key) {
					$codePoint = end($codePoints);
					if ($stackEntry['function'] == '__call') {
						$codePoint['args'] += $stackEntry['args'][1];
					}
					else {
						$codePoint['args'] += $stackEntry['args'];
					}
					$codePoints[count($codePoints) - 1] = $codePoint;
				}
				else {
					$codePoint = $stackEntry;
					$codePoint['code'] = CodeFormatter::formatCode($code);
					$codePoint['key']  = $key;

					// deal with magic
					if ($codePoint['function'] == '__call') {
						$codePoint['args'] = $codePoint['args'][1];
					}

					$codePoints[] = $codePoint;
				}
			}

			$trace = $e->getTraceAsString();
		}

		// let's tell the user what we found
		$this->write("=============================================================" . PHP_EOL, $this->writer->commentStyle);
		$this->write("DETAILED ERROR REPORT" . PHP_EOL);
		$this->write("----------------------------------------" . PHP_EOL . PHP_EOL, $this->writer->commentStyle);

		$this->write("The story failed in the ");
		$this->write($phaseName, $this->writer->failedPhaseStyle);
		$this->write(" phase." . PHP_EOL);

		if (count($codePoints) > 0)
		{
			$this->write(PHP_EOL . "-----" . PHP_EOL, $this->writer->commentStyle);
			$this->write("The story was executing this Prose code when it failed:". PHP_EOL);

			$codePoints = array_reverse($codePoints);
			foreach ($codePoints as $codePoint) {
				$this->write(PHP_EOL . str_repeat(' ', 4));
				$this->writeCodePointFile($codePoint);
				$this->write(':' . PHP_EOL . PHP_EOL);
				$this->write('        ');
				$this->writeCodePointCode($codePoint);
				$this->write(PHP_EOL);

				// unfortunately, I've had to comment this out
				//
				// it's just far too easy to end up with a variable that
				// can't be var_dump()ed without running out of RAM :(
				//
				// if (isset($codePoint['args']) && count($codePoint['args'])) {
				// 	$this->write(PHP_EOL . '        Arguments:' . PHP_EOL, $this->writer->argumentsHeadingStyle);
				// 	foreach ($codePoint['args'] as $key => $arg) {
				// 		if (is_object($arg) && get_class($arg) == 'DataSift\Storyplayer\PlayerLib\Storyteller') {
				// 			$printableArg = '$st';
				// 		}
				// 		else {
				// 			ob_start();
				// 			var_dump($arg);
				// 			$printableArg = ob_get_contents();
				// 			ob_end_clean();
				// 		}

				// 		// how many lines do we have?
				// 		$lines = explode(PHP_EOL, $printableArg);
				// 		$maxLength = 100;
				// 		if (count($lines) > $maxLength) {
				// 			$printableArg = '';
				// 			for ($i = 0; $i < $maxLength; $i++) {
				// 				$printableArg .= $lines[$i] . PHP_EOL;
				// 			}
				// 			$printableArg .= '...' . PHP_EOL;
				// 		}

				// 		$this->write(PHP_EOL . CodeFormatter::indentBySpaces($printableArg, 12));
				// 	}
				// }
			}
		}
		if (!empty($activityLog)) {
			$this->write(PHP_EOL . "-----" . PHP_EOL, $this->writer->commentStyle);
			$this->write("This is the detailed output from the ");
			$this->write($phaseName, $this->writer->failedPhaseStyle);
			$this->write(" phase:" . PHP_EOL . PHP_EOL);

			foreach ($activityLog as $msg) {
				$this->writeActivity($msg['text'], $msg['codeLine'], $msg['ts']);
			}
		}

		if ($trace) {
			$this->write(PHP_EOL . "-----" . PHP_EOL, $this->writer->commentStyle);
			$this->write("This is the stack trace for this failure:"
			     . PHP_EOL . PHP_EOL
			     . CodeFormatter::indentBySpaces($trace, 4) . PHP_EOL . PHP_EOL);
		}

		// all done
		$this->write("----------------------------------------" . PHP_EOL, $this->writer->commentStyle);
		$this->write("END OF ERROR REPORT" . PHP_EOL);
		$this->write("=============================================================" . PHP_EOL . PHP_EOL, $this->writer->commentStyle);
	}

	protected function writeCodePointFile($codeLine)
	{
		$this->write($codeLine['file'], $this->writer->activityStyle);
		$this->write('@', $this->writer->punctuationStyle);
		$this->write($codeLine['line'], $this->writer->activityStyle);
	}

	protected function writeCodePointCode($codeLine, $style = null)
	{
		$this->write(rtrim($codeLine['code'] . PHP_EOL), $style);
	}
}