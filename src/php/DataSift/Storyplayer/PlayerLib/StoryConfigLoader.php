<?php

namespace DataSift\Storyplayer\PlayerLib;

use RuntimeException;
use DataSift\Stone\ConfigLib\JsonConfigLoader;
use DataSift\Stone\LogLib\Log;

class StoryConfigLoader extends JsonConfigLoader
{
	public function __construct()
	{
		parent::__construct("storyteller", realpath(__DIR__ . '/../../../../'));
	}
}