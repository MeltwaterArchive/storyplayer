<?php

namespace DataSift\Storyplayer\UserLib;

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
        $context       = $st->getStoryContext();
        $story         = $st->getStory();
        $runtimeConfig = $st->getRuntimeConfig();

        // what environment are we working in?
        $envName = $context->envName;

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
        $runtimeConfig->users->$envName = $this->generator->getUser($st);

        // all done
        return $runtimeConfig->users->$envName;
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