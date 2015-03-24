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

/**
 * do things with SavageD. SavageD is DataSift's real-time server and
 * process monitoring API-driven daemon
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingSavageD extends HostBase
{
	public function deleteStatsPrefix()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("delete SavageD stats prefix on host '{$this->args[0]}'");

		// where are we doing this?
		$url = $this->getSavagedUrl() . "/stats/prefix";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$newPrefix = $st->fromHttp()->get($url);
		$st->assertsString($newPrefix)->equals('');

		// all done
		$log->endAction();
	}

	public function setStatsPrefix($prefix)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("set SavageD stats prefix to '{$prefix}' on host '{$this->args[0]}'");

		// where are we doing this?
		$url = $this->getSavagedUrl() . "/stats/prefix";

		// make the request
		$st->usingHttp()->post($url, null, array("prefix" => $prefix));

		// did it work?
		$response = $st->fromHttp()->get($url);

		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"prefix":"' . $prefix . '"}');

		// all done
		$log->endAction();
	}

	public function watchProcess($processName, $pid)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("watch the '{$processName}' process w/ ID '{$pid}' on host '{$this->args[0]}'");

		// where are we doing this?
		$safeProcessName = urlencode($this->args[0] . '.processes.' . $processName);
		$url = $this->getSavagedUrl() . "/process/{$safeProcessName}/pid";

		// make the request
		$st->usingHttp()->post($url, null, array("pid" => $pid));

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"pid":"' . $pid . '"}');

		// all done
		$log->endAction();
	}

	public function stopWatchingProcess($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop watching the '{$processName}' process on host '{$this->args[0]}'");

		// where are we doing this?
		$safeProcessName = urlencode($this->args[0] . '.processes.' . $processName);
		$url = $this->getSavagedUrl() . "/process/{$safeProcessName}/pid";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(404);
		$st->expectsHttpResponse($response)->hasBody('{"error": "no such alias"}');

		// all done
		$log->endAction();
	}

	public function watchProcessCpu($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("watch the CPU used by the '{$processName}' process on host '{$this->args[0]}'");

		// build the URL
		// where are we doing this?
		$safeProcessName = urlencode($this->args[0] . '.processes.' . $processName);
		$url = $this->getSavagedUrl() . "/process/{$safeProcessName}/cpu";

		// make the request
		$st->usingHttp()->post($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":true}');

		// all done
		$log->endAction();
	}

	public function stopWatchingProcessCpu($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop watch the cpu used by the '{$processName}' process on host '{$this->args[0]}'");

		// where are we doing this?
		$safeProcessName = urlencode($this->args[0] . '.processes.' . $processName);
		$url = $this->getSavagedUrl() . "/process/{$safeProcessName}/url";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(404);
		$st->expectsHttpResponse($response)->hasBody('{"error": "no such alias"}');

		// all done
		$log->endAction();
	}

	public function watchProcessMemory($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("watch the memory used by the '{$processName}' process on host '{$this->args[0]}'");

		// build the URL
		// where are we doing this?
		$safeProcessName = urlencode($this->args[0] . '.processes.' . $processName);
		$url = $this->getSavagedUrl() . "/process/{$safeProcessName}/memory";

		// make the request
		$st->usingHttp()->post($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":true}');

		// all done
		$log->endAction();
	}

	public function stopWatchingProcessMemory($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop watch the memory used by the '{$processName}' process on host '{$this->args[0]}'");

		// where are we doing this?
		$safeProcessName = urlencode($this->args[0] . '.processes.' . $processName);
		$url = $this->getSavagedUrl() . "/process/{$safeProcessName}/memory";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(404);
		$st->expectsHttpResponse($response)->hasBody('{"error": "no such alias"}');

		// all done
		$log->endAction();
	}

	public function watchServerCpu()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("watch the server's cpu usage on host '{$this->args[0]}'");

		// where are we doing this?
		$safeTestName = urlencode($this->args[0] . '.host');
		$url = $this->getSavagedUrl() . "/server/{$safeTestName}/cpu";

		// make the request
		$st->usingHttp()->post($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":true}');

		// all done
		$log->endAction();
	}

	public function stopWatchingServerCpu()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop watching the server's cpu on host '{$this->args[0]}'");

		// where are we doing this?
		$safeTestName = urlencode($this->args[0] . '.host');
		$url = $this->getSavagedUrl() . "/server/{$safeTestName}/cpu";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(404);
		$st->expectsHttpResponse($response)->hasBody('{"error": "no such alias"}');

		// all done
		$log->endAction();
	}

	public function watchServerLoadavg()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("watch the server's load average on host '{$this->args[0]}'");

		// where are we doing this?
		$safeTestName = urlencode($this->args[0] . '.host');
		$url = $this->getSavagedUrl() . "/host/{$safeTestName}/loadavg";

		// make the request
		$st->usingHttp()->post($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":true}');

		// all done
		$log->endAction();
	}

	public function stopWatchingServerLoadavg()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop watching the server's load average on host '{$this->args[0]}'");

		// where are we doing this?
		$safeTestName = urlencode($this->args[0] . '.host');
		$url = $this->getSavagedUrl() . "/host/{$safeTestName}/loadavg";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(404);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":false}');

		// all done
		$log->endAction();
	}

	public function watchServerDiskstats()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("watch the server's diskstats on host '{$this->args[0]}'");

		// where are we doing this?
		$safeTestName = urlencode($this->args[0] . '.host');
		$url = $this->getSavagedUrl() . "/host/{$safeTestName}/diskstats";

		// make the request
		$st->usingHttp()->post($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":true}');

		// all done
		$log->endAction();
	}

	public function stopWatchingServerDiskstats()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop watching the server's diskstats on host '{$this->args[0]}'");

		// where are we doing this?
		$safeTestName = urlencode($this->args[0] . '.host');
		$url = $this->getSavagedUrl() . "/host/{$safeTestName}/diskstats";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(404);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":false}');

		// all done
		$log->endAction();
	}

	public function startMonitoring()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("tell SavageD on host '{$this->args[0]}' to start sending stats to statsd");

		// where are we doing this?
		$url = $this->getSavagedUrl() . "/stats/monitoring";

		// make the request
		$st->usingHttp()->post($url, null, array("monitoring" => "true"));

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":true}');

		// all done
		$log->endAction();
	}

	public function stopMonitoring()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("tell SavageD on host '{$this->args[0]}' to stop sending stats to statsd");

		// where are we doing this?
		$url = $this->getSavagedUrl() . "/stats/monitoring";

		// make the request
		$st->usingHttp()->post($url, array("monitoring" => false));

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":false}');

		// all done
		$log->endAction();
	}

	protected function getSavagedUrl()
	{
		// where is SavageD running?
		$hostDetails = $this->getHostDetails();

		// do we have the module settings we need?
		if (!isset($hostDetails->moduleSettings, $hostDetails->moduleSettings->savaged, $hostDetails->moduleSettings->savaged->httpPort)) {
			throw new E5xx_ActionFailed(__METHOD__, "moduleSettings.savaged.httpPort not set for host '{$this->args[0]}'");
		}

		$url = "http://" . $hostDetails->ipAddress . ":" . $hostDetails->moduleSettings->savaged->httpPort;

		return $url;
	}
}