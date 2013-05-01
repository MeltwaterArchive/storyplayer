<?php

namespace DataSift\Storyplayer\UserLib;

use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\PlayerLib\StoryContext;
use DataSift\Storyplayer\PlayerLib\StoryCheckpoint;

interface UserGenerator
{
	public function getUser($staticConfig, $runtimeConfig, StoryContext $context, Story $story);
	public function storeUserContextAndCheckpoint($user, StoryContext $context, StoryCheckpoint $checkpoint = null);
}