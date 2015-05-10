---
layout: v2/modules-testenvironment
title: fromTestEnvironment()
updated_for_v2: true
prev: '<a href="../../modules/testenvironment/index.html">Prev: The TestEnvironment Module</a>'
next: '<a href="../../modules/timer/index.html">Next: The Timer Module</a>'
---

# fromTestEnvironment()

_fromTestEnvironment()_ allows you to retrieve settings from your test environment config file.

The source code for these methods can be found in the class `Prose\FromTestEnvironment`.

## Behaviour And Return Codes

If the method cannot find the data you're looking for, an exception is throw. _Do not catch exceptions thrown by these methods._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every module method call will be successful.

## get()

Use `fromTestEnvironment()->get()` to retrieve any setting from your system under test config file using [dot.notation.support](../../using/configuration/dot.notation.support.html).

{% highlight php startinline %}
$moduleSettings = fromTestEnvironment()->get('moduleSettings');
$bridgedIface   = fromTestEnvironment()->get('moduleSettings.vagrant.bridgedIface');
{% endhighlight %}

## getAppSetting()

<div class="callout danger" markdown="1">
#### Deprecated Feature

This action was deprecated in Storyplayer v2.2.0. [More details are available here.](../../using/deprecated/appSettings.html)
</div>

Use _[fromHost()->getAppSetting()](../host/fromHost.html#getAppSetting)_ to retrieve an app setting from your test environment config.

## getAppSettings()

<div class="callout danger" markdown="1">
#### Deprecated Feature

This action was deprecated in Storyplayer v2.2.0. [More details are available here.](../../using/deprecated/appSettings.html)
</div>

Use _[fromHost()->getAppSettings()](../host/fromHost.html#getAppSettings)_ to retrieve the app settings from your test environment config.

## getConfig()

Use `fromTestEnvironment()->getConfig()` to retrieve all of the settings from the test environment config file.

{% highlight php startinline %}
$config = fromTestEnvironment()->getConfig();
{% endhighlight %}

## getModuleSetting()

<div class="callout warning" markdown="1">
#### A Better Alternative

If you're writing a Storyplayer module, use [fromConfig()->getModuleSetting()](../config/fromConfig.html#getmodulesetting) instead.
</div>

Use `fromTestEnvironment()->getModuleSetting()` to retrieve a single module setting from the test environment config file.

{% highlight php startinline %}
$bridgedIface = fromTestEnvironment()->getModuleSetting('vagrant.bridgedIface');
{% endhighlight %}

Notes:

* This method is meant to be used by Storyplayer modules. You should never need to call this from your stories.

## getModuleSettings()

<div class="callout warning" markdown="1">
#### A Better Alternative

If you're writing a Storyplayer module, use [fromConfig()->getModuleSetting()](../config/fromConfig.html#getmodulesetting) instead.
</div>

Use `fromTestEnvironment()->getModuleSettings()` to retrieve all the settings for a module from the test environment config file.

{% highlight php startinline %}
$awsSettings = fromTestEnvironment()->getModuleSettings('aws');
{% endhighlight %}

Notes:

* This method is meant to be used by Storyplayer modules. You should never need to call this from your stories.

## getName()

Use `fromTestEnvironment()->getName()` to get the name of the system under test. This is the filename of the config file, e.g. 'vagrant-vbox-centos6'.

{% highlight php startinline %}
$name = fromTestEnvironment()->getName();
{% endhighlight %}

## getStorySetting()

Use _[fromHost()->getStorySetting()](../host/fromHost.html#getStorySetting)_ to retrieve a config setting from the `storySettings` section of your test environment config.
