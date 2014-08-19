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

use DataSift\Stone\ConfigLib\E4xx_ConfigFileNotFound;
use DataSift\Stone\ConfigLib\LoadedConfig;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * helper class for loading our static config files
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class StaticConfigManager extends ConfigManagerBase
{
	const USER_DOTFILE = 'storyplayer.json';

	/**
	 * load the default config file
	 *
	 * @param StaticConfig $config
	 * @return void
	 */
	public function loadDefaultConfig($config, $defaultConfigFilename)
	{
		// load the default config file
		$this->configHelper->loadConfigFile($config, $defaultConfigFilename);
	}

	/**
	 * [loadUserConfig description]
	 * @param  [type] $config  [description]
	 * @param  [type] $appName [description]
	 * @return [type]          [description]
	 */
	public function loadUserConfig($config)
	{
		try {
			// We start with an empty config
			$this->configHelper->loadDotFileConfig($config, self::APP_NAME, self::USER_DOTFILE);
		}
		catch (E4xx_ConfigFileNotFound $e) {
			// we don't care - user configs are optional
		}
		// all done
	}

	public function loadAdditionalConfig($config, $filename)
	{
		$this->configHelper->loadConfigFile($config, $filename);
	}

	/**
	 *
	 * @return array<string>
	 */
	public function getListOfConfigFiles($dirs)
	{
		// do we have a credible search list?
		if (!is_array($dirs)) {
			// fraid not
			return [];
		}

		$return = [];
		foreach ($dirs as $dirToSearch)
		{
			// find JSON config files
			$files = $this->configHelper->getListOfConfigFilesIn($dirToSearch, 'json');
			foreach ($files as $filename) {
				$return[basename($filename, '.json')] = $filename;
			}
		}

		// all done
		return $return;
	}

	public function loadConfigFilesFrom($dirs)
	{
		// our list of loaded config files
		$return = [];

		// the files to load
		$filenames = $this->getListOfConfigFiles($dirs);

		// load the files
		foreach ($filenames as $filename)
		{
			$config = new BaseObject();
			$this->configHelper->loadConfigFile($config, $filename);
			$return[basename($filename, '.json')] = $config;
		}

		// all done
		return $return;
	}

	public function loadConfigFile($filename)
	{
		$config = new BaseObject();
		$this->configHelper->loadConfigFile($config, $filename);

		return $config;
	}
}
