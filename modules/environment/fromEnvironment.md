---
layout: modules-environment
title: fromEnvironment()
prev: '<a href="../../modules/environment/index.html">Prev: The Environment Module</a>'
next: '<a href="../../modules/file/index.html">Next: The File Module</a>'
---

# fromEnvironment()

_fromEnvironment()_ allows you to retrieve data from the environment that the current story is being run against.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromEnvironment_.

## Behaviour And Return Codes

Unlike most _fromXXX()_ modules, every action returns a value on success, or throws an exception on failure.  _Do not catch exceptions thrown by these actions_. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action will succeed.

## getAppSetting()

Use `$st->fromEnvironment()->getAppSetting()` to get a single piece of information from the environment.

{% highlight php %}
$value = $st->fromEnvironment()->getAppSetting($app, $setting);
{% endhighlight %}

This is the equivalent of:

{% highlight php %}
$env = $st->getEnvironment();
$value = $env->$app->$setting
{% endhighlight %}

... with the added advantage of throwing an exception if the environment doesn't define the information that you're looking for.

Most tests tend to need to use all the available settings for _$app_ in the environment; you might find [getAppSettings()](#getappsettings) to be more convenient.

## getAppSettings()

Use `$st->fromEnvironment()->getAppSettings()` to get all the information about an 'app' from the environment.

{% highlight php %}
$settings = $st->fromEnvironment()->getAppSettings($app);
$value = $settings->$setting;
{% endhighlight %}

This is the equivalent of:

{% highlight php %}
$env = $st->getEnvironment();
$settings = $env->$app;
{% endhighlight %}

.. with the added advangage of throwing an exception if the environment has no entry for _$app_.