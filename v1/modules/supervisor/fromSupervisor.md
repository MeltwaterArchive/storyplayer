---
layout: v1/modules-supervisor
title: fromSupervisor()
prev: '<a href="../../modules/supervisor/index.html">Prev: The Supervisor Module</a>'
next: '<a href="../../modules/supervisor/expectsSupervisor.html">Next: expectsSupervisor()</a>'
---

# fromSupervisor()

_fromSupervisor()_ allows you to extract information about the prgrams that are managed by Supervisor.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromSupervisor_.

## Behaviour And Return Codes

Every action either returns a value on success, or `NULL` on failure. None of these actions throw exceptions on failure.

## getProgramIsRunning()

Use `$st->fromSupervisor()->getProgramIsRunning()` to see if a program managed by Supervisor is running.

{% highlight php %}
$isRunning = $st->fromSupervisor()->getProgramIsRunning($programName);
{% endhighlight %}

where:

* `$hostname` is the name of the host you want to check
* `$programName` is the name of the program that Supervisor is managing
* `$isRunning` is _TRUE_ if the program is running, _FALSE_ otherwise

## getProgramIsNotRunning()

Use `$st->fromSupervisor()->getProgramIsNotRunning()` to see if a program managed by Supervisor is not running.

{% highlight php %}
$isRunning = $st->fromSupervisor()->getProgramIsNotRunning($programName);
{% endhighlight %}

where:

* `$hostname` is the name of the host you want to check
* `$programName` is the name of the program that Supervisor is managing
* `$isRunning` is _TRUE_ if the program is not running, _FALSE_ otherwise

