---
layout: v2/modules-systemundertest
title: The SystemUnderTest Module
prev: '<a href="../../modules/supervisor/usingSupervisor.html">Prev: usingSupervisor()</a>'
next: '<a href="../../modules/systemundertest/fromSystemUnderTest.html">Next: fromSystemUnderTest()</a>'
updated_for_v2: true
---

# The SystemUnderTest Module

The __SystemUnderTest__ module allows you to get the settings from your [system under test config file](../../using/configuration/system-under-test-config.html).

The source code for this module can be found in this PHP class:

* `Prose\FromSystemUnderTest`

## Dependencies

This module has no dependencies.

## Using The SystemUnderTest Module

The basic format of an action is:

{% highlight php startinline %}
$configSetting = fromSystemUnderTest()->METHOD("<dot.notation.path>");
{% endhighlight %}

where:

* __METHOD__ is one of the methods provided by the Storyplayer module, and
* __&lt;dot.notation.path&gt;__ is path to the setting that you want to retrieve

Here are some examples:

{% highlight php startinline %}
$paths = fromSystemUnderTest()->getStorySetting('pages');
{% endhighlight %}