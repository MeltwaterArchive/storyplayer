---
layout: v2/modules-supervisor
title: expectsSupervisor()
prev: '<a href="../../modules/supervisor/fromSupervisor.html">Prev: fromSupervisor()</a>'
next: '<a href="../../modules/supervisor/usingSupervisor.html">Next: usingSupervisor()</a>'
---

# expectsSupervisor()

_expectsSupervisor()_ allows you to test the state of a process that is being managed by [Supervisor](http://supervisord.org).

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\ExpectsSupervisor_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception.  _Do not catch exceptions thrown by these actions_.  Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## programIsRunning()

Use `$st->expectsSupervisor()->programIsRunning()` to make sure that a program managed by Supervisor is running.

{% highlight php %}
$st->expectsSupervisor($hostname)->programIsRunning($programName);
{% endhighlight %}

where:

* `$hostname` is the name of the host you want to check
* `$programName` is the name of the program that Supervisor is managing

## programIsNotRunning()

Use `$st->expectsSupervisor()->programIsNotRunning()` to make sure that a program managed by Supervisor is not running.

{% highlight php %}
$st->expectsSupervisor($hostname)->programIsNotRunning($programName);
{% endhighlight %}

where:

* `$hostname` is the name of the host you want to check
* `$programName` is the name of the program that Supervisor is managing
