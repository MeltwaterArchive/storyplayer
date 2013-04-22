<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;

class SavageDActions extends ProseActions
{
	public function deleteStatsPrefix()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("delete SavageD stats prefix");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/stats/prefix";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$newPrefix = $st->fromHttp()->get($url);
		$st->assertsString($newPrefix)->equals($prefix);

		// all done
		$log->endAction();
	}

	public function setStatsPrefix($prefix)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("set SavageD stats prefix to '{$prefix}'");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/stats/prefix";

		// make the request
		$st->usingHttp()->put($url, null, array("prefix" => $prefix));

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
		$log = $st->startAction("watch the '{$processName}' process w/ ID '{$pid}'");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/process/{$processName}/pid";

		// make the request
		$st->usingHttp()->put($url, null, array("pid" => $pid));

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
		$log = $st->startAction("stop watching the '{$processName}' process");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/process/{$processName}/pid";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":true}');

		// all done
		$log->endAction();
	}

	public function watchProcessCpu($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("watch the CPU used by the '{$processName}' process");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/process/{$processName}/cpu";

		// make the request
		$st->usingHttp()->put($url);

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
		$log = $st->startAction("stop watch the cpu used by the '{$processName}' process");

		// get the process ID of the process


		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/process/{$processName}/cpu";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":false}');

		// all done
		$log->endAction();
	}

	public function watchProcessMemory($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("watch the memory used by the '{$processName}' process");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/process/{$processName}/memory";

		// make the request
		$st->usingHttp()->put($url);

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
		$log = $st->startAction("stop watch the memory used by the '{$processName}' process");

		// get the process ID of the process


		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/process/{$processName}/memory";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":false}');

		// all done
		$log->endAction();
	}

	public function watchServerCpu($testName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("watch the server's cpu usage");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/server/{$testName}.host/cpu";

		// make the request
		$st->usingHttp()->put($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":true}');

		// all done
		$log->endAction();
	}

	public function stopWatchingServerCpu($testName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop watching the server's cpu");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/server/{$testName}.host/cpu";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":false}');

		// all done
		$log->endAction();
	}

	public function watchServerLoadavg($testName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("watch the server's load average");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/server/{$testName}.host/loadavg";

		// make the request
		$st->usingHttp()->put($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":true}');

		// all done
		$log->endAction();
	}

	public function stopWatchingServerLoadavg($testName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop watching the server's load average");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/server/{$testName}.host/loadavg";

		// make the request
		$st->usingHttp()->delete($url);

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":false}');

		// all done
		$log->endAction();
	}

	public function startMonitoring()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("tell SavageD to start sending stats to statsd");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/stats/monitoring";

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
		$log = $st->startAction("tell SavageD to stop sending stats to statsd");

		// build the URL
		$ipAddress = $this->args[0];
		$httpPort  = $st->fromEnvironment()->getAppSetting("savaged", "httpPort");
		$url       = "http://{$ipAddress}:{$httpPort}/stats/monitoring";

		// make the request
		$st->usingHttp()->post($url, array("monitoring" => false));

		// did it work?
		$response = $st->fromHttp()->get($url);
		$st->expectsHttpResponse($response)->hasStatusCode(200);
		$st->expectsHttpResponse($response)->hasBody('{"monitoring":false}');

		// all done
		$log->endAction();
	}
}