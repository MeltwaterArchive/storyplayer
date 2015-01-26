---
layout: v2/using-configuration
title: Configuring Storyplayer
next: '<a href="../configuration/storyplayer-json.html">Next: The storyplayer.json File</a>'
prev: '<a href="../using/storyplayer-commands/play-story.html">Prev: The play-story Command</a>'
---

# Configuring Storyplayer

Storyplayer's config files are designed to be very easy to get started with, but with the flexibility you need when larger teams are working on a shared repository of stories.

## Basic Configuration - storyplayer.json

The [storyplayer.json](storyplayer-json.html) file is the only file you need to get started with.  It contains:

* the default [settings for the applications that you are testing](app-settings.html)
* a list of the [environments to test against](storyplayer-json.html#the_environments_list)

It can also contain:

* the default [settings for Storyplayer's logging](logging.html)
* the default [test phases to execute](test-phases.html)

## Advanced Configuration Approaches

As the number of environments to test against grows, as your repository of tests grows, and as the number of people and teams re-using your tests (and contributing new ones!) grows, you'll find it very helpful to move the config for your target environments out of the _storyplayer.json_ file and into [per-environment config files](environment-config.html).

## Your Own Preferences

Sometimes, you may want to override the settings in your test repository's _storyplayer.json_ file, such as adjusting the amount of log information that Storyplayer writes to the screen, or temporarily switching off one or more test phases.

Storyplayer provides an optional [per-user config file](user-config.html) exactly for this purpose.

## Storyplayer Remembers Things Too

Finally, Storyplayer sometimes needs to remember things between test runs, such as [the hosts table](../modules/hoststable/index.html).  Storyplayer stores all of these things in the [runtime config file](runtime-config.json).

## Config File Load Order

When you run Storyplayer, it loads the following configuration files, in this order:

1. _[./storyplayer.json.dist or ./storyplayer.json](storyplayer-json.html)_ - the main configuration file for your tests.  This lives inside the same repository that your tests live inside.
1. _[$HOME/.storyplayer/storyplayer.json](user-config.html)_ - your per-user overrides and settings.  These settings apply to every test repository.  Normally used to vary logging levels.
1. _[./etc/&lt;environment&gt;.json](environment-config.html)_ - your per-test-environment settings. There is normally one file per environment that you wish to test against.  These files live inside the same repository that your tests live inside.
1. _[$HOME/.storyplayer/runtime.json](runtime-config.html)_ - the things that Storyplayer remembers between execution runs.  Storyplayer normally manages this file for you.