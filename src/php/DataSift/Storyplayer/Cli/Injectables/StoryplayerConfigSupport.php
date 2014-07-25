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
use DataSift\Stone\ConfigLib\E4xx_InvalidConfigFile;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * support for working with Storyplayer's config file
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
trait Injectables_StoryplayerConfigSupport
{
	public $storyplayerConfig;

	public function initStoryplayerConfigSupport(Injectables $injectables, $configFilename)
	{
		// shorthand
		$output = $injectables->output;

		// we start with an empty object
		$config = new BaseObject;

		try {
			// try to load our main config file
			$injectables->staticConfigManager->loadDefaultConfig(
				$config,
				$configFilename
			);
		}
		catch (E4xx_ConfigFileNotFound $e) {
			// there is no default config file
			//
			// it isn't fatal, but we do want to tell people about it
			$output->logCliWarning("storyplayer config file '$configFilename' not found");
		}
		catch (E4xx_InvalidConfigFile $e) {
			// we either can't read the config file, or it contains
			// invalid JSON
			//
			// that is fatal
			$output->logCliError("unable to read or prase storyplayer config file '$configFilename'");
			exit(1);
		}

		// all done
		$this->storyplayerConfig = json_encode($config);
		return $this->storyplayerConfig;
	}
}
