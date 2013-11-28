---
layout: modules-checkpoint
title: fromCheckpoint()
prev: '<a href="../../modules/checkpoint/index.html">Prev: The Checkpoint Module</a>'
next: '<a href="../../modules/checkpoint/usingCheckpoint.html">Next: usingCheckpoint()</a>'
---

# fromCheckpoint()

_fromCheckpoint()_ allows you to retrieve data stored in the checkpoint, without having to call _$st()->getCheckpoint()_ yourself.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromCheckpoint_.

## Behaviour And Return Codes

Every action returns either a value on success, or _NULL_ on failure.  None of these actions throw exceptions on failure.

## get()

Use _$st->fromCheckpoint()->get()_ to retrieve data stored in the checkpoint.

{% highlight php %}
$balance = $st->fromCheckpoint()->get('balance');
{% endhighlight %}

This is the same as doing:

{% highlight php %}
// get the checkpoint
$checkpoint = $st->getCheckpoint();

// copy the balance from the checkpoint
$balance = $checkpoint->balance;
{% endhighlight %}

Which way you use is down to personal preference.