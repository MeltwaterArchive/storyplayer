---
layout: v2/modules-supervisor
title: usingSupervisor()
prev: '<a href="../../modules/supervisor/expectsSupervisor.html">Prev: expectsSupervisor()</a>'
next: '<a href="../../modules/uuid/index.html">Next: The UUID Module</a>'
---

# usingSupervisor()

_usingSupervisor()_ allows you to start and stop processes that are managed by Supervisor.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingSupervisor_.

## Behaviour And Return Codes

Every action starts and stops processes on the named host.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, the action throws an exception.  _Do not catch exceptions thrown by these actions_.  Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action must succeed.

## startProgram()

Use `$st->usingSupervisor()->startProgram()` to start a program that is managed by Supervisor.

{% highlight php %}
$st->usingSupervisor($hostname)->startProgram($programName);
{% endhighlight %}

where:

* `$hostname` is the name of the host you want to check
* `$programName` is the name of the program that Supervisor is managing

## stopProgram()

Use `$st->usingSupervisor()->stopProgram()` to stop a program that is managed by Supervisor.

{% highlight php %}
$st->usingSupervisor($hostname)->startProgram($programName);
{% endhighlight %}

where:

* `$hostname` is the name of the host you want to check
* `$programName` is the name of the program that Supervisor is managing