---
layout: modules-log
title: The Log Module
prev: '<a href="../../modules/http/usingHttp.html">Prev: usingHttp()</a>'
next: '<a href="../../modules/log/usingLog.html">Next: usingLog()</a>'
---

# The Log Module

The __Log__ module allows your story to write a message into Storyplayer's output log.

The source code for this Prose module can be found in this PHP class:

* DataSift\Storyplayer\Prose\LogActions

## Using The Log Module

The basic format of an action is:

{% highlight php %}
$st->usingLog()->ACTION();
{% endhighlight %}

where __action__ is one of the documented methods available.