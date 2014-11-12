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

/**
 * Helper to try and bridge the gap between Storyplayer v1 and v2.
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class EnvironmentHelper extends Prose
{
	public function getAppSetting($appName, $settingName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get $settingName for '{$appName}'");

		// do we have any settings anywhere for this app?
		$appSettings = $this->getAppSettings($appName);

		// do we have the setting we want?
		if (!isset($appSettings->$settingName)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// if we get here, then we have what we want
		$value = $appSettings->$appName;
		$logValue = $printer->convertToString($value);
		$log->endAction("setting for '{$appName}' is '{$logValue}'");

		// all done
		return $value;
	}

	public function getAppSettings($appName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get all settings for $appName");

		// do we have any in the storyplayer.json file?
		$config = $st->getConfig();
		if (isset($config->appSettings, $config->appSettings->$appName)) {
			// success!
			$value = $config->appSettings->$appName;

			// log the settings
			$printer  = new DataPrinter();
			$logValue = $printer->convertToString($value);
			$log->endAction("settings for '{$appName}' are '{$logValue}'");

			// all done
			return $value;
		}

		// TODO: search test environments too?

		// if we get here, then we could not find the settings
		throw new E5xx_ActionFailed(__METHOD__);
	}

	public function __get($paramName)
	{
		return $this->getAppSettings($paramName);
	}
}
