<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\BaseObject;

class ShellActions extends ProseActions
{
	public function startInScreen($screenName, $commandLine)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run process '{$screenName}' ({$commandLine}) in the background");

		// we're going to save some info in the environment
		$env = $st->getEnvironment();

		// this is the data that we're going to store
		$appData = new BaseObject();
		if (!isset($env->screen)) {
			$env->screen = new BaseObject();
		}
		if (!isset($env->screen->sessions)) {
			$env->screen->sessions = new BaseObject();
		}
		$env->screen->sessions->$screenName = $appData;

		// we need to create a unique screen name
		$appData->screenName = $screenName . '_' . date('YmdHis');

		// build up our command to run
		$appData->commandLine = "screen -d -m -S " . $appData->screenName
		         . ' bash -c "' . $commandLine . ' && sleep 5"';

		// run our command
		//
		// this creates a detached screen session called $appData->screenName
		$log->addStep("run commandline '{$appData->commandLine}'", function() use($appData) {
			passthru($appData->commandLine);
		});

		// find the PID of the screen session, for future use
		$appData->pid = trim(`screen -ls | grep {$appData->screenName} | awk -F. '{print $1}'`);

		// did the process start, or has it already terminated?
		if (empty($appData->pid)) {
			$log->endAction("process failed to start");
			throw new E5xx_ActionFailed(__METHOD__, "failed to start process '{$screenName}'");
		}

		// all done
		$log->endAction("process running as '{$appData->screenName}' ({$appData->pid})");
	}

	public function stopInScreen($screenName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop process '{$screenName}'");

		// get the app details
		$appData = $st->fromShell()->getScreenSessionDetails($screenName);

		// stop the process
		$st->usingShell()->stopProcess($appData->pid);

		// all done
		$log->endAction();
	}

	public function stopAllScreens()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop all running screen processes");

		// get the app details
		$apps = $st->fromShell()->getAllScreenSessions();

		// stop the process
		foreach ($apps as $appData) {
			$st->usingShell()->stopProcess($appData->pid);
		}

		// all done
		$log->endAction();
	}

	public function stopProcess($pid)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("stop process '{$pid}'");

		// is the process running at all?
		if (!$st->fromShell()->getIsProcessRunning($pid)) {
			$log->endAction("process is not running");
			return;
		}

		// yes it is, so stop it
		// send a TERM signal to the screen session
		$log->addStep("send SIGTERM to process '{$pid}'", function() use ($pid) {
			posix_kill($pid, SIGTERM);
		});

		// has this worked?
		$log->addStep("wait for process to terminate", function() use($st, $pid) {
			for($i = 0; $i < 2; $i++) {
				if ($st->fromShell()->getIsProcessRunning($pid)) {
					// process still exists
					sleep(1);
				}
			}
		});

		if (posix_kill($pid, 0)) {
			$log->addStep("send SIGKILL to process '{$pid}'", function() use($pid) {
				posix_kill($pid, SIGKILL);
				sleep(1);
			});
		}

		// success?
		if ($st->fromShell()->getIsProcessRunning($pid)) {
			$log->endAction("process is still running :(");
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done
		$log->endAction("process has finished");
	}
}