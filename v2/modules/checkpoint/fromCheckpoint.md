---
layout: v2/modules-checkpoint
title: fromCheckpoint()
prev: '<a href="../../modules/checkpoint/getCheckpoint.html">Prev: getCheckpoint()</a>'
next: '<a href="../../modules/checkpoint/usingCheckpoint.html">Next: usingCheckpoint()</a>'
updated_for_v2: true
---

# fromCheckpoint()

_fromCheckpoint()_ allows you to retrieve data stored in the checkpoint, without having to call `getCheckpoint()` yourself.

The source code for these actions can be found in the class `Prose\FromCheckpoint`.

<div class="callout warning" markdown="1">
#### Deprecated in Storyplayer v2

The functionality documented here is deprecated, and will be removed in Storyplayer v3.

Use [getCheckpoint()](getCheckpoint.html) instead.
</div>

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  None of these actions throw exceptions on failure.

## get()

Use `fromCheckpoint()->get()` to retrieve data stored in the checkpoint.

{% highlight php startinline %}
$balance = fromCheckpoint()->get('balance');
{% endhighlight %}

This is the same as doing:

{% highlight php startinline %}
// get the checkpoint
$checkpoint = getCheckpoint();

// copy the balance from the checkpoint
$balance = $checkpoint->balance;
{% endhighlight %}

Which way you use is down to personal preference.