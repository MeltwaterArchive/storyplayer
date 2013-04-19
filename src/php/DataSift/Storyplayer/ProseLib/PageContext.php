<?php

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

class PageContext
{
	protected $pageContextAction = null;

	public function switchToContext(StoryTeller $st)
	{
		if (is_callable($this->pageContextAction)) {
			$callback = $this->pageContextAction;

			$callback($st);
		}
	}
}