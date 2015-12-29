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
 * @package   Storyplayer/TestEnvironmentsLib
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-2015 Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\TestEnvironmentsLib;

use DataSift\Storyplayer\ConfigLib\WrappedConfig;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * the class for a test environment's runtime config
 *
 * the runtime config is the persistent state (e.g. hosts that have been
 * spun up) of the test environment
 *
 * @category  Libraries
 * @package   Storyplayer/TestEnvironmentsLib
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-2015 Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TestEnvironmentRuntimeConfig extends WrappedConfig
{
    public function __construct()
    {
        parent::__construct(self::ROOT_IS_OBJECT);
    }

    public function validateConfig()
    {
        // do we have any config?
        $config = $this->getConfig();
    }

    /**
     * set the name of this config by looking at the filename
     *
     * the 'name' is used as an array key elsewhere. if we get this wrong,
     * then Storyplayer isn't going to be able to find our config later on
     *
     * @param  string $filename
     *         the path/to/file/name.ext where we found this config
     * @return void
     */
    protected function setNameFromFilename($filename)
    {
        $this->setName(basename(dirname($filename)));
    }

    // ==================================================================
    //
    // This is the old RuntimeConfigManager API from SPv1 (more or less).
    //
    // It is part of our migration from a single runtime-v2.json file to
    // a per-test-environment runtime.json file scheme.
    //
    // ------------------------------------------------------------------

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
        // make sure the storyplayer section exists
        if (!$this->hasData('tables')) {
            $this->setData('tables', new BaseObject);
        }

        // arrow-notation ensures we get a READ-WRITE object back
        return $this->tables;
    }

    /**
     * return a single table from the persistent state
     *
     * if the table does not exist, this will create an empty table
     * before returning it to the caller
     *
     * @param  string $tableName
     *         the name of the table we want
     * @return BaseObject
     */
    public function getTable($tableName)
    {
        // normalise!
        $tableName = lcfirst($tableName);

        // find it
        $tables = $this->getAllTables();
        if (!isset($tables->$tableName)) {
            // make sure the caller gets a table
            $tables->$tableName = new BaseObject;
        }

        // all done
        return $tables->$tableName;
    }

    /**
     * remove any empty tables from the runtime config
     *
     * @return void
     */
    public function removeEmptyTables()
    {
        $tables = $this->getAllTables();

        foreach ($tables as $tableName => $table) {
            if ($table instanceof BaseObject) {
                if (!$table->hasProperties()) {
                    unset($tables->$tableName);
                }
            }
            else if (is_array($table)) {
                if (empty($table)) {
                    unset($tables->$tableName);
                }
            }
        }

        if (!$tables->hasProperties()) {
            $this->unsetData('tables');
        }
    }
}
