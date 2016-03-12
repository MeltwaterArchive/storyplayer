<?php

/**
 * Copyright (c) 2013-present Mediasift Ltd
 * Copyright (c) 2015-present Ganbaro Digital Ltd
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
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2013-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace StoryplayerInternals\SPv3\Modules\RuntimeTable;

use DataSift\Stone\ObjectLib\BaseObject;
use Prose\Prose;
use Storyplayer\SPv3\Modules\Log;
use StoryplayerInternals\SPv3\Modules\RuntimeTable;

/**
 * UsingRuntimeTables
 *
 * @uses Prose
 * @author Stuart Herbert <stuherbert@ganbarodigital.com>
 */
class UsingRuntimeTables extends Prose
{
    /**
     * create a runtime table
     *
     * if the table already exists, it will NOT be overwritten
     *
     * @param  string $tableName
     *         the table to create
     * @return BaseObject
     *         the created table, ready for use
     */
    public function createTable($tableName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("create runtime table '{$tableName}'");

        // get the current runtime tables
        $tables = RuntimeTable::fromRuntimeTables()->getAllTables();
        if (!isset($tables->$tableName)) {
            $log->writeToLog("table does not exist; creating");
            $tables->$tableName = new BaseObject;
        }
        else {
            $log->writeToLog("table already exists");
        }

        // all done
        $log->endAction();
        return $tables->$tableName;
    }

    /**
     * remove a runtime table, even if it has content
     *
     * @param  string $tableName
     *         the name of the table to use
     * @return void
     */
    public function removeTable($tableName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("remove table '{$tableName}' from the runtime tables");

        // does the table exist?
        if (!RuntimeTable::fromRuntimeTables()->getTableExists($tableName)) {
            $log->endAction();
            return;
        }

        // get the tables
        $tables = RuntimeTable::fromRuntimeTables()->getAllTablesSilently();
        unset($tables->$tableName);

        // all done
        $log->endAction("table '{$tableName}' removed");
    }

    /**
     * remove a table if, and only if, it is empty
     *
     * @param  string $tableName
     *         the name of the table to remove
     * @return void
     */
    public function removeTableIfEmpty($tableName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("remove table '{$tableName}' from the runtime tables if it is empty");

        // is the table empty?
        if (!RuntimeTable::fromRuntimeTable($tableName)->getIsEmpty()) {
            $log->endAction("not removing; table is not empty");
            return;
        }

        // if we get here, the table is empty
        $tables = RuntimeTable::fromRuntimeTables()->getAllTablesSilently();
        unset($tables->$tableName);

        // all done
        $log->endAction("table '{$tableName}' removed; table was empty");
    }
}
