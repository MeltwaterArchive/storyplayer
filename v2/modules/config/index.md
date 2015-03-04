---
layout: v2/modules-config
title: The Config Module
prev: '<a href="../../modules/checkpoint/usingCheckpoint.html">Prev: usingCheckpoint()</a>'
next: '<a href="../../modules/curl/index.html">Next: The cURL Module</a>'
---

# The Config Module

## Introduction

The __Config__ module allows you to retrieve settings from Storyplayer's internal config (known as the _Active Config_ in the source code).

The source code for this module can be found in:

* `Prose\FromConfig`

<div class="callout warning" markdown="1">
#### Internal Module

This module is used internally by Storyplayer. Do not call this module from your stories.
</div>

## Dependencies

This module has no dependencies.

## Using The Config Module

The basic format of an action is:

{% highlight php startinline %}
$configSetting = fromConfig()->METHOD("<dot.notation.path>");
{% endhighlight %}

where:

* __METHOD__ is one of the methods provided by the Storyplayer module, and
* __&lt;dot.notation.path&gt;__ is path to the setting that you want to retrieve

Here are some examples:

{% highlight php startinline %}
$hosts  = fromConfig()->get('hosts');
$config = fromConfig()->getAll();
{% endhighlight %}
