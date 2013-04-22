<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\JsonObject;

class DoppeldActions extends ProseActions
{
	public function start($testCaseName, $testCase)
	{
		// shorthand
		$st  = $this->st;
		$env = $st->getEnvironment();

		// what are we doing?
		$log = $st->startAction("start doppeld with test scenario '{$testCaseName}' ({$testCase})");

		// make sure this environment is configured for doppeld
		if (!isset($env->doppeld)) {
			throw new E5xx_ActionFailed(__METHOD__, "environment has no configuration for doppeld");
		}
		if (!isset($env->doppeld->dir)) {
			throw new E5xx_ActionFailed(__METHOD__, "doppeld configuration has no 'dir' setting");
		}
		$doppelDir = $env->doppeld->dir;

		// build up the command to run
		$command = "cd '{$doppelDir}' && node ./server.js {$testCase}";

		// run the command
		$log->addStep("start doppeld with command '{$command}'", function() use($st, $command, $testCaseName) {
			$st->usingShell()->startInScreen($testCaseName, $command);

			// wait before continuing
			sleep(1);
		});

		// make sure that it's running
		$st->expectsShell()->isRunningInScreen($testCaseName);

		// all done
		$log->endAction();
	}

	public function stop($testCaseName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop doppeld running test scenario '{$testCaseName}'");

		// stop it
		$st->usingShell()->stopInScreen($testCaseName);

		// all done
		$log->endAction();
	}
}