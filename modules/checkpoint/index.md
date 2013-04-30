---
layout: modules-checkpoint
title: The Checkpoint Module
prev: '<a href="../../modules/browser/webdriver.html">Prev: The WebDriver Library</a>'
next: '<a href="../../modules/checkpoint/fromCheckpoint.html">Next: fromCheckpoint()</a>'
---

# The Checkpoint Module

## Introduction

The __Checkpoint__ module allows you to work with [Storyplayer's inter-phase checkpoint object](../../stories/the-checkpoint.html).

This module is here for convenience; you can achieve the same results using a mixture of plain PHP and the [assertions module](../assertions/index.html).

The source code for this Prose module can be found in these PHP classes:

* DataSift\Storyplayer\Prose\CheckpointActions
* DataSift\Storyplayer\Prose\CheckpointDetermines

## Dependencies

This module has no dependencies.

## Using The Checkpoint Module

The basic format of an action is:

{% highlight php %}
$st->MODULE()->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromCheckpoint()](fromCheckpoint.html)_ - get data from the checkpoint
* _[usingCheckpoint()](usingCheckpoint.html)_ - put data into the checkpoint

and __action__ is one of the methods available on the __module__ you choose.

Here are some examples:

{% highlight php %}
$balance = $st->fromCheckpoint()->get('balance');
{% endhighlight %}

{% highlight php %}
$st->usingCheckpoint()->set('balance', 100);
{% endhighlight %}