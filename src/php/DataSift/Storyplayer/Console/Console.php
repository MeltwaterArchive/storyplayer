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
use DataSift\Storyplayer\PlayerLib\Story;
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
	 * @param Phase_Result $phaseResult
	 */
	protected function showActivityForPhase(Story $story, Phase_Result $phaseResult)
	{
		// what is the phase that we are dealing with?
		$phaseName = $phaseResult->getPhaseName();

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
		echo <<<EOS

=============================================================
DETAILED ERROR REPORT
----------------------------------------


EOS;

		echo "The story failed in the " . $phaseName . " phase." . PHP_EOL;
		if (count($codePoints) > 0) {
			echo PHP_EOL . "-----" . PHP_EOL
			     . "The story was executing this Prose code when it failed:"
			     . PHP_EOL;

			$codePoints = array_reverse($codePoints);
			foreach ($codePoints as $codePoint) {
				echo PHP_EOL . str_repeat(' ', 4) . $codePoint['file'] . '@' . $codePoint['line'] . ':' . PHP_EOL . PHP_EOL;
				echo CodeFormatter::indentBySpaces($codePoint['code'], 8) . PHP_EOL;

				if (isset($codePoint['args']) && count($codePoint['args'])) {
					echo PHP_EOL . '        Arguments:' . PHP_EOL;
					foreach ($codePoint['args'] as $key => $arg) {
						ob_start();
						var_dump($arg);
						$printableArg = ob_get_contents();
						ob_end_clean();

						// how many lines do we have?
						$lines = explode(PHP_EOL, $printableArg);
						$maxLength = 100;
						if (count($lines) > $maxLength) {
							$printableArg = '';
							for ($i = 0; $i < $maxLength; $i++) {
								$printableArg .= $lines[$i] . PHP_EOL;
							}
							$printableArg .= '...' . PHP_EOL;
						}

						echo PHP_EOL . CodeFormatter::indentBySpaces($printableArg, 12);
					}
				}
			}
		}
		if (isset($this->phaseMessages[$phaseName])) {
			echo PHP_EOL . "-----" . PHP_EOL
			     . "This is the detailed output from the {$phaseName} phase:"
			     . PHP_EOL . PHP_EOL;

			foreach ($this->phaseMessages[$phaseName] as $msg) {
				echo "[" . date("Y-m-d H:i:s", $msg['ts']) . "] "
				     . $this->logLevelStrings[$msg['level']]
                     . $msg['text'] . PHP_EOL;
			}
		}

		if ($trace) {
			echo PHP_EOL . "-----" . PHP_EOL
			     . "This is the stack trace for this failure:"
			     . PHP_EOL . PHP_EOL
			     . CodeFormatter::indentBySpaces($trace, 4) . PHP_EOL;
		}

		// all done
		echo <<< EOS

----------------------------------------
END OF ERROR REPORT
=============================================================

EOS;
	}
}
