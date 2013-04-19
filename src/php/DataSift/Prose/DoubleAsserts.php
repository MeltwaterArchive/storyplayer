<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\ProseLib\AssertActions;
use DataSift\Stone\ComparisonLib\DoubleComparitor;

class DoubleAsserts extends AssertActions
{
	public function __construct(StoryTeller $st, $params)
	{
		parent::__construct($st, new DoubleComparitor($params[0]));
	}
}