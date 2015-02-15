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
	 *
	 * @return void
	 */
	public function makeConfigDir(Output $output)
	{
		// what is the path to the config directory?
		$configDir = $this->getConfigDir();

		// does it exist?
		if (!file_exists($configDir))
		{
			$success = mkdir($configDir, 0700, true);
			if (!$success)
			{
				// cannot create it - bail out now
				$output->logCliError("unable to create config directory '{$configDir}'");
				exit(1);
			}
		}
	}

	/**
	 *
	 * @return stdClass
	 */
	public function loadRuntimeConfig(Output $output)
	{
		// where is the config file?
		$configDir = $this->getConfigDir();
		$filename = $configDir . "/" . self::RUNTIME_FILENAME;

		// does the file exist?
		if (!is_file($filename)) {
			return new BaseObject;
		}

		// at this point, we should be able to load the config
		$raw = @file_get_contents($filename);
		if (FALSE === $raw) {
			$output->logCliError("unable to read config file '{$filename}'; permissions error?");
			exit(1);
		}

		// the config file may be empty
		if (strlen(rtrim($raw)) === 0) {
			return new BaseObject;
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

		// all done
		return $enhancedConfig;
	}

	/**
	 *
	 * @param stdClass $config
	 * @return void
	 */
	public function saveRuntimeConfig($config, Output $output)
	{
		$this->makeConfigDir($output);
		$raw = json_encode($config);

		$filename = $this->getConfigDir() .	"/" . self::RUNTIME_FILENAME;
		if (FALSE === file_put_contents($filename, $raw)) {
			$output->logCliError("unable to write config file '{$filename}'");
			exit(1);
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
    public function getAllTables($runtimeConfig)
    {
        // make sure the storyplayer section exists
        if (!isset($runtimeConfig->storyplayer)) {
            $runtimeConfig->storyplayer = new BaseObject;
        }

        // and that the tables section exists
        if (!isset($runtimeConfig->storyplayer->tables)) {
            $runtimeConfig->storyplayer->tables = new BaseObject;
        }

        return $runtimeConfig->storyplayer->tables;
	}

	/**
	 * return a single table from the persistent config
	 *
	 * if the table does not exist, this will create an empty table
	 * before returning it to the caller
	 *
	 * @param  BaseObject $runtimeConfig
	 *         our persistent config
	 * @param  string $tableName
	 *         the name of the table we want
	 * @return BaseObject
	 */
	public function getTable($runtimeConfig, $tableName)
	{
		// normalise!
		$tableName = lcfirst($tableName);

		// find it
		$tables = $this->getAllTables($runtimeConfig);
		if (!isset($tables->$tableName)) {
			// make sure the caller gets a table
			$tables->$tableName = new BaseObject;
		}

		// all done
		return $tables->$tableName;
	}
}