<?php

namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Stone\ConfigLib\JsonConfigLoader;
use DataSift\Stone\LogLib\Log;

class RuntimeConfigManager
{
	public function getConfigDir()
	{
		static $configDir = null;

		// do we have a configDir remembered yet?
		if (!$configDir)
		{
			$configDir = getenv("HOME") . '/.storyteller';
		}

		return $configDir;
	}

	public function makeConfigDir()
	{
		// what is the path to the config directory?
		$configDir = $this->getConfigDir();

		// does it exist?
		if (!file_exists($configDir))
		{
			$success = mkdir($configDir, 0700, true);
			if (!$success)
			{
				// cannot create it - bail out now
				Log::logError("Unable to create config directory '{$configDir}'");
				exit(1);
			}
		}
	}
}