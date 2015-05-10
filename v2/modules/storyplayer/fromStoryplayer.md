---
layout: v2/modules-storyplayer
title: fromStoryplayer()
prev: '<a href="../../modules/storyplayer/index.html">Prev: The Storyplayer Module</a>'
next: '<a href="../../modules/supervisor/index.html">Next: The Supervisor Module</a>'
updated_for_v2: true
---

# fromStoryplayer()

_fromStoryplayer()_ allows you to retrieve settings from your `storyplayer.json` config file.

The source code for these methods can be found in the class `Prose\FromStoryplayer`.

## Behaviour And Return Codes

If the method cannot find the data you're looking for, an exception is throw. _Do not catch exceptions thrown by these methods._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every module method call will be successful.

## get()

Use `fromStoryplayer()->get()` to retrieve any setting from your `storyplayer.json` config file using [dot.notation.support](../../using/configuration/dot.notation.support.html).

{% highlight php startinline %}
$moduleSettings = fromStoryplayer()->get('moduleSettings');
$bridgedIface   = fromStoryplayer()->get('moduleSettings.vagrant.bridgedIface');
{% endhighlight %}

## getAppSetting()

<div class="callout danger" markdown="1">
#### Deprecated Feature

This action was deprecated in Storyplayer v2.2.0. [More details are available here.](../../using/deprecated/appSettings.html)
</div>

Use `fromStoryplayer()->getAppSetting()` to retrieve a single setting from the config file.

{% highlight php startinline %}
$mode = fromStoryplayer()->getAppSetting('testTypes.smokeTests');
{% endhighlight %}

## getAppSettings()

<div class="callout danger" markdown="1">
#### Deprecated Feature

This action was deprecated in Storyplayer v2.2.0. [More details are available here.](../../using/deprecated/appSettings.html)
</div>

Use `fromStoryplayer()->getAppSettings()` to retrieve all the settings for an app from the config file.

{% highlight php startinline %}
$testTypes = fromStoryplayer()->getAppSettings('testTypes');
{% endhighlight %}

## getConfig()

Use `fromStoryplayer()->getConfig()` to retrieve all of the settings from the `storyplayer.json` config file.

{% highlight php startinline %}
$config = fromStoryplayer()->getConfig();
{% endhighlight %}

## getModuleSetting()

<div class="callout warning" markdown="1">
#### A Better Alternative

If you're writing a Storyplayer module, use [fromConfig()->getModuleSetting()](../config/fromConfig.html#getmodulesetting) instead.
</div>

Use `fromStoryplayer()->getModuleSetting()` to retrieve a single module setting from the `storyplayer.json` config file.

{% highlight php startinline %}
$bridgedIface = fromStoryplayer()->getModuleSetting('vagrant.bridgedIface');
{% endhighlight %}

Notes:

* This method is meant to be used by Storyplayer modules. You should never need to call this from your stories.

## getModuleSettings()

<div class="callout warning" markdown="1">
#### A Better Alternative

If you're writing a Storyplayer module, use [fromConfig()->getModuleSetting()](../config/fromConfig.html#getmodulesetting) instead.
</div>

Use `fromStoryplayer()->getModuleSettings()` to retrieve all the settings for a module from the `storyplayer.json` config file.

{% highlight php startinline %}
$awsSettings = fromStoryplayer()->getModuleSettings('aws');
{% endhighlight %}

Notes:

* This method is meant to be used by Storyplayer modules. You should never need to call this from your stories.

## getStorySetting()

Use `fromStoryplayer()->getStorySetting()` to retrieve a setting from the `storySettings` section of your `storyplayer.json` config file.

{% highlight php startinline %}
$mode = fromStoryplayer()->getStorySetting('testTypes.smokeTests');
{% endhighlight %}