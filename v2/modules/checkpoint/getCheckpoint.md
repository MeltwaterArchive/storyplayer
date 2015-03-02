---
layout: v2/modules-checkpoint
title: getCheckpoint()
prev: '<a href="../../modules/checkpoint/index.html">Prev: The Checkpoint Module</a>'
next: '<a href="../../modules/checkpoint/fromCheckpoint.html">Next: fromCheckpoint()</a>'
---

# getCheckpoint()

_getCheckpoint()_ returns the [Checkpoint object](../../using/stories/the-checkpoint.html).

## getCheckpoint()

Use `getCheckpoint()` to obtain the checkpoint object.

{% highlight php startinline %}
$checkpoint = getCheckpoint();
{% endhighlight %}

You can store data inside the checkpoint object simply by setting any attribute you want:

{% highlight php startinline %}
$checkpoint = getCheckpoint();
$checkpoint->balance = 99.99;
{% endhighlight %}

You can retrieve data from the checkpoint object simply by reading any attribute you have previously set:

{% highlight php startinline %}
$checkpoint = getCheckpoint();
assertsObject($checkpoint)->hasAttribute('balance');
assertsDouble($checkpoint->balance)->equals(99.99);
{% endhighlight %}

The checkpoint is always reset to be an empty object at the start of every story.