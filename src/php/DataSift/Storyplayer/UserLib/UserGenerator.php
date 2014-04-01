<?php

namespace DataSift\Storyplayer\UserLib;

use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

interface UserGenerator
{
	public function getUser(StoryTeller $st);

    /**
     * @return void
     */
    public function storeUser(StoryTeller $st, $user);

    /**
     * @return void
     */
    public function emptyCache(StoryTeller $st);
}