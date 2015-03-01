---
layout: v2/modules-log
title: usingLog()
prev: '<a href="../../modules/log/index.html">Prev: The Log Module</a>'
next: '<a href="../../modules/processestable/index.html">Next: The Processes Table Module</a>'
---

# usingLog()

_usingLog()_ allows you to write a message into Storyplayer's output log.

The source code for these actions can be found in this class:

* `Prose\UsingLog`.

## Behaviour And Return Codes

If the action succeeds, the action returns control to your code, and does not return a value.

If the action fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## writeToLog()

Use `usingLog()->writeToLog()` to write a message to the log file from your story.

{% highlight php startinline %}
usingLog()->writeToLog($msg);
{% endhighlight %}

where:

* `$msg` is the message you want to write to Storyplayer's output log.

__NOTES:__

* Only use this module from inside your story's phases.
* Prose modules should create a `$log` object via `startAction()`.