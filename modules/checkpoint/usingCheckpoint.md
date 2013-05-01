---
layout: modules-checkpoint
title: usingCheckpoint()
prev: '<a href="../../modules/checkpoint/fromCheckpoint.html">Prev: fromCheckpoint()</a>'
next: '<a href="../../modules/environment/index.html">Next: The Environment Module</a>'
---

# usingCheckpoint()

_usingCheckpoint()_ allows you to put data into the checkpoint without having to call _$st()->getCheckpoint()_ yourself.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\CheckpointActions_.

## Behaviour And Return Codes

Every action makes changes to the inter-phase checkpoint.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, an exception is throw. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action will be successful.

## set()

Use _$st->usingCheckpoint()->set()_ to store data in the checkpoint.

{% highlight php %}
$st->usingCheckpoint()->set('balance', 10);
{% endhighlight %}

This is the same as doing:

{% highlight php %}
$checkpoint = $st->getCheckpoint();
$checkpoint->balance = 10;
{% endhighlight %}

Which way you use is down to personal preference.