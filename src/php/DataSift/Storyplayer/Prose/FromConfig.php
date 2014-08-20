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

namespace DataSift\Storyplayer\Prose;

use DataSift\Stone\DataLib\DataPrinter;

/**
 * Get information from the loaded config (+ anything that has been
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
	public function getAppSetting($app, $setting)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get $setting for '{$app}' from the storyplayer config");

		// get the details
		$config = $st->getConfig();
		if (!isset($config->appSettings, $config->appSettings->$app, $config->appSettings->$app->$setting)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}
		$value = $config->appSettings->$app->$setting;

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("$setting for '{$app}' is '{$logValue}'");

		// all done
		return $value;
	}

	public function getAppSettings($app)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get all settings for '{$app}' from the storyplayer config");

		// get the details
		$config = $st->getConfig();
		if (!isset($config->appSettings, $config->appSettings->$app)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}
		$value = $config->appSettings->$app;

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("settings for '{$app}' are '{$logValue}'");

		// all done
		return $value;
	}

	public function getModuleSetting($module, $setting)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get $setting for '{$module}' from the storyplayer config");

		// get the details
		$config = $st->getConfig();
		if (!isset($config->storyplayer->modules)) {
			throw new E5xx_ActionFailed(__METHOD__, "no 'modules' section in your storyplayer.json config");
		}
		if (!isset($config->storyplayer->modules->$module)) {
			throw new E5xx_ActionFailed(__METHOD__, "no 'modules->$module' section in your storyplayer.json config");
		}
		if (!isset($config->storyplayer->modules->$module->$setting)) {
			throw new E5xx_ActionFailed(__METHOD__, "no 'modules->{$module}->{$setting}' setting in your storyplayer.json config");
		}
		$value = $config->storyplayer->modules->$module->$setting;

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("$setting for '{$module}' is '{$logValue}'");

		// all done
		return $value;
	}

	public function getModuleSettings($module)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get all settings for '{$module}' from the storyplayer config");

		// get the details
		$config = $st->getConfig();
		if (!isset($config->storyplayer->modules)) {
			throw new E5xx_ActionFailed(__METHOD__, "no 'modules' section in your storyplayer.json config");
		}
		if (!isset($config->storyplayer->modules->$module)) {
			throw new E5xx_ActionFailed(__METHOD__, "no 'modules->$module' section in your storyplayer.json config");
		}
		$value = $config->storyplayer->modules->$module;

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("settings for '{$module}' are '{$logValue}'");

		// all done
		return $value;
	}

	public function get($name)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get '$name' from the storyplayer config");

		// get the details
		$config = $st->getConfig();

		// find what we are looking for
		$parts = explode('.', $name);
		$currentLevel = $config;
		$endPart = array_pop($parts);

		foreach ($parts as $part) {
			if (!isset($currentLevel->$part)) {
				$log->endAction("no such setting '{$name}'");
				return null;
			}
			$currentLevel = $currentLevel->$part;
		}

		if (!isset($currentLevel->$endPart)) {
			$log->endAction("no such setting '{$name}'");
			return null;
		}

		// if we get here, then success \o/
		$value = $currentLevel->$endPart;

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("value is: '{$logValue}'");

		// all done
		return $value;
	}
}