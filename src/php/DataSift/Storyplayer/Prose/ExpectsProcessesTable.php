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

use DataSift\Storyplayer\HostLib;
use DataSift\Storyplayer\OsLib;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 *
 * test the state of the internal processes table
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ExpectsProcessesTable extends Prose
{
	public function hasEntryForProcess($pid)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure process ID '{$pid}' has an entry in Storyplayer's processes table");

		// get the processes table
		$processesTable = $st->fromProcessesTable()->getProcessesTable();

		// make sure we have the entry
		if (!isset($processesTable->$pid)) {
			$msg = "table does not contain an entry for '{$pid}'";
			$log->endAction($msg);

			throw new E5xx_ExpectFailed(__METHOD__, "processes table has an entry for '{$pid}'", "processes table has no entry for '{$pid}'");
		}

		// all done
		$log->endAction();
	}

	public function hasNoEntryForProcess($pid)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure process ID '{$pid}' has no entry in Storyplayer's processes table");

		// get the processes table
		$processesTable = $st->fromProcessesTable()->getProcessesTable();

		// make sure we do not have the entry
		if (isset($processesTable->$pid)) {
			$msg = "table contains an entry for '{$pid}'";
			$log->endAction($msg);

			throw new E5xx_ExpectFailed(__METHOD__, "processes table has no entry for '{$pid}'", "processes table has an entry for '{$pid}'");
		}

		// all done
		$log->endAction();
	}
}