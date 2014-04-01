<?php

namespace DataSift\Storyplayer\UserLib;

use DataSift\Stone\PasswordLib\BasicGenerator;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

class GenericUserGenerator implements UserGenerator
{
	public function getUser(StoryTeller $st)
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

    public function storeUser(StoryTeller $st, $user)
    {
        // no action required
    }

    public function emptyCache(StoryTeller $st)
    {
        // can't do anything
    }
}