<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\JsonObject;

class VagrantDetermine extends ProseActions
{
	public function getDetails($boxName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("retrieve details for Vagrant VM '{$boxName}'");

		// do we have *any* VM details?
		$runtimeConfig = $st->getRuntimeConfig();
		if (!isset($runtimeConfig->vagrant, $runtimeConfig->vagrant->vms)) {
			$log->endAction("there are no details about any VMs!!");
			throw new E5xx_ActionFailed(__METHOD__, "no details about any VMs");
		}

		// do we have the details about *this* VM?
		if (!isset($runtimeConfig->vagrant->vms->$boxName)) {
			$log->endAction("there are no details about Vagrant VM '{$boxName}'");
			throw new E5xx_ActionFailed(__METHOD__, "no details about VM '{$boxName}'");
		}

		// return the details
		$log->endAction();
		return $runtimeConfig->vagrant->vms->$boxName;
	}

	public function getBoxIsRunning($boxName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is VM '{$boxName}' running?");

		// get the VM details
		try {
			$boxDetails = $st->fromVagrant()->getDetails($boxName);
		}
		catch (E5xx_ActionFailed $e) {
			// the box does not exist
			return false;
		}

		// if the box is running, it should have a status of 'running'
		$status = '';
		$log->addStep("determine status of Vagrant VM", function() use($st, $boxName, &$status) {
			$command = "vagrant status | grep default | awk '{print \$2'}";
			$status = $st->usingVagrant()->runVagrantCommand($boxName, $command);
		});
		if ($status != 'running') {
			$log->endAction("VM is not running; state is '{$status}'");
			return false;
		}

		// all done
		$log->endAction("VM is running");
		return true;
	}

	public function getIpAddress($boxName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get IP address of Vagrant VM '{$boxName}'");

		// get the VM details
		$boxDetails = $st->fromVagrant()->getDetails($boxName);

		// do we already know the IP address?
		if (isset($boxDetails->ipAddress)) {
			$log->endAction("IP address is '{$boxDetails->ipAddress}'");
			return $boxDetails->ipAddress;
		}

		// log into the machine, and ask it
		$ipAddress = '';
		$log->addStep("determine IP address of VM", function() use($st, $boxName, &$ipAddress) {
			$command = "/sbin/ifconfig eth1 | grep 'inet addr' | awk -F: '{print \\\$2}' | awk '{print \\\$1}'";
			$ipAddress = $st->usingVagrant()->runVagrantCommand($boxName, "vagrant ssh -c \"{$command}\"");
		});

		// cache the IP address, because it is a slow process to keep
		// finding it out
		$boxDetails->ipAddress = $ipAddress;

		// all done
		$log->endAction("IP address is '{$ipAddress}'");
		return $ipAddress;
	}

	public function getInstalledPackageDetails($boxName, $packageName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get details for package '{$packageName}' installed in VM '{$boxName}'");

		// get the details
		$command = "sudo yum list installed {$packageName} | grep '{$packageName}' | awk '{print \\\$1,\\\$2,\\\$3}'";
		$details = $st->usingVagrant()->runCommandInBox($boxName, $command);

		// any luck?
		$parts = explode(' ', $details);
		if (count($parts) < 3) {
			$log->endAction("could not get details ... package not installed?");
			return new JsonObject();
		}

		// we have some information to return
		$return = new JsonObject();
		$return->name = $parts[0];
		$return->version = $parts[1];
		$return->repo = $parts[2];

		// all done
		$log->endAction();
		return $return;
	}

	public function getProcessIsRunning($boxName, $processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is process '{$processName}' running on VM '{$boxName}'?");

		// SSH in and have a look
		$command = "ps -ef | awk '{ print \\\$8 }' | grep '[" . $processName{0} . "]" . substr($processName, 1) . "'";
		$details = $st->usingVagrant()->runCommandInBox($boxName, $command);

		// what did we find?
		if (empty($details)) {
			$log->endAction("not running");
			return false;
		}

		// success
		$log->endAction("is running");
		return true;
	}

	public function getPid($boxName, $processName)
	{
		// alias the storyteller object
		$st = $this->st;

		// log some info to the user
		$log = $st->startAction("get memory usage for process '{$processName}' running on VM '{$boxName}'");

		// run the command to get the process id
		$command = "ps -ef | grep '[" . $processName{0} . "]" . substr($processName, 1) . "' | awk '{print \\\$2}'";
		$details = $st->usingVagrant()->runCommandInBox($boxName, $command);

		// check that we got something
		if (empty($details)) {
			$log->endAction("could not get pid ... is the process running?");
			return 0;
		}

		// check that we found exactly one process
		$pids = explode("\n", $details);
		if (count($pids) != 1) {
			$log->endAction("found more than one process but expecting only one ... is this correct?");
			return 0;
		}

		// we can now reason that we have the correct pid
		$pid = $pids[0];

		// all done
		$log->endAction("{$pid}");
		return $pid;
	}
}