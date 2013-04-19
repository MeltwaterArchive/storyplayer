<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Storyplayer\ProseLib\AssertActions;
use DataSift\Stone\ComparisonLib\ObjectComparitor;

class ObjectAsserts extends AssertActions
{
	public function __construct(StoryTeller $st, $params)
	{
		parent::__construct($st, new ObjectComparitor($params[0]));
	}
}