<?php

namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Storyplayer\StoryLib\Story;

abstract class EnvironmentSetup
{

	protected $story;

	public function setStory(Story $story)
	{
		$this->story = $story;
	}

	abstract public function getName();
	abstract public function setUp(StoryTeller $st);
	abstract public function tearDown(StoryTeller $st);
}
