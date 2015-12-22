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

use DataSift\Stone\ObjectLib\BaseObject;

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
    /**
     * load test users from the given filename
     *
     * NOTES:
     *
     * - Storyplayer calls this for you when you use the --users switch
     *
     * - it is safe to call this yourself from a story if you want to load
     *   additional users for any reason. however, Storyplayer will not manage
     *   saving these users for you - you WILL have to do that yourself
     *
     * @param  string $filename
     *         the JSON file to load users from
     * @return \DataSift\Stone\ObjectLib\BaseObject
     */
    public function loadUsersFromFile($filename)
    {
        // what are we doing?
        $log = usingLog()->startAction("load test users from '{$filename}'");

        // load the file
        $raw = @file_get_contents($filename);
        if (!$raw || empty($raw)) {
            throw Exceptions::newActionFailedException(__METHOD__, "cannot open file '{$filename}' or file is empty");
        }
        $plainUsers = json_decode($raw);
        if ($plainUsers === null) {
            throw Exceptions::newActionFailedException(__METHOD__, "file '{$filename}' contains invalid JSON");
        }
        if (!is_object($plainUsers)) {
            throw Exceptions::newActionFailedException(__METHOD__, "file '{$filename}' must contain a JSON object");
        }

        // merge these in with any users we have already loaded
        $users = new BaseObject;
        $users->mergeFrom($plainUsers);

        // remember what we've loaded
        $this->st->setTestUsers($users);

        // all done
        $count = count(get_object_vars($users));
        $log->endAction("loaded {$count} test user(s)");
        return $users;
    }

    /**
     * save test users to disk
     *
     * NOTES:
     *
     * - Storyplayer calls this for you when all tests have completed
     *
     * - if you've loaded test users yourself inside your test, you'll need
     *   to call this method to save those test users
     *
     * @param  \DataSift\Stone\ObjectLib\BaseObject $users
     *         the test users to save to disk
     * @param  string $filename
     *         the filename to save to
     * @return void
     */
    public function saveUsersToFile($users, $filename)
    {
        // what are we doing?
        $log = usingLog()->startAction("save test users to file '{$filename}'");

        // save the contents
        file_put_contents($filename, json_encode($users, JSON_PRETTY_PRINT));

        // all done
        $count = count(get_object_vars($users));
        $log->endAction("saved {$count} test user(s)");
    }

    /**
     * tell Storyplayer that test users loaded via the --users switch must
     * not be saved back to disk when the stories are over
     *
     * NOTES:
     *
     * - Storyplayer calls this for you if you use the --read-only-users
     *   switch, or put 'moduleSettings.users.readOnly' in your test
     *   environment config
     *
     * - this setting has no effect if you call saveUsersToFile() manually
     */
    public function setUsersFileIsReadOnly()
    {
        // what are we doing?
        $log = usingLog()->startAction("mark test users file as read-only");

        // track the state change
        $this->st->setTestUsersFileIsReadOnly(true);

        // all done
        $log->endAction();
    }
}