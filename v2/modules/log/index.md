---
layout: v2/modules-log
title: The Log Module
prev: '<a href="../../modules/iterators/lastHostWithRole.html">Prev: foreach(lastHostWithRole())</a>'
next: '<a href="../../modules/log/usingLog.html">Next: usingLog()</a>'
updated_for_v2: true
---
# The Log Module

The __Log__ module allows your story to write a message into Storyplayer's output log.

The source code for this module can be found in this PHP class:

* `Prose\UsingLog`

## Using The Log Module

The basic format of an action is:

{% highlight php startinline %}
usingLog()->ACTION();
{% endhighlight %}

where __action__ is one of the documented methods available.