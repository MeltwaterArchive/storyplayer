<?php

namespace DataSift\Storyplayer\UserLib;

use DataSift\Stone\PasswordLib\BasicGenerator;
use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\PlayerLib\StoryContext;
use DataSift\Storyplayer\PlayerLib\StoryCheckpoint;

class GenericUserGenerator implements UserGenerator
{
	public function getUser($config, $runtimeConfig, StoryContext $context, Story $story)
	{
		// create a container for the data
		$user = new User();

		// what kind of user is this?
		$user->addRole("loggedout user");

		// hard-coded for now
		$user->username = "EX" . time();
		$user->password = BasicGenerator::generatePassword(8, 12);
		$user->email    = "me+" . $user->username . "@example.com";

		// all done
		return $user;
	}

    public function storeUser($user, $staticConfig, $runtimeConfig)
    {
        // no action required
    }

    public function emptyCache($staticConfig, $runtimeConfig, StoryContext $context)
    {
        // can't do anything
    }
}