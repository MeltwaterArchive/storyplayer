---
layout: configuration
title: The Runtime Configuration
prev: '<a href="../configuration/user-config.html">Prev: Per-User Configuration</a>'
next: '<a href="../stories/index.html">Next: Introducing Stories</a>'
---

# The Runtime Configuration

## Persisted Data

Sometimes, Storyplayer needs to remember information between executions, such as:

* _the [hosts table](../modules/hoststable/index.html)_ - a list of virtual machines that it has provisioned but not yet destroyed
* _the [test user](../stories/test-users.html)_ - the user that you're logging into your application as

Storyplayer logs this information in the file _$HOME/.storyplayer/runtime.json_ for you.

## When You Need To Edit This File

Most of the time, you can let Storyplayer manage the runtime config file for you.  The only times that you need to edit this file yourself are:

* if it contains an entry for a virtual machine that you have destroyed manually outside of Storyplayer
* if you want Storyplayer to create a brand-new test user for you

## Persisting Data From Your Own Modules

You're very welcome to use the runtime config to [persist data in your own prose modules](../prose/persisting-data.html).