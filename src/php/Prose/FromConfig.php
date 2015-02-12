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
	public function getAppSetting($path)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get app setting '{$path}' from the storyplayer config");

		// what is the full path to this data?
		$fullPath = 'storyplayer.appSettings.' . $path;

		// get the details
		$config = $st->getActiveConfig();
		if (!$config->hasData($fullPath)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}
		$value = $config->getData($fullPath);

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("setting for '{$path}' is '{$logValue}'");

		// all done
		return $value;
	}

	public function getAppSettings($app)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get all settings for '{$app}' from the storyplayer config");

		// what is the full path to this data?
		$fullPath = 'storyplayer.appSettings.' . $app;

		// get the details
		$config = $st->getActiveConfig();
		if (!$config->hasData($fullPath)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}
		$value = $config->getData($fullPath);

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("settings for '{$app}' are '{$logValue}'");

		// all done
		return $value;
	}

	public function getModuleSetting($path)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get module setting '{$path}' from the storyplayer config");

		// what is the full path to this data?
		$fullPath = 'storyplayer.moduleSettings.' . $path;

		// get the details
		$config = $st->getActiveConfig();
		if (!$config->hasData($fullPath)) {
			throw new E5xx_ActionFailed(__METHOD__, "module setting '$path' not found");
		}

		// success!
		$value = $config->getData($fullPath);

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("setting for '{$path}' is '{$logValue}'");

		// all done
		return $value;
	}

	public function getModuleSettings($module)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get all settings for '{$module}' from the storyplayer config");

		// what is the full path to this data?
		$fullPath = 'storyplayer.moduleSettings.' . $module;

		// get the details
		$config = $st->getActiveConfig();
		if (!$config->hasData($fullPath)) {
			throw new E5xx_ActionFailed(__METHOD__, "no module '$module' found in the config");
		}

		// success!
		$value = $config->getData($fullPath);

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
		$config = $st->getActiveConfig();

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
}