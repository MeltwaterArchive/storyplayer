<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\JsonObject;

class ShellDetermine extends ProseActions
{
	public function getIsScreenRunning($screenName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("check if  process '{$screenName}' is still running");

		// get the details
		$appData = $st->fromShell()->getScreenSessionDetails($screenName);

		// is it still running?
		$isRunning = $st->fromShell()->getIsProcessRunning($appData->pid);

		// all done
		if ($isRunning) {
			$log->endAction("still running");
			return true;
		}
		else {
			$log->endAction("not running");
			return false;
		}
	}

	public function getIsProcessRunning($pid)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is process w/ PID '{$pid}' running?");

		// do we *have* a valid PID?
		if (empty($pid)) {
			$log->endAction("process has no PID; did not start?");
			return false;
		}

		// is the process running at all?
		if (!posix_kill($pid, 0)) {
			$log->endAction("process is not running");
			return false;
		}

		$log->endAction("process is running");
		return true;
	}

	public function getScreenSessionDetails($screenName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get details about process '{$screenName}'");

		// are there any details?
		$env = $st->getEnvironment();
		if (!isset($env->screen->sessions)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}
		if (!isset($env->screen->sessions->$screenName)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// we have some data :)
		$appData = $env->screen->sessions->$screenName;

		// all done
		$log->endAction();
		return $appData;
	}

	public function getAllScreenSessions()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get details about all screen processes");

		// are there any details?
		$env = $st->getEnvironment();
		if (!isset($env->screen->sessions)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// we have some data :)
		$apps = $env->screen->sessions;

		// all done
		$log->endAction();
		return $apps;
	}

}