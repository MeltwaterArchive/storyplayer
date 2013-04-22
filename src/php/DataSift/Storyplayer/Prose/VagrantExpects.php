<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\JsonObject;

class VagrantExpects extends ProseActions
{
	public function boxIsRunning($boxName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure VM '{$boxName}' running");

		// is it running?
		$running = $st->fromVagrant()->getBoxIsRunning($boxName);
		if (!$running) {
			$log->endAction();
			throw new E5xx_ExpectFailed(__METHOD__, 'VM running', 'VM not running');
		}

		// all done
		$log->endAction();
	}

	public function packageIsInstalled($boxName, $packageName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure package '{$packageName}' is installed in VM '{$boxName}'");

		// is it installed?
		$details = $st->fromVagrant()->getInstalledPackageDetails($boxName, $packageName);

		if (!isset($details->version)) {
			$log->endAction();
			throw new E5xx_ExpectFailed(__METHOD__, "package installed", "package is not installed");
		}

		// all done
		$log->endAction();
	}

	public function processIsRunning($boxName, $processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure process '{$processName}' is running in VM '{$boxName}'");

		// is the process running?
		$isRunning = $st->fromVagrant()->getProcessIsRunning($boxName, $processName);

		if (!$isRunning) {
			throw new E5xx_ExpectFailed(__METHOD__, "process '{$processName}' running", "process '{$processName}' is not running");
		}

		// all done
		$log->endAction();
	}

	public function processIsNotRunning($boxName, $processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure process '{$processName}' is not running in VM '{$boxName}'");

		// is the process running?
		$isRunning = $st->fromVagrant()->getProcessIsRunning($boxName, $processName);

		if ($isRunning) {
			throw new E5xx_ExpectFailed(__METHOD__, "process '{$processName}' not running", "process '{$processName}' is running");
		}

		// all done
		$log->endAction();
	}

}