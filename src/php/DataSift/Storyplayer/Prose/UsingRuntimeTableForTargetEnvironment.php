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

namespace DataSift\Storyplayer\Prose;

use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;

/**
 * UsingRuntimeTable
 *
 * @uses Prose
 * @author Michael Heap <michael.heap@datasift.com>
 */
class UsingRuntimeTableForTargetEnvironment extends BaseRuntimeTable
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
        $st = $this->st;

        // get our table name from the constructor
        $tableName = $this->args[0];
        $targetEnv = $st->getTestEnvironmentName();

        $log = $st->startAction("add entry '{$key}' to {$tableName}->{$targetEnv} table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure the table
        if (!isset($tables->$tableName)){
            $log->addStep("{$tableName} does not exist in the runtime config. creating empty table", function() use ($tables, $tableName){
                $tables->$tableName = new BaseObject();
            });
        }
        if (!isset($tables->$tableName->$targetEnv)) {
            $log->addStep("{$tableName}->{$targetEnv} does not exist in the runtime config. creating empty table", function() use($tables, $tableName, $targetEnv) {
                $tables->$tableName->$targetEnv = new BaseObject();
            });
        }

        // make sure we don't have a duplicate entry
        if (isset($tables->$tableName->$targetEnv->$key)){
            $msg = "Table already contains an entry for '{$key}'";
            $log->endAction($msg);
            throw new E5xx_ActionFailed(__METHOD__, $msg);
        }

        // add the entry
        $tables->$tableName->$targetEnv->$key = $value;

        // save the updated runtime config
        $log->addStep("saving runtime config to disk", function() use ($st){
            $st->saveRuntimeConfig();
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
        // shorthand
        $st = $this->st;

        // get our table name from the constructor
        $tableName = $this->args[0];
        $targetEnv = $st->getTestEnvironmentName();

        // what are we doing?
        $log = $st->startAction("remove entry '{$key}' from {$tableName}->{$targetEnv} table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure it exists
        if (!isset($tables->$tableName)) {
            $msg = "table is empty / does not exist. '{$key}' not removed";
            $log->endAction($msg);
            return;
        }
        if (!isset($tables->$tableName->$targetEnv)) {
            $msg = "table for {$targetEnv} is empty / does not exist. '{$key}' not removed";
            $log->endAction($msg);
            return;
        }

        // make sure we have an entry to remove
        if (!isset($tables->$tableName->$targetEnv->$key)) {
            $msg = "table does not contain an entry for '{$key}'";
            $log->endAction($msg);
            return;
        }

        // remove the entry
        unset($tables->$tableName->$targetEnv->$key);

        // remove the table if it's empty
        if (!count(get_object_vars($tables->$tableName->$targetEnv))) {
            $log->addStep("table '{$tableName}->{$targetEnv}' is empty, removing from runtime config", function() use ($tables, $tableName, $targetEnv){
                unset($tables->$tableName->$targetEnv);
            });
        }
        if (!count(get_object_vars($tables->$tableName))) {
            $log->addStep("table '{$tableName}' is empty, removing from runtime config", function() use ($tables, $tableName){
                unset($tables->$tableName);
            });
        }

        // save the changes
        $st->saveRuntimeConfig();

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
        $st = $this->st;

        // get our table name from the constructor
        $tableName = $this->args[0];
        $targetEnv = $st->getTestEnvironmentName();

        $log = $st->startAction("add entry '{$group}->{$key}' to {$tableName}->{$targetEnv} table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure it exists
        if (!isset($tables->$tableName)){
            $tables->$tableName = new BaseObject();
        }
        if (!isset($tables->$tableName->$targetEnv)) {
            $tables->$tableName->$targetEnv = new BaseObject;
        }
        if (!isset($tables->$tableName->$targetEnv->$group)) {
            $tables->$tableName->$targetEnv->$group = new BaseObject;
        }

        // make sure we don't have a duplicate entry
        if (isset($tables->$tableName->$targetEnv->$group->$key)){
            $msg = "table already contains an entry for '{$group}->{$key}'";
            $log->endAction($msg);
            throw new E5xx_ActionFailed(__METHOD__, $msg);
        }

        // add the entry
        $tables->$tableName->$targetEnv->$group->$key = $value;

        // make sure that the table's group is always available for
        // template expansion
        //
        // NOTE: any code that adds groups to tables by hand does NOT
        //       get this guarantee
        $activeConfig = $st->getTestEnvironment();
        $activeConfig->$tableName = $tables->$tableName->$targetEnv->$group;

        // save the updated runtime config
        $log->addStep("saving runtime config to disk", function() use ($st){
            $st->saveRuntimeConfig();
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
        // shorthand
        $st = $this->st;

        // get our table name from the constructor
        $tableName = $this->args[0];
        $targetEnv = $st->getTestEnvironmentName();

        // what are we doing?
        $log = $st->startAction("remove entry '{$group}->{$key}' from {$tableName}->{$targetEnv} table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure it exists
        if (!isset($tables->$tableName)) {
            $msg = "table is empty / does not exist. '{$group}->{$key}' not removed";
            $log->endAction($msg);
            return;
        }
        if (!isset($tables->$tableName->$targetEnv)) {
            $msg = "table has no data for '{$targetEnv}'. '{$group}->{$key}' not removed";
            $log->endAction($msg);
            return;
        }
        if (!isset($tables->$tableName->$targetEnv->$group)) {
            $msg = "table has no group '{$group}'. '{$group}->{$key}' not removed";
            $log->endAction($msg);
            return;
        }
        if (!isset($tables->$tableName->$group->$targetEnv->$key)) {
            $msg = "table does not contain an entry for '{$group}->{$key}'";
            $log->endAction($msg);
            return;
        }

        // remove the entry
        unset($tables->$tableName->$targetEnv->$group->$key);

        // remove the table if it's empty
        if (!count(get_object_vars($tables->$tableName->$targetEnv->$group))) {
            $log->addStep("table group '{$group}' is empty, removing from runtime config", function() use ($tables, $tableName, $group){
                unset($tables->$tableName->$targetEnv->$group);
            });
        }
        if (!count(get_object_vars($tables->$tableName->$targetEnv))) {
            $log->addStep("table '{$tableName}->{$targetEnv}' is empty, removing from runtime config", function() use ($tables, $tableName, $group){
                unset($tables->$tableName->$targetEnv);
            });
        }
        if (!count(get_object_vars($tables->$tableName))) {
            $log->addStep("table '{$tableName}' is empty, removing from runtime config", function() use ($tables, $tableName, $group){
                unset($tables->$tableName);
            });
        }

        // save the changes
        $st->saveRuntimeConfig();

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
        // shorthand
        $st = $this->st;

        // get our table name from the constructor
        $tableName = $this->args[0];
        $targetEnv = $st->getTestEnvironmentName();

        // what are we doing?
        $log = $st->startAction("remove entry '{$key}' from all groups in {$tableName}->{$targetEnv} table");

        // get the table config
        $tables = $this->getAllTables();

        // make sure it exists
        if (!isset($tables->$tableName)) {
            $msg = "table is empty / does not exist. '{$key}' not removed";
            $log->endAction($msg);
            return;
        }
        if (!isset($tables->$tableName->$targetEnv)) {
            $msg = "table is empty / does not exist. '{$key}' not removed";
            $log->endAction($msg);
            return;
        }

        $groups = get_object_vars($tables->$tableName->$targetEnv);
        if (!count($groups)) {
            $msg = "table has no groups. '{$key}' not removed";
            $log->endAction($msg);
            return;
        }

        foreach ($tables->$tableName->$targetEnv as $groupName => $group) {
            if (isset($group->$key)) {
                // remove the entry
                unset($group->$key);
            }

            // remove the table if it's empty
            if (!count(get_object_vars($tables->$tableName->$targetEnv->$groupName))) {
                $log->addStep("table group '{$tableName}->{$targetEnv}->{$groupName}' is empty, removing from runtime config", function() use ($tables, $tableName, $targetEnv, $groupName){
                    unset($tables->$tableName->$targetEnv->$groupName);
                });
            }
        }

        // remove any empty tables
        if (!count(get_object_vars($tables->$tableName->$targetEnv))) {
            $log->addStep("table '{$tableName}->{$targetEnv}' is empty, removing from runtime config", function() use ($tables, $tableName, $targetEnv){
                unset($tables->$tableName->$targetEnv);
            });
        }
        if (!count(get_object_vars($tables->$tableName))) {
            $log->addStep("table '{$tableName}' is empty, removing from runtime config", function() use ($tables, $tableName){
                unset($tables->$tableName);
            });
        }

        // save the changes
        $st->saveRuntimeConfig();

        // all done
        $log->endAction();
    }
}
