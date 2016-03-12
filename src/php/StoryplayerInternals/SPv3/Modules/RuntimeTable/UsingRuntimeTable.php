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

namespace StoryplayerInternals\SPv3\Modules\RuntimeTable;

use DataSift\Stone\ObjectLib\BaseObject;
use Storyplayer\SPv3\Modules\Exceptions;
use Storyplayer\SPv3\Modules\Log;
use StoryplayerInternals\SPv3\Modules\RuntimeTable;

class UsingRuntimeTable extends BaseRuntimeTable
{
    /**
     * addItem
     *
     * Add an item to a module's runtime config table
     *
     * @param string $key
     *        The key to save data under
     * @param mixed $value
     *        The value to save
     *
     * @return void
     */
    public function addItem($key, $value)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        $log = Log::usingLog()->startAction("add item '{$key}' to {$tableName} table");

        // get the table
        $table = RuntimeTable::fromRuntimeTable($tableName)->getTable();

        // make sure we don't have a duplicate entry
        if (isset($table->$key)){
            $msg = "table already contains item '{$key}'";
            $log->endAction($msg);
            throw Exceptions::newActionFailedException(__METHOD__, $msg);
        }

        // add the entry
        $table->$key = $value;

        // save the updated runtime config
        $this->saveRuntimeConfig($log);

        // all done
        $log->endAction();
    }

    /**
     * removeItem
     *
     * Removes an item from the runtimeConfig file
     *
     * @param string $key
     *        The key that we want to remove
     *
     * @return void
     */
    public function removeItem($key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("remove item '{$key}' from {$tableName} table");

        // get the table
        $table = RuntimeTable::fromRuntimeTable($tableName)->getTableIfExists();

        // does the table exist?
        if (!$table) {
            $msg = "table does not exist. '{$key}' not removed";
            $log->endAction($msg);
            return;
        }

        // make sure we have an entry to remove
        if (!isset($table->$key)) {
            $msg = "table does not contain item '{$key}'";
            $log->endAction($msg);
            return;
        }

        // remove the entry
        unset($table->$key);

        // remove the table if it's empty
        RuntimeTable::usingRuntimeTables()->removeTableIfEmpty($tableName);

        // save our changes
        $this->saveRuntimeConfig($log);

        // all done
        $log->endAction();
    }

    /**
     * updateItem
     *
     * Replace an item in the runtimeConfig file. If the item does not exist,
     * we will create it.
     *
     * @param string $key
     *        The key that we want to update
     * @param mixed $value
     *        The new value for $key
     *
     * @return void
     */
    public function updateItem($key, $value)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("update entry '{$key}' in {$tableName} table");

        // get the table config
        $table = RuntimeTable::fromRuntimeTable($tableName)->getTable();

        // update the entry
        $table->$key = $value;

        // save the changes
        $this->saveRuntimeConfig($log);

        // all done
        $log->endAction();

    }

    /**
     * Add an item to a module's runtime config table
     *
     * @param string $key
     *        The key to save data under
     * @param string $value
     *        The value to save
     *
     * @return void
     */
    public function addItemToGroup($group, $key, $value)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        $log = Log::usingLog()->startAction("add entry '{$group}->{$key}' to {$tableName} table");

        // get the table config
        $table = RuntimeTable::fromRuntimeTable($tableName)->getTable();

        // make sure the group exists
        if (!isset($tables->$tableName->$group)) {
            $tables->$tableName->$group = new BaseObject;
        }

        // make sure we don't have a duplicate entry
        if (isset($tables->$tableName->$group->$key)){
            $msg = "table already contains an entry for '{$group}->{$key}'";
            $log->endAction($msg);
            throw Exceptions::newActionFailedException(__METHOD__, $msg);
        }

        // add the entry
        $table->$group->$key = $value;

        // make sure that the table's group is always available for
        // template expansion
        //
        // NOTE: any code that adds groups to tables by hand does NOT
        //       get this guarantee
        $activeConfig = $this->st->getActiveConfig();
        $activeConfig->setData($tableName, $tables->$tableName);

        // save the updated runtime config
        $this->saveRuntimeConfig($log);

        // all done
        $log->endAction();
    }

    /**
     * Removes an item from a group in the table
     *
     * @param string $key
     *        The key that we want to remove
     *
     * @return void
     */
    public function removeItemFromGroup($group, $key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("remove item '{$key}' from group '{$group}' in {$tableName} table");

        // get the table config
        $table = RuntimeTable::fromRuntimeTable($tableName)->getTableIfExists();

        // does the group exist?
        if (!isset($table->$group)) {
            $msg = "table has no group '{$group}'. '{$group}->{$key}' not removed";
            $log->endAction($msg);
            return;
        }

        // does the group contain the item?
        if (!isset($table->$group->$key)) {
            $msg = "table does not contain item '{$item}' in group '{$group}'";
            $log->endAction($msg);
            return;
        }

        // remove the entry
        unset($table->$group->$key);

        // remove the table if it is now empty
        RuntimeTable::usingRuntimeTables()->removeTableIfEmpty($tableName);

        // save the changes
        $this->saveRuntimeConfig($log);

        // all done
        $log->endAction();
    }

    /**
     * removeTable
     *
     * deletes the entire table
     *
     * @return void
     */
    public function removeTable()
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = Log::usingLog()->startAction("remove the {$tableName} table from the runtime config");

        // there's a module to do this
        RuntimeTable::usingRuntimeTables()->removeTable($tableName);

        // save the changes
        $this->saveRuntimeConfig($log);

        // all done
        $log->endAction();
    }

    /**
     * save the updated runtime config to disk
     *
     * @param  object $log
     *         the currently active logger
     * @return void
     */
    protected function saveRuntimeConfig($log)
    {
        // save the updated runtime config
        $log->addStep("saving runtime config to disk", function() {
            $this->st->saveRuntimeConfig();
        });
    }
}
