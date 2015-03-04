---
layout: v2/modules-checkpoint
title: The Checkpoint Module
prev: '<a href="../../modules/browser/webdriver.html">Prev: The WebDriver Library</a>'
next: '<a href="../../modules/checkpoint/getCheckpoint.html">Next: getCheckpoint()</a>'
---

# The Checkpoint Module

## Introduction

The __Checkpoint__ module allows you to work with [Storyplayer's inter-phase checkpoint object](../../using/stories/the-checkpoint.html).

This module is here for convenience; you can achieve the same results using a mixture of plain PHP and the [Assertions module](../assertions/index.html).

The source code for this Prose module can be found in this PHP file:

* `Prose\functions.php`

## Dependencies

This module has no dependencies.

## Using The Checkpoint Module

Use the checkpoint module to retrieve the Checkpoint object:

{% highlight php startinline %}
$checkpoint = getCheckpoint();
{% endhighlight %}

You can then treat it as an ordinary PHP object by getting and setting attributes.