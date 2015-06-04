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
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Storyplayer\Output;
use DataSift\Storyplayer\TestEnvironmentsLib\TestEnvironmentRuntimeConfig;

/**
 * Helper class for working with Storyplayer's persistent state
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class RuntimeConfigManager extends ConfigManagerBase
{
    const RUNTIME_FILENAME = "runtime-v2.json";

    /**
     *
     * @return string
     */
    public function getConfigDir()
    {
        static $configDir = null;

        // do we have a configDir remembered yet?
        if (!$configDir)
        {
            $configDir = getcwd() . '/.storyplayer';
        }

        return $configDir;
    }

    /**
     * NEW for SPv2.4 - we are replacing the runtime config file with a
     * separate file for each test environment
     *
     * @return null
     */
    public function loadRuntimeConfig(Output $output)
    {
        // where is the config file?
        $configDir = $this->getConfigDir();
        $filename = $configDir . "/" . self::RUNTIME_FILENAME;

        // does the file exist?
        if (!is_file($filename)) {
            return;
        }

        // at this point, we should be able to load the config
        $raw = @file_get_contents($filename);
        if (FALSE === $raw) {
            $output->logCliError("unable to read config file '{$filename}'; permissions error?");
            exit(1);
        }

        // the config file may be empty
        if (strlen(rtrim($raw)) === 0) {
            return;
        }

        $config = @json_decode($raw);
        if (NULL === $config) {
            $output->logCliError("unable to parse JSON in config file '{$filename}'");
            exit(1);
        }

        // we need to convert the loaded config into our more powerful config
        // (at least for now)
        $enhancedConfig = new BaseObject;
        $enhancedConfig->mergeFrom($config);

        // NEW for SPv2.4 ... the runtimeConfig is going away
        $this->splitUpConfig($enhancedConfig);
        $this->removeRuntimeConfig();

        // all done
        return null;
    }

    /**
     * NEW for SPv2.4 - we're replacing the runtime-v2.json with a config
     * file per test environment
     *
     * This method takes a runtime.json already loaded from disk, and creates
     * a separate config file for each test environment. The original
     * runtime-v2.json file is then deleted from disk.
     *
     * @param  BaseObject $config
     * @return void
     */
    protected function splitUpConfig($oldConfig)
    {
        // do we have any config to split up?
        if (!isset($oldConfig->storyplayer, $oldConfig->storyplayer->tables, $oldConfig->storyplayer->tables->hosts)) {
            return;
        }

        // yes we do
        foreach($oldConfig->storyplayer->tables->hosts as $testEnvName => $hosts) {
            // our new per-test-env config file
            $newConfig = new TestEnvironmentRuntimeConfig();
            $newConfig->setName($testEnvName);
            $newConfig->setFilename(".storyplayer/runtime-test-environments/{$testEnvName}/runtime.json");

            // replicate the existing tables approach
            //
            // we should have roles here too, but it looks like there's a bug
            // in SPv2.3 which prevents roles data being correctly recorded
            $newConfig->setData("tables.hosts", $hosts);

            // all done
            $newConfig->saveConfig();
        }
    }

    /**
     * NEW for SPv2.4 - the old-style runtime.json (single file for each
     * Storyplayer project) is going away
     *
     * @return void
     */
    protected function removeRuntimeConfig()
    {
        // where is the config file?
        $configDir = $this->getConfigDir();
        $filename = $configDir . "/" . self::RUNTIME_FILENAME;

        // do we have a config file to delete?
        if (!file_exists($filename)) {
            return;
        }

        // remove it
        unlink($filename);
    }

    /**
     * @param TestEnvironmentRuntimeConfig $config
     * @return void
     */
    public function saveRuntimeConfig(TestEnvironmentRuntimeConfig $config, Output $output)
    {
        // tidy the config first
        $runtimeConfig->tidyEmptyTables();

        // we save if there's something to save, otherwise
        // we remove the config
        if ($config->hasConfig()) {
            $config->saveConfig();
        }
        else {
            $config->removeConfig();
        }
    }

    /**
     * getAllTables
     *
     * Return our tables config that we can use for
     * in place editing
     *
     * @return BaseObject
     */
    public function getAllTables(TestEnvironmentRuntimeConfig $runtimeConfig)
    {
        return $runtimeConfig->getAllTables();
    }

    /**
     * return a single table from the persistent config
     *
     * if the table does not exist, this will create an empty table
     * before returning it to the caller
     *
     * @param  TestEnvironmentRuntimeConfig $runtimeConfig
     *         our persistent config
     * @param  string $tableName
     *         the name of the table we want
     * @return BaseObject
     */
    public function getTable($runtimeConfig, $tableName)
    {
        return $runtimeConfig->getTable($tableName);
    }

    /**
     * remove all empty tables from the runtime config
     *
     * if there are no tables left, the 'tables' entry will be removed too
     *
     * @param  TestEnvironmentRuntimeConfig $runtimeConfig
     *         our persistent config
     * @return void
     */
    public function removeEmptyTables($runtimeConfig)
    {
        $runtimeConfig->removeEmptyTables();
    }
}