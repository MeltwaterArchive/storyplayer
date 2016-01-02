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

namespace StoryplayerInternals\SPv2\Modules\RuntimeTable;

use DataSift\Stone\ObjectLib\BaseObject;
use Prose\Prose;
use Storyplayer\SPv2\Modules\Log;

/**
 * FromRuntimeTables
 *
 * @uses Prose
 * @author Stuart Herbert <stuherbert@ganbarodigital.com>
 */
class FromRuntimeTables extends Prose
{
    /**
     * getAllTables
     *
     * Return our tables config that we can use for
     * in place editing
     *
     * @return BaseObject
     */
    public function getAllTables()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get all the current runtime tables");

        // get the tables
        $tables = $this->getAllTablesSilently();

        // all done
        $log->endAction();
        return $tables;
    }

    /**
     * does the runtime table $tableName exist?
     *
     * @param  string $tableName
     *         the name of the table we want to check
     * @return boolean
     *         TRUE if the table exists
     *         FALSE otherwise
     */
    public function getTableExists($tableName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("does the runtime table '{$tableName}' exist?");

        // get the active tables
        $tables = $this->getAllTablesSilently();

        // does our table exist?
        if (isset($tables->{$tableName})) {
            $log->endAction("it exists");
            return true;
        }

        $log->endAction("it does not exist");
        return false;
    }

    /**
     * return the current runtime tables, without writing to the log
     *
     * this exists mostly for other sections of the RuntimeTable module to use
     * without spamming the hell out of the logs
     *
     * @return BaseObject
     */
    public function getAllTablesSilently()
    {
        // get the runtime config
        $runtimeConfig = $this->st->getRuntimeConfig();
        $runtimeConfigManager = $this->st->getRuntimeConfigManager();

        // get the active tables
        $tables = $runtimeConfigManager->getAllTables($runtimeConfig);

        // all done
        return $tables;
    }

    /**
     * get a table from the runtime tables list
     *
     * @param  string $tableName
     *         the name of the table that you want
     * @return object|null
     *         returns an object if the table exists
     *         returns NULL otherwise
     */
    public function getTable($tableName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get runtime table '{$tableName}'");

        // do we have one?
        $table = $this->getTableSilently($tableName);

        // all done
        //
        // NOTE: we do not log the contents of the table here, to avoid spamming
        // the logs
        //
        // when we have more fine-grained logging, we can revisit this
        $log->endAction();
        return $table;
    }

    /**
     * return a named table, without writing to the log
     *
     * @param  string $tableName
     *         the name of the table that we want
     * @return BaseObject|null
     */
    public function getTableSilently($tableName)
    {
        $tables = $this->getAllTablesSilently();
        if (isset($tables->$tableName)) {
            return $tables->$tableName;
        }

        return null;
    }
}
