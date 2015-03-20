---
layout: v2/modules-testenvironment
title: The TestEnvironment Module
prev: '<a href="../../modules/systemundertest/fromSystemUnderTest.html">Prev: fromSystemUnderTest()</a>'
next: '<a href="../../modules/testenvironment/fromTestEnvironment.html">Next: fromTestEnvironment()</a>'
updated_for_v2: true
---

# The TestEnvironment Module

The __TestEnvironment__ module allows you to get the settings from your [test environment config file](../../using/configuration/test-environment-config.html).

The source code for this module can be found in this PHP class:

* `Prose\FromTestEnvironment`

## Dependencies

This module has no dependencies.

## Using The TestEnvironment Module

The basic format of an action is:

{% highlight php startinline %}
$configSetting = fromTestEnvironment()->METHOD("<dot.notation.path>");
{% endhighlight %}

where:

* __METHOD__ is one of the methods provided by the Storyplayer module, and
* __&lt;dot.notation.path&gt;__ is path to the setting that you want to retrieve

Here are some examples:

{% highlight php startinline %}
$settings = fromTestEnvironment()->getModuleSetting('http');
{% endhighlight %}