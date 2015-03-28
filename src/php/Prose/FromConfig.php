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
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

use DataSift\Stone\DataLib\DataPrinter;

/**
 * Get information from the active config (+ anything that has been
 * overriden via the -D switch)
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromConfig extends Prose
{
	public function get($name)
	{
		// what are we doing?
		$log = usingLog()->startAction("get '$name' from the active config");

		// get the details
		$config = $this->st->getActiveConfig();

		if (!$config->hasData($name)) {
			$log->endAction("no such setting '{$name}'");
			return null;
		}

		// if we get here, then success \o/
		$value = $config->getData($name);

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("value is: '{$logValue}'");

		// all done
		return $value;
	}

	public function getAll()
	{
		// what are we doing?
		$log = usingLog()->startAction("get the full active config");

		// get the details
		$config = $this->st->getActiveConfig();
		$retval = $config->getData("");

		// all done
		$log->endAction($retval);
		return $retval;
	}

	public function getModuleSetting($settingPath)
	{
		// what are we doing?
		$log = usingLog()->startAction("get module setting '{$settingPath}'");

		// get the active config
		$config = $this->st->getActiveConfig();

		// we search the config in this order
		$pathsToSearch = [
			"user.moduleSettings.{$settingPath}"            => "user's .storyplayer file",
			"systemundertest.moduleSettings.{$settingPath}" => "system under test config file",
			"target.moduleSettings.{$settingPath}"          => "test environment config file",
			"storyplayer.moduleSettings.{$settingPath}"     => "storyplayer.json config file",
		];

		foreach ($pathsToSearch as $searchPath => $origin) {
			if ($config->hasData($searchPath)) {
				$value = $config->getData($searchPath);

				// log the settings
				$printer  = new DataPrinter();
				$logValue = $printer->convertToString($value);
				$log->endAction("found in $origin: '{$logValue}'");

				return $value;
			}
		}

		// if we get here, the module setting does not exist
		throw new E5xx_ActionFailed(__METHOD__, "unable to find moduleSetting '{$settingPath}'");
	}

	public function hasModuleSetting($settingPath)
	{
		// what are we doing?
		$log = usingLog()->startAction("check if module setting '{$settingPath}' exists");

		// get the active config
		$config = $this->st->getActiveConfig();

		// we search the config in this order
		$pathsToSearch = [
			"user.moduleSettings.{$settingPath}"            => "user's .storyplayer file",
			"systemundertest.moduleSettings.{$settingPath}" => "system under test config file",
			"target.moduleSettings.{$settingPath}"          => "test environment config file",
			"storyplayer.moduleSettings.{$settingPath}"     => "storyplayer.json config file",
		];

		foreach ($pathsToSearch as $searchPath => $origin) {
			if ($config->hasData($searchPath)) {
				$log->endAction("found in $origin");

				return true;
			}
		}

		// if we get here, the module setting does not exist
		$log->endAction("not found");
		return false;
	}
}