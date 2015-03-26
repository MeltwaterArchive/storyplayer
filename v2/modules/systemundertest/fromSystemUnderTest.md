---
layout: v2/modules-systemundertest
title: fromSystemUnderTest()
updated_for_v2: true
prev: '<a href="../../modules/systemundertest/index.html">Prev: The SystemUnderTest Module</a>'
next: '<a href="../../modules/testenvironment/index.html">Next: The TestEnvironment Module</a>'
---

# fromSystemUnderTest()

_fromSystemUnderTest()_ allows you to retrieve settings from your system-under-test config file.

The source code for these methods can be found in the class `Prose\FromSystemUnderTest`.

## Behaviour And Return Codes

If the method cannot find the data you're looking for, an exception is throw. _Do not catch exceptions thrown by these methods._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every module method call will be successful.

## get()

Use `fromSystemUnderTest()->get()` to retrieve any setting from your system under test config file using [dot.notation.support](../../using/configuration/dot.notation.support.html).

{% highlight php startinline %}
$moduleSettings = fromSystemUnderTest()->get('moduleSettings');
$bridgedIface   = fromSystemUnderTest()->get('moduleSettings.vagrant.bridgedIface');
{% endhighlight %}

## getAppSetting()

<div class="callout danger" markdown="1">
#### Deprecated Feature

This action was deprecated in Storyplayer v2.2.0. [More details are available here.](../../using/deprecated/appSettings.html)
</div>

Use `fromSystemUnderTest()->getAppSetting()` to retrieve a single setting from the config file.

{% highlight php startinline %}
$login_path = fromSystemUnderTest()->getAppSetting('pages.login');
{% endhighlight %}

## getAppSettings()

<div class="callout danger" markdown="1">
#### Deprecated Feature

This action was deprecated in Storyplayer v2.2.0. [More details are available here.](../../using/deprecated/appSettings.html)
</div>

Use `fromSystemUnderTest()->getAppSettings()` to retrieve all the settings for an app from the system under test config file.

{% highlight php startinline %}
$paths = fromSystemUnderTest()->getAppSettings('pages');
{% endhighlight %}

## getConfig()

Use `fromSystemUnderTest()->getConfig()` to retrieve all of the settings from the system under test config file.

{% highlight php startinline %}
$config = fromSystemUnderTest()->getConfig();
{% endhighlight %}

## getModuleSetting()

Use `fromSystemUnderTest()->getModuleSetting()` to retrieve a single module setting from the system under test config file.

{% highlight php startinline %}
$bridgedIface = fromSystemUnderTest()->getModuleSetting('vagrant.bridgedIface');
{% endhighlight %}

Notes:

* This method is meant to be used by Storyplayer modules. You should never need to call this from your stories.

## getModuleSettings()

Use `fromSystemUnderTest()->getModuleSettings()` to retrieve all the settings for a module from the system under test config file.

{% highlight php startinline %}
$awsSettings = fromSystemUnderTest()->getModuleSettings('aws');
{% endhighlight %}

Notes:

* This method is meant to be used by Storyplayer modules. You should never need to call this from your stories.

## getName()

Use `fromSystemUnderTest()->getName()` to get the name of the system under test. This is the filename of the config file, e.g. 'my-app-2.3.4'.

{% highlight php startinline %}
$name = fromSystemUnderTest()->getName();
{% endhighlight %}

## getStorySetting()

Use `fromSystemUnderTest()->getStorySetting()` to retrieve a setting from the `storySettings` section in your system under test config file.

{% highlight php startinline %}
$paths = fromSystemUnderTest()->getStorySetting('pages');
{% endhighlight %}