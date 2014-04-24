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

use DataSift\Stone\ConfigLib\E5xx_ConfigFileNotFound;
use DataSift\Stone\ConfigLib\LoadedConfig;

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
	/**
	 * @param StaticConfig $config
	 * @return void
	 */
	public function loadConfig($config)
	{
		// load the main config file
		try {
			// We start with an empty config
			$userConfig = new LoadedConfig;
			// Then load our user config into it
			$this->configLoader->loadUserConfig($userConfig);

			// Then try and load our default configs
			$newConfig = $this->configLoader->loadDefaultConfig();

			// Merge our user config into the default config
			$newConfig->mergeFrom($userConfig);

		// We couldn't find our default config files
		} catch (E5xx_ConfigFileNotFound $e){
			// Did we load a user config though? Get the public class
			// vars and count them. If there's > 0, we have some config
			$availableKeys = get_object_vars($userConfig);
			// Otherwise, just rethrow the exception
			if (!count($availableKeys)){
				throw $e;
			}

			// Move user config to $newConfig for merging into the
			// current config
			$newConfig = $userConfig;
		}

		// merge the new config with the existing
		$config->mergeFrom($newConfig);

		// all done
	}

	/**
	 *
	 * @param  \stdClass $config
	 * @param  string   $configName
	 * @return void
	 */
	public function loadAdditionalConfig($config, $configName)
	{
		return $this->configLoader->loadAdditionalConfig($config, $configName);
	}

	/**
	 *
	 * @return stdClass
	 */
	public function loadRuntimeConfig()
	{
		return $this->configLoader->loadRuntimeConfig();
	}

	/**
	 *
	 * @return array<string>
	 */
	public function getListOfAdditionalConfigFiles()
	{
		return $this->configLoader->getListOfAdditionalConfigFiles();
	}
}
