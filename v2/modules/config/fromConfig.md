---
layout: v2/modules-config
title: fromConfig()
prev: '<a href="../../modules/config/index.html">Prev: The Config Module</a>'
next: '<a href="../../modules/curl/index.html">Next: The cURL Module</a>'
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