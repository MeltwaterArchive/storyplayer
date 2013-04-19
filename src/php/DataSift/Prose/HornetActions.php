<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\JsonObject;

class HornetActions extends ProseActions
{
	public function startHornetDrone($clientName, $clientParams)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("start hornet-drone '{$clientName}' with params '(" . implode(', ', $clientParams) . ")");

		// build the command to run
		$env = $st->getEnvironment();
		$pathToHornet = $env->hornet->path;

		$command = $pathToHornet . '/hornet-drone ' . implode(' ', $clientParams);

		// run the command in a screen session
		$st->usingShell()->startInScreen($clientName, $command);

		// all done
		$log->endAction();
	}
}