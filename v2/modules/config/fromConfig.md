---
layout: v2/modules-config
title: fromConfig()
prev: '<a href="../../modules/config/index.html">Prev: The Config Module</a>'
next: '<a href="../../modules/devicemanager/index.html">Next: The DeviceManager Module</a>'
updated_for_v2: true
---

# fromConfig()

_fromConfig()_ allows you to retrieve settings from Storyplayer's internal config (known as the _Active Config_ in the source code).

The source code for these methods can be found in the class `Prose\FromConfig`.

## Behaviour And Return Codes

If the method cannot find the data you're looking for, an exception is throw. _Do not catch exceptions thrown by these methods._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every module method call will be successful.

## get()

Use `fromConfig()->get()` to retrieve any setting from Storyplayer's internal configuration using [dot.notation.support](../../using/configuration/dot.notation.support.html).

{% highlight php startinline %}
$hosts = fromConfig()->get('hosts');
{% endhighlight %}

## getAll()

Use `fromConfig()->getAll()` to retrieve the entire internal configuration of Storyplayer.

{% highlight php startinline %}
$config = fromConfig()->getAll();
{% endhighlight %}

Use this method if you ever want to `var_dump()` Storyplayer's internal configuration. This can be useful when debugging modules.

## getModuleSetting()

Use `fromConfig()->getModuleSetting()` to retrieve a module setting from Storyplayer's config files.

{% highlight php startinline %}
$setting = fromConfig()->getModuleSetting($settingPath);
{% endhighlight %}

where:

* `$settingPath` is the [dot.notation.path](../../using/configuration/dot.notation.support.html) to the moduleSetting you want
* `$setting` is set to the moduleSetting that you've retrieved

See [moduleSettings reference](../../using/configuration/module-settings.html) for details about the search order.

## hasModuleSetting()

Use `fromConfig()->hasModuleSetting()` to determine if a module setting exists in Storyplayer's config files.

{% highlight php startinline %}
$hasSetting = fromConfig()->hasModuleSetting($settingPath);
{% endhighlight %}

where:

* `$settingPath` is the [dot.notation.path](../../using/configuration/dot.notation.support.html) to the moduleSetting you want
* `$hasSetting` is set to `TRUE` if the setting exists, `FALSE` otherwise

See [moduleSettings reference](../../using/configuration/module-settings.html) for details about the search order.