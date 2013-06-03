<?php

namespace DataSift\Storyplayer\UserLib;

use stdClass;
use DataSift\Stone\PasswordLib\BasicGenerator;
use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\PlayerLib\StoryContext;
use DataSift\Storyplayer\PlayerLib\StoryCheckpoint;

class ConfigUserLoader implements UserGenerator
{
    protected $generator;

    public function __construct($generator)
    {
        $this->generator = $generator;
    }

    public function getUser($staticConfig, $runtimeConfig, StoryContext $context, Story $story)
    {
        // what environment are we working in?
        $envName = $context->env->envName;

        // do we have a cached user from telleroftales, or a previous
        // storyplayer?
        if (isset($runtimeConfig->users, $runtimeConfig->users->$envName))
        {
            $user = new User();
            $user->mergeFrom($runtimeConfig->users->$envName);
            $runtimeConfig->users->$envName = $user;

            return $user;
        }

        // if we get here, then there's no previous user to reuse
        if (!isset($runtimeConfig->users)) {
            $runtimeConfig->users = (object)array();
        }
        $runtimeConfig->users->$envName = $this->generator->getUser($staticConfig, $runtimeConfig, $context, $story);

        // all done
        return $runtimeConfig->users->$envName;
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