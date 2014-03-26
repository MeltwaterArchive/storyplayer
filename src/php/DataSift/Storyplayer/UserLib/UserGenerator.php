<?php

namespace DataSift\Storyplayer\UserLib;

use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\PlayerLib\StoryContext;

interface UserGenerator
{
	public function getUser($staticConfig, $runtimeConfig, StoryContext $context, Story $story);

    /**
     * @return void
     */
    public function storeUser($user, $staticConfig, $runtimeConfig);

    /**
     * @return void
     */
    public function emptyCache($staticConfig, $runtimeConfig, StoryContext $context);
}