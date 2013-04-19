<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\ProseLib\IframeContext;

class IframeDetermine extends CurrentPageDetermine
{
	public function __construct(StoryTeller $st, $params)
	{
		// call our parent constructor
		parent::__construct($st);

		// switch to the specified iframe
		$pageContext = new IFrameContext($params[0]);
		$st->setPageContext($pageContext);
	}
}