<?php

/**
 * Copyright (c) 2013-present Mediasift Ltd
 * Copyright (c) 2016-present Ganbaro Digital Ltd
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
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2013-present Mediasift Ltd www.datasift.com
 * @copyright 2016-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace StoryplayerInternals\SPv2\Modules\RuntimeTable;

use DataSift\Stone\ObjectLib\BaseObject;
use Storyplayer\SPv2\Modules\Log;
use StoryplayerInternals\SPv2\Modules\RuntimeTable;

class FromRuntimeTable extends BaseRuntimeTable
{
    /**
     * get a table from the runtime tables
     *
     * if the table does not exist, it will be created
     *
     * @return object
     *         The table from the config
     */
    public function getTable()
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("get '{$tableName}' table from runtime config");

        // get the table config
        $tables = RuntimeTable::fromRuntimeTables()->getAllTablesSilently();

        // make sure we have a table
        if (!isset($tables->$tableName)){
            Log::usingLog()->writeToLog("table '{$tableName}' does not exist; creating empty table");
            RuntimeTable::usingRuntimeTables()->createTable($tableName);
        }

        // all done
        $log->endAction();
        return $tables->$tableName;
    }

    /**
     * get a table from the runtime config (if it exists)
     *
     * @return object|null
     *         The table from the config, or NULL if there is no table
     */
    public function getTableIfExists()
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("get '{$tableName}' table from runtime config");

        // get the table config
        $tables = RuntimeTable::fromRuntimeTables()->getAllTablesSilently();

        // make sure we have a table
        if (!isset($tables->$tableName)){
            $log->endAction("table '{$tableName}' does not exist");
            return null;
        }

        // all done
        $log->endAction();
        return $tables->$tableName;
    }

    /**
     * get a group from our runtime table
     *
     * if the group does not exist, it will be created
     *
     * @param  string $group
     *         the name of the group that we want
     * @return BaseObject
     */
    public function getGroupFromTable($group)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("get '{$tableName}->{$group}' table group from runtime config");

        // get the table
        $table = $this->getTable();

        // make sure we have a group
        if (!isset($table->$group)) {
            $log->writeToLog("'{$group}' does not exist; creating");
            $table->$group = new BaseObject;
        }

        // all done
        $log->endAction();
        return $table->$group;
    }

    /**
     * Get the value of a specific key
     *
     * @param string $key
     *        The key to look for inside the tableName table
     *
     * @return mixed
     *         The value of the key
     */
    public function getItem($key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("get details for '{$key}' from {$tableName} table");

        // get the table
        $table = $this->getTableIfExists();

        // make sure we have a hosts table
        if (!$table) {
            $msg = "table does not exist";
            $log->endAction($msg);

            return null;
        }

        // do we have the entry we're looking for?
        if (!isset($table->$key)) {
            $msg = "table does not contain an entry for '{$key}'";
            $log->endAction($msg);
            return null;
        }

        // all done
        $log->endAction();
        return $table->$key;
    }

    /**
     * Get the value of a specific key from a group
     *
     * @param string $key
     *        The key to look for inside the tableName table
     *
     * @return mixed
     *         The value of the $key
     */
    public function getItemFromGroup($group, $key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("get details for '{$group}->{$key}' from {$tableName} table");

        // get the table config
        $table = $this->getTableIfExists();

        // make sure we have a table
        if (!$table) {
            $msg = "table does not exist";
            $log->endAction($msg);

            return null;
        }

        // make sure we have the group
        if (!isset($table->$group)) {
            $msg = "table has no group '{$group}'";
            $log->endAction($msg);

            return null;
        }

        // do we have the entry we're looking for?
        if (!isset($table->$group->$key)) {
            $msg = "table does not contain an entry for '{$group}->{$key}'";
            $log->endAction($msg);
            return null;
        }

        // all done
        $log->endAction();
        return $table->$group->$key;
    }

    /**
     * is the table empty?
     *
     * @return boolean
     *         TRUE if the table is empty
     *         TRUE if the table does not exist
     *         FALSE otherwise
     */
    public function getIsEmpty()
    {
        // shorthand
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("is table '{$tableName}' empty?");

        // get the table
        $table = RuntimeTable::fromRuntimeTables()->getTableSilently($tableName);
        if ($table === null) {
            $log->endAction("table does not exist; reporting that it is empty");
            return true;
        }

        // is it empty?
        $contents = get_object_vars($table);
        $contentCount = count($contents);
        if (count($contents) !== 0) {
            $log->endAction("table is not empty; has '{$contentCount}' item(s)");
            return false;
        }

        // if we get here, the table is empty
        $log->endAction("table is empty");
        return true;
    }

    /**
     * does this table contain a specific item?
     *
     * @param  string $key
     *         the item that we are looking for
     * @return boolean
     *         TRUE if the table contains $key
     *         FALSE otherwise
     */
    public function hasItem($key)
    {
        // shorthand
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("does table '{$tableName}' contain item '{$key}'?");

        // get our table
        $table = $this->getTableIfExists();
        if (!$table) {
            $log->endAction("table does not exist");
            return false;
        }

        if (!isset($table->$key)) {
            $log->endAction("table does not contain item '{$key}'");
            return false;
        }

        // if we get here, all is good
        $log->endAction("table contains item '{$key}'");
        return true;
    }

    /**
     * does this table contain a specific group?
     *
     * @param  string $key
     *         the group that we are looking for
     * @return boolean
     *         TRUE if the table contains $key
     *         FALSE otherwise
     */
    public function hasGroup($key)
    {
        // shorthand
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("does table '{$tableName}' contain group '{$key}'?");

        // get our table
        $table = $this->getTableIfExists();
        if (!$table) {
            $log->endAction("table does not exist");
            return false;
        }

        if (!isset($table->$key)) {
            $log->endAction("table does not contain group '{$key}'");
            return false;
        }

        // is it a group?
        if (!$table->$key instanceof BaseObject) {
            $log->endAction("table contains item '{$key}', but it is not a group");
            return false;
        }

        // if we get here, all is good
        $log->endAction("table contains group '{$key}'");
        return true;
    }
}
