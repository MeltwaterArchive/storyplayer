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

namespace Prose;

/**
 * work with the library of test users
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingUsers extends Prose
{
	public function loadUsersFromFile($filename)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("load test users from '{$filename}'");

		// load the file
		$raw = @file_get_contents($filename);
		if (!$raw || empty($raw)) {
			throw new E5xx_ActionFailed(__METHOD__, "cannot open file '{$filename}' or file is empty");
		}
		$users = @json_decode($raw);
		if (!$users) {
			throw new E5xx_ActionFailed(__METHOD__, "file '{$filename}' contains invalid JSON");
		}
		if (!is_object($users)) {
			throw new E5xx_ActionFailed(__METHOD__, "file '{$filename}' contains no test users");
		}

		$st->setTestUsers($users);
		$st->setTestUsersFilename($filename);

		// all done
		$count = count(get_object_vars($users));
		$log->endAction("loaded {$count} test user(s)");
	}

	public function saveUsersToFile($filename)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("save test users to file '{$filename}'");

		// save the contents
		$users = $st->getTestUsers();
		file_put_contents($filename, json_encode($users));

		// all done
		$count = count(get_object_vars($users));
		$log->endAction("saved {$count} test user(s)");
	}

	public function setUsersFileIsReadOnly()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("mark test users file as read-only");

		// track the state change
		$st->setUsersFileIsReadOnly(true);

		// all done
		$log->endAction();
	}
}