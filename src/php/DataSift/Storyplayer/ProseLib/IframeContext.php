<?php

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Storyplayer\StoryLib\StoryContext;

class IframeContext extends PageContext
{
	public function __construct($iframeId) {
		$this->pageContextAction = function(StoryContext $context) use($iframeId) {
			$browser = $context->browserSession;

			$browser->frame(array('id' => $iframeId));
		};
	}
}