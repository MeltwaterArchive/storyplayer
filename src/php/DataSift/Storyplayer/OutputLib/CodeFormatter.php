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

/**
 * helper for producing printable PHP code
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class CodeFormatter
{
	static public function formatCode($parsedCode)
	{
		// special case - make sure we have code to format
		if ($parsedCode === null) {
			return 'no code' . PHP_EOL;
		}

		// we have the parsed code
		// we're going to need something to turn the parsed code back
		// into source code
		$prettyPrinter = new \PhpParser\PrettyPrinter\Standard;

		// convert the parsed code into a string
		//
		// this string may contain more than just the code we are
		// interested in
		$codeToPrint = $prettyPrinter->prettyPrint([$parsedCode]);

		// let's strip stuff
		$codeToPrint = self::stripComments($codeToPrint);

		// all done
		return $codeToPrint;
	}

	static public function stripComments($codeToPrint)
	{
		// now, strip out any comment lines
		$return = trim(preg_replace('|^(//.*)|m', '', $codeToPrint));

		// all done
		return $return;
	}

	static public function indentBySpaces($codeToPrint, $indentAmount)
	{
		// the string to indent with
		$spaces = str_repeat(' ', $indentAmount);

		// do the indentation
		$return = preg_replace('|^(.*)|m', $spaces . '\\1', $codeToPrint);

		// all done
		return $return;

	}
}
