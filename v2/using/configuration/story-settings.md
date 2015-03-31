---
layout: v2/using-configuration
title: storySettings Section
prev: '<a href="../../using/configuration/storyplayer-json.html">Prev: The storyplayer.json File</a>'
next: '<a href="../../using/configuration/module-settings.html">Next: moduleSettings Section</a>'
updated_for_v2: true
---

# storySettings Section

<div class="callout info" markdown="1">
#### storySettings Are The New appSettings

In Storyplayer v1, and in Storyplayer v2.0 and v2.1, these settings were known as `appSettings`. For Storyplayer v2.2.0 and onwards, we have renamed them `storySettings`. They're exactly the same as before. We just think that `storySettings` is a much better name for them.

If you're already using `appSettings` in your tests, don't worry. We'll continue to support the `appSettings` section until we release Storyplayer v3.0. We just won't mention `appSettings` in the documentation anywhere.
</div>

## Why Use storySettings?

There's absolutely nothing stopping you hard-coding into your tests all of the settings about your system-under-test.  Your tests will execute fine.

But what happens when a new version of the system-under-test changes these settings?  You might end up having to edit multiple tests, just to make sure all of your tests are using the new settings. And what happens when the settings are different from test environment to test environment?

That's where the `storySettings` section of config files comes in. Put these settings into your config files. Have your stories retrieve the settings when they run. You can write your story once, and re-use it against different versions of your system under test, running in different test environments.

## Where To Put storySettings

The `storySettings` section can put put in any of these config files:

* in your system-under-test config file
* in your test environment config file
* in your storyplayer.json config file

Which one to choose?

* If you have a setting that is specific to the test environment (such as a network port), put the setting into your test environment config file.
* Put all the other settings into your system-under-test config file.

Use the `storySettings` section of your `storyplayer.json` file only for settings that you use to change the behaviour of your tests (e.g. [smoke testing](../../tips/test-types/smoke-testing.html)).

## Accessing The storySettings From Your System Under Test

You can access your storySettings using _[fromSystemUnderTest()->getStorySetting()](../../modules/systemundertest/fromSystemUnderTest.html#getstorysetting)_ in your tests:

{% highlight php startinline %}
$settings = fromSystemUnderTest()->getStorySetting('pages');
{% endhighlight %}

You can also use [dot.notation.support](dot.notation.support.html) to access individual settings:

{% highlight php startinline %}
$loginPagePath = fromSystemUnderTest()->getStorySetting('pages.login');
{% endhighlight %}

## Accessing The storySettings From Your Test Environment

Use _[fromHost()->getStorySetting()](../../modules/host/fromHost.html#getstorysetting)_ to access any story settings in your test environment:

{% highlight php startinline %}
foreach(firstHostWithRole('pdf_queue') as $hostId) {
    // get the ZMQ ports on this host
    $ports = fromHost($hostId)->getStorySetting('pdf_queue.zmq');
}
{% endhighlight %}

## An Example Config

Look at these files on GitHub to see how Storyplayer uses `storySettings` in the config files we use to test Storyplayer:

* [systems-under-test config file](https://github.com/datasift/storyplayer/blob/develop/.storyplayer/systems-under-test/storyplayer-2.x.json)
* [test environment config file](https://github.com/datasift/storyplayer/blob/develop/.storyplayer/test-environments/vagrant-centos6-ssl.json)