---
layout: v2/configuration
title:  Test Phases Configuration
prev: '<a href="../configuration/logging.html">Prev: Logging</a>'
next: '<a href="../configuration/environment-config.html">Next: Per-Environment Configuration</a>'
---

# Test Phases Configuration

By default, Storyplayer will execute every [test execution phase](../stories/phases.html) in order.  However, when you are creating a complex test (especially one involving virtual machines), you can save a lot of time by temporarily disabling some of the test phases.

## Supported Test Phases

Storyplayer currently supports the following test phases:

* __TestEnvironmentSetup__
* __TestSetup__
* __PreTestPrediction__
* __PreTestInspection__
* __Action__
* __PostTestInspection__
* __TestTeardown__
* __TestEnvironmentTeardown__

You can disable any phase individually, as _phases->\*_ in your config file.

## An Example Config

Here's what Storyplayer's default settings for the test phases would look like, if they were listed in your [storyplayer.json](storyplayer-json.html) file:

{% highlight json %}
{
    "phases": {
        "TestEnvironmentSetup": true,
        "TestSetup": true,
        "PreTestPrediction": true,
        "PreTestInspection": true,
        "Action": true,
        "PostTestInspection": true,
        "TestTeardown": true,
        "TestEnvironmentTeardown": true
    }
}
{% endhighlight %}
