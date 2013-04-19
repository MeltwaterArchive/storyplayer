<?php

namespace DataSift\Storyplayer\UserLib;

use stdClass;
use DataSift\Stone\PasswordLib\BasicGenerator;
use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\PlayerLib\StoryContext;
use DataSift\Storyplayer\PlayerLib\StoryCheckpoint;

class TmpFileUserLoader implements UserGenerator
{
	protected $generator;

	public function __construct($generator)
	{
		$this->generator = $generator;
	}

	public function getUser($config, StoryContext $context, Story $story)
	{
		$filename = $this->getFilename($context->env->envName);

	    // do we have a cached user from telleroftales, or a previous
	    // storyplayer?
		if (file_exists($filename)) {
			$rawUser = json_decode(file_get_contents($filename));
			$user    = new User();
			$user->initFromJson($rawUser);
			return $user;
		}

		// if we get here, then there's no previous user to reuse
		return $this->generator->getUser($config, $context, $story);
	}

	public function storeUserContextAndCheckpoint($user, StoryContext $context, StoryCheckpoint $checkpoint = null)
	{
		$filename = $this->getFilename($context->env->envName);

	    // write the user out to disk, so that we can re-use him
	    // when writing more contained actions
	    file_put_contents($filename, json_encode($user));

	    // we choose not to store the context and checkpoint at this time
	    // we will revisit this in future, I'm sure
	}

	public function emptyCache($envName)
	{
		$filename = $this->getFilename($envName);

		if (file_exists($filename)) {
			unlink($filename);
		}
	}

	protected function getFilename($envName)
	{
		return '/tmp/storyteller-user.' . $envName . '.json';
	}
}