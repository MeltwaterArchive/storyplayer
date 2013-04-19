<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\ProseLib\AssertActions;
use DataSift\Stone\ComparisonLib\ArrayComparitor;

class ArrayAsserts extends AssertActions
{
	public function __construct(StoryTeller $st, $params)
	{
		parent::__construct($st, new ArrayComparitor($params[0]));
	}
}