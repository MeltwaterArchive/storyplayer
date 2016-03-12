<?php

/**
 * Copyright (c) 2013-present Mediasift Ltd
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
 * @author    Michael Heap <michael.heap@datasift.com>
 * @copyright 2013-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace StoryplayerInternals\SPv3\Modules\RuntimeTable;

use Prose\Prose;
use Storyplayer\SPv3\Modules\Exceptions;
use Storyplayer\SPv3\Modules\Log;
use StoryplayerInternals\SPv3\Modules\RuntimeTable;

/**
 * ExpectsRuntimeTable
 *
 * @uses Prose
 * @author Michael Heap <michael.heap@datasift.com>
 */
class ExpectsRuntimeTable extends BaseRuntimeTable
{
    /**
     * hasItem
     *
     * @param string $key
     *        The key to look for inside the tableName table
     *
     * @return void
     */
    public function hasItem($key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("make sure item '{$key}' exists in the '{$tableName}' table");

        // does the item exist?
        $exists = RuntimeTable::fromRuntimeTable($tableName)->hasItem($key);
        if (!$exists) {
            $msg = "table does not contain item '{$key}'";
            $log->endAction($msg);

            throw Exceptions::newExpectFailedException(__METHOD__, "{$tableName} table has item '{$key}'", "{$tableName} table has no item '{$key}'");
        }

        // all done
        $log->endAction();
    }

    /**
     * doesNotHaveItem
     *
     * @param string $key
     *        The key to look for inside the tableName table
     *
     * @return void
     */
    public function doesNotHaveItem($key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("make sure there is no existing item '{$key}' in '{$tableName}'");

        // does the item exist?
        $exists = RuntimeTable::fromRuntimeTable($tableName)->hasItem($key);
        if ($exists) {
            $msg = "table contains item '{$key}'";
            $log->endAction($msg);

            throw Exceptions::newExpectFailedException(__METHOD__, "{$tableName} table has no item '{$key}'", "{$tableName} table has item '{$key}'");
        }

        // all done
        $log->endAction();
    }

    /**
     * exists
     *
     * @return void
     */
    public function exists()
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("make sure runtime table '{$tableName}' exists");

        // does the table exist?
        $exists = RuntimeTable::fromRuntimeTables()->getTableExists($tableName);

        // make sure we have the named table
        if (!$exists) {
            $log->endAction("table does not exist");
            throw Exceptions::newExpectFailedException(__METHOD__, "runtime table '{$tableName}' exists", "runtime table '{$tableName}' does not exist");
        }

        // all done
        $log->endAction();
    }

    /**
     * doesNotExist
     *
     * @return void
     */
    public function doesNotExist()
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("make sure runtime table '{$tableName}' does not exist");

        // does the table exist?
        $exists = RuntimeTable::fromRuntimeTables()->getTableExists($tableName);

        // make sure we do not have the named table
        if ($exists) {
            $log->endAction("table exists");
            throw Exceptions::newExpectFailedException(__METHOD__, "runtime table '{$tableName}' does not exist", "runtime table '{$tableName}' exists");
        }

        // all done
        $log->endAction();
    }
}
