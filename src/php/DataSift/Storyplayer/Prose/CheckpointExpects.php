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

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\ProseLib\Prose;
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;

/**
 * Test the contents of the checkpoint
 *
 * This is often better done using one of the assertion objects
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class CheckpointExpects extends Prose
{
	public function equals($expected)
	{
		// shorthand
		$st = $this->st;
		$fieldName = $this->args[0];

		// what are we doing?
		$log = $st->startAction("[ checkpoint field '{$fieldName}' must contain '{$expected}' ]");

		// does this field exist?
		$checkpoint = $st->getCheckpoint();
		if (!isset($checkpoint->$fieldName)) {
			throw new E5xx_ExpectFailed(__METHOD__, "field {$field} exists in checkpoint", "field does not exist");
		}

		// extract the actual value
		$actual = $st->getCheckpoint()->$fieldName;

		if (is_string($expected)) {
			if ($expected !== $actual) {
				throw new E5xx_ExpectFailed(__METHOD__, $expected, $actual);
			}
		}
		else if (is_integer($expected) || is_float($expected)) {
			if ($expected != $actual) {
				throw new E5xx_ExpectFailed(__METHOD__, $expected, $actual);
			}
		}
		else {
			throw new E5xx_ExpectFailed(__METHOD__ , $expected, "Unsupported data type for \$expected");
		}

		// all done
		$log->endAction();
	}

	public function exists()
	{
		// shorthand
		$st = $this->st;
		$fieldName = $this->args[0];

		// what are we doing?
		$log = $st->startAction("[ checkpoint field '{$fieldName}' must exist ]");

		if (!isset($st->getCheckpoint()->$fieldName)) {
			throw new E5xx_ExpectFailed(__METHOD__, 'field exists', 'field does not exist');
		}

		// all done
		$log->endAction();
	}

	/*
	public function hasMatchingElements($expected)
	{
		// we expect all of the elements named in $expected to exist
		// in $actual

		foreach ($expected as $value) {
			if (!in_array($value, $actual)) {
				throw new E5xx_ExpectFailed(__METHOD__, $value, null);
			}
		}
	}
	*/
}