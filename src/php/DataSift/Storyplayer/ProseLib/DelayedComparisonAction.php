<?php

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class DelayedComparisonAction
{
	protected $st;
	protected $topElement;

	public function __construct(StoryTeller $st, $newStats, $action)
	{
		$this->st = $st;
		$this->newStats = $newStats;
		$this->action   = $action;
	}

	public function since($oldStats)
	{
		$action = $this->action;
		$action($this->st, $oldStats, $this->newStats);
	}
}