<?php

namespace DataSift\Storyplayer\UserLib;

use stdClass;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

class ConfigUserLoader implements UserGenerator
{
    protected $generator;

    public function __construct($generator)
    {
        $this->generator = $generator;
    }

    public function getUser(StoryTeller $st)
    {
        // shorthand
        $runtimeConfig = $st->getRuntimeConfig();

        // what environment are we working in?
        $testEnvName = $st->getTestEnvironmentName();

        // do we have a cached user from a previous storyplayer?
        if (isset($runtimeConfig->users, $runtimeConfig->users->$testEnvName))
        {
            $user = new User();
            $user->mergeFrom($runtimeConfig->users->$testEnvName);
            $runtimeConfig->users->$testEnvName = $user;

            return $user;
        }

        // if we get here, then there's no previous user to reuse
        if (!isset($runtimeConfig->users)) {
            $runtimeConfig->users = new stdClass();
        }
        $runtimeConfig->users->$testEnvName = $this->generator->getUser($st);

        // all done
        return $runtimeConfig->users->$testEnvName;
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