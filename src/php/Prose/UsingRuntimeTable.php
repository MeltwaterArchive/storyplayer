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

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * UsingRuntimeTable
 *
 * @uses Prose
 * @author Michael Heap <michael.heap@datasift.com>
 */
class UsingRuntimeTable extends BaseRuntimeTable
{
    /**
     * addItem
     *
     * Add an item to a module's runtime config table
     *
     * @param string $key The key to save data under
     * @param string $value The value to save
     *
     * @return void
     */
    public function addItem($key, $value)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        $log = usingLog()->startAction("add entry '{$key}' to {$tableName} table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure it exists
        if (!isset($tables->$tableName)){
            $log->addStep("{$tableName} does not exist in the runtime config. creating empty table", function() use ($tables, $tableName){
                $tables->$tableName = new BaseObject();
            });
        }

        // make sure we don't have a duplicate entry
        if (isset($tables->$tableName->$key)){
            $msg = "Table already contains an entry for '{$key}'";
            $log->endAction($msg);
            throw new E5xx_ActionFailed(__METHOD__, $msg);
        }

        // add the entry
        $tables->$tableName->$key = $value;

        // save the updated runtime config
        $log->addStep("saving runtime config to disk", function() {
            $this->st->saveRuntimeConfig();
        });

        // all done
        $log->endAction();
    }

    /**
     * removeItem
     *
     * Removes an item from the runtimeConfig file
     *
     * @param string $key The key that we want to remove
     *
     * @return void
     */
    public function removeItem($key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = usingLog()->startAction("remove entry '{$key}' from {$tableName} table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure it exists
        if (!isset($tables->$tableName)) {
            $msg = "table is empty / does not exist. '{$key}' not removed";
            $log->endAction($msg);
            return;
        }

        // make sure we have an entry to remove
        if (!isset($tables->$tableName->$key)) {
            $msg = "table does not contain an entry for '{$key}'";
            $log->endAction($msg);
            return;
        }

        // remove the entry
        unset($tables->$tableName->$key);

        // remove the table if it's empty
        if (!count(get_object_vars($tables->$tableName))) {
            $log->addStep("table '{$tableName}' is empty, removing from runtime config", function() use ($tables, $tableName){
                unset($tables->$tableName);
            });
        }

        // save the changes
        $this->st->saveRuntimeConfig();

        // all done
        $log->endAction();

    }


    /**
     * Add an item to a module's runtime config table
     *
     * @param string $key The key to save data under
     * @param string $value The value to save
     *
     * @return void
     */
    public function addItemToGroup($group, $key, $value)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        $log = usingLog()->startAction("add entry '{$group}->{$key}' to {$tableName} table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure it exists
        if (!isset($tables->$tableName)){
            $tables->$tableName = new BaseObject();
        }
        if (!isset($tables->$tableName->$group)) {
            $tables->$tableName->$group = new BaseObject;
        }

        // make sure we don't have a duplicate entry
        if (isset($tables->$tableName->$group->$key)){
            $msg = "table already contains an entry for '{$group}->{$key}'";
            $log->endAction($msg);
            throw new E5xx_ActionFailed(__METHOD__, $msg);
        }

        // add the entry
        $tables->$tableName->$group->$key = $value;

        // make sure that the table's group is always available for
        // template expansion
        //
        // NOTE: any code that adds groups to tables by hand does NOT
        //       get this guarantee
        $activeConfig = $this->st->getActiveConfig();
        $activeConfig->setData($tableName, $tables->$tableName);

        // save the updated runtime config
        $log->addStep("saving runtime config to disk", function() {
            $this->st->saveRuntimeConfig();
        });

        // all done
        $log->endAction();
    }

    /**
     * Removes an item from the runtimeConfig file
     *
     * @param string $key The key that we want to remove
     *
     * @return void
     */
    public function removeItemFromGroup($group, $key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = usingLog()->startAction("remove entry '{$group}->{$key}' from {$tableName} table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure it exists
        if (!isset($tables->$tableName)) {
            $msg = "table is empty / does not exist. '{$group}->{$key}' not removed";
            $log->endAction($msg);
            return;
        }
        if (!isset($tables->$tableName->$group)) {
            $msg = "table has no group '{$group}'. '{$group}->{$key}' not removed";
            $log->endAction($msg);
            return;
        }
        if (!isset($tables->$tableName->$group->$key)) {
            $msg = "table does not contain an entry for '{$group}->{$key}'";
            $log->endAction($msg);
            return;
        }

        // remove the entry
        unset($tables->$tableName->$group->$key);

        // remove the table if it's empty
        if (!count(get_object_vars($tables->$tableName->$group))) {
            $log->addStep("table group '{$tableName}->{$group}' is empty, removing from runtime config", function() use ($tables, $tableName, $group){
                unset($tables->$tableName->$group);
            });
        }

        // save the changes
        $this->st->saveRuntimeConfig();

        // all done
        $log->endAction();

    }

    /**
     * Removes an item from the runtimeConfig file
     *
     * @param string $key The key that we want to remove
     *
     * @return void
     */
    public function removeItemFromAllGroups($key)
    {
        // get our table name from the constructor
        $tableName = $this->args[0];

        // what are we doing?
        $log = usingLog()->startAction("remove entry '{$key}' from all groups in {$tableName} table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure it exists
        if (!isset($tables->$tableName)) {
            $msg = "table is empty / does not exist. '{$key}' not removed";
            $log->endAction($msg);
            return;
        }
        $groups = get_object_vars($tables->$tableName);
        if (!count($groups)) {
            $msg = "table has no groups. '{$key}' not removed";
            $log->endAction($msg);
            return;
        }
        foreach ($tables->$tableName as $group => $contents) {
            if (isset($tables->$tableName->$group->$key)) {
                // remove the entry
                unset($tables->$tableName->$group->$key);

                // remove the table if it's empty
                if (!count(get_object_vars($tables->$tableName->$group))) {
                    $log->addStep("table group '{$tableName}->{$group}' is empty, removing from runtime config", function() use ($tables, $tableName, $group){
                        unset($tables->$tableName->$group);
                    });
                }
            }
        }
        // save the changes
        $this->st->saveRuntimeConfig();

        // all done
        $log->endAction();
    }
}
