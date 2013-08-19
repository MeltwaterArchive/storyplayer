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

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\DataLib\DataPrinter;

/**
 * Get information from the environment defined in the config file(s)
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class EnvironmentDetermine extends Prose
{
	public function getAppSetting($app, $setting)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get $setting for '{$app}'");

		// get the details
		$env = $st->getEnvironment();
		if (!isset($env->$app, $env->$app->$setting)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}
		$value = $env->$app->$setting;

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
		$log = $st->startAction("get all settings for '{$app}'");

		// get the details
		$env = $st->getEnvironment();
		if (!isset($env->$app)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}
		$value = $env->$app;

		// log the settings
		$printer  = new DataPrinter();
		$logValue = $printer->convertToString($value);
		$log->endAction("settings for '{$app}' are '{$logValue}'");

		// all done
		return $value;
	}

	public function getGraphiteUrl()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get base url for graphite");

		// get the details
		$env = $st->getEnvironment();
		$graphiteUrl = $env->graphite->url;

		// all done
		$log->endAction("graphite url is '{$graphiteUrl}");
		return $graphiteUrl;
	}

	public function getHostAddress()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get IP address of Storyteller's host");

		// get the details
		$env = $st->getEnvironment();
		$ipAddress = $env->host->ipAddress;

		// all done
		$log->endAction("'{$ipAddress}'");
		return $ipAddress;
	}

	public function getHostHostname()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get the hostname of Storyteller's host");

		// get the details
		$env = $st->getEnvironment();
		$hostname = $env->host->name;

		// all done
		$log->endAction("'{$hostname}'");
		return $hostname;
	}

	public function getStatsdHost()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get host details for statsd");

		// get the details
		$env = $st->getEnvironment();
		$statsdHost = $env->statsd->host;

		// all done
		$log->endAction("statsd host is '{$statsdHost}");
		return $statsdHost;
	}
}