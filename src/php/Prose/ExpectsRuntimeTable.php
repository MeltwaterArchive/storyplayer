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
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Michael Heap <michael.heap@datasift.com>
 * @copyright 2013-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

/**
 * ExpectsRuntimeTable
 *
 * @uses Prose
 * @author Michael Heap <michael.heap@datasift.com>
 */
class ExpectsRuntimeTable extends BaseRuntimeTable
{
    /**
     * hasEntry
     *
     * @param string $key key The key to look for inside the tableName table
     *
     * @return void
     */
    public function hasEntry($key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = usingLog()->startAction("make sure host '{$key}' has an entry in the '{$tableName}' table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure we have a hosts table
        if (!isset($tables->$tableName)) {
            $msg = "table is empty / does not exist";
            $log->endAction($msg);

            throw Exceptions::newExpectFailedException(__METHOD__, "{$tableName} table existed", "{$parent} table does not exist");
        }

        // make sure we don't have a duplicate entry
        if (!isset($tables->$tableName->$key)) {
            $msg = "table does not contain an entry for '{$key}'";
            $log->endAction($msg);

            throw Exceptions::newExpectFailedException(__METHOD__, "{$tableName} table has an entry for '{$key}'", "{$parent} table has no entry for '{$key}'");
        }

        // all done
        $log->endAction();
    }

    /**
     * hasNoEntry
     *
     * @param string $key key The key to look for inside the tableName table
     *
     * @return void
     */
    public function hasNoEntry($key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = usingLog()->startAction("make sure there is no existing entry for '{$key}' in '{$tableName}'");

        // get the table config
        $tables = $this->getAllTables();

        // make sure we have a hosts table
        if (!isset($tables->$tableName)) {
            $msg = "table is empty / does not exist";
            $log->endAction($msg);
            return;
        }

        // make sure we don't have a duplicate entry
        if (isset($tables->$tableName->$key)) {
            $msg = "table already contains an entry for '{$key}'";
            $log->endAction($msg);

            throw Exceptions::newExpectFailedException(__METHOD__, "{$tableName} table has no entry for '{$key}'", "{$parent} table has an entry for '{$key}'");
        }

        // all done
        $log->endAction();
    }
}