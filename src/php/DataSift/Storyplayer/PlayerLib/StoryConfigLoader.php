<?php

namespace DataSift\Storyplayer\PlayerLib;

use RuntimeException;
use DataSift\Stone\ConfigLib\JsonConfigLoader;
use DataSift\Stone\LogLib\Log;

class StoryConfigLoader extends JsonConfigLoader
{
	public function __construct()
	{
		// are we installed globally?
		if ('@@BIN_DIR@@' != '@@BIN_DIR@@') {
			// yes, we are
			parent::__construct("storyplayer", getcwd());
		}
		else {
			// we are running from the github repo clone
			parent::__construct("storyplayer", realpath(__DIR__ . '/../../../../../'));
		}
	}
}