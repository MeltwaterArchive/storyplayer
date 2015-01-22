---
layout: v2/using-stories
title: The Environment
prev: '<a href="../../using/stories/the-checkpoint.html">Prev: The Checkpoint</a>'
next: '<a href="../../using/stories/test-setup-teardown.html">Next: Test Setup / Teardown Phases</a>'
---

# The Environment

Storyplayer was originally designed to run tests against our _staging_ and _production_ environments, so that we could prove that a new feature had been successfully deployed before giving access to our customers.  This meant running the same stories against two different environments, right from the very beginning.

[Per-environment configuration files](../configuration/environment-config.html) allow you to capture the settings that are different between each environment.  But how do you get at these settings inside your test?  That's where the _Environment Object_ comes in.

## The Environment Object

The _environment object_ is a plain old PHP object that's created every time you run Storyplayer.  It contains all of the settings that apply to the environment that you are testing against, and is available for you to read from in any phase of your story:

{% highlight php %}
// gets the whole object
$env = $st->getEnvironment();

// returns $env->apache, without having to create $env first
// this is more readable, and more future-proof
$settings = $st->fromEnvironment()->getAppSettings('apache');

// gets the name of the current environment
// this is the <environment> passed on the command-line as '-e <environment>'
$envName = $st->getEnvironmentName();
{% endhighlight php %}

## The Environment Object Is Read-Only

Okay, we don't enforce this (yet ...), but you should treat the _environment object_ as being read-only.  There's no good reason for your stories to change anything inside the _environment object_.  Always treat change the _environment object_ inside your own stories or [modules of your own](../modules/making-your-own/index.html) as a code smell.

## When To Use The Environment Object

The whole point of the _environment object_ is to make your stories _environment-independent_.  Don't hard-code URLs, port numbers, usernames, passwords or anything at all.  Add them to your [per-environment config files](../configuration/environment-config.html) so that you can edit every setting to suit each environment.

If you have settings that are common to all environments, you can add them to the _defaults_ environment in your [storyplayer.json](../configuration/storyplayer-json.html) file, so that they are shared across all of your environments.  Don't worry, you can still override them in your [per-environment config files](../configuration/environment-config.html) if needed (for example, for a development environment).