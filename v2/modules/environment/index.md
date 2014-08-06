---
layout: v2/modules-environment
title: The Environment Module
prev: '<a href="../../modules/checkpoint/usingCheckpoint.html">Prev: usingCheckpoint()</a>'
next: '<a href="../../modules/environment/fromEnvironment.html">Next: fromEnvironment()</a>'
---
# The Environment Module

## Introduction

The __Environment__ module allows you to pull information about the _environments_ section of [your configuration file(s)](../../configuration.html).

The source code for this Prose module can be found in this PHP class:

* DataSift\Storyplayer\Prose\FromEnvironment

## What Is The Environment?

When you run Storyplayer, the first parameter on the command-line is the name of the environment that you are running tests against.

{% highlight bash %}
storyplayer <environment-name> <path to story to run>
{% endhighlight %}

Before your story starts to run, Storyplayer loads your config files, and puts the settings under _environments -&gt; environment-name_ into an internal object called the Environment.

Your configuration files can define as many different environments as you like, but when your story runs, it will only know about the one environment that you chose.  This makes it easy to support writing your test once, and running it against many different environments (such as dev, test, staging and live).

## Dependencies

This module has no additional dependencies.

## Using The Environment Module

The basic format of an action is:

{% highlight php %}
$st->MODULE()->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromEnvironment()](fromEnvironment.html)_ - retrieve data from the environment

and __action__ is one of the documented methods available on the __module__ you choose.