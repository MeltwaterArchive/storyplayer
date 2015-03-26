---
layout: v2/modules-storyplayer
title: The Storyplayer Module
prev: '<a href="../../modules/shell/usingShell.html">Prev: usingShell()</a>'
next: '<a href="../../modules/storyplayer/fromStoryplayer.html">Next: fromStoryplayer()</a>'
updated_for_v2: true
---

# The Storyplayer Module

## Introduction

The __Storyplayer__ module allows you to retrieve settings from your `storyplayer.json` config file.

The source code for this module can be found in:

* `Prose\FromStoryplayer`

## Dependencies

This module has no dependencies.

## Using The Storyplayer Module

The basic format of an action is:

{% highlight php startinline %}
$configSetting = fromStoryplayer()->METHOD("<dot.notation.path>");
{% endhighlight %}

where:

* __METHOD__ is one of the methods provided by the Storyplayer module, and
* __&lt;dot.notation.path&gt;__ is path to the setting that you want to retrieve

Here are some examples:

{% highlight php startinline %}
$moduleSettings  = fromStoryplayer()->get('moduleSettings');
$bridgedIface = fromStoryplayer()->get('moduleSettings.vagrant.bridgedIface');
$mode = fromStoryplayer()->getStorySetting('testTypes.smokeTests');
{% endhighlight %}