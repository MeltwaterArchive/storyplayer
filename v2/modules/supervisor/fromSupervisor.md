---
layout: v2/modules-supervisor
title: fromSupervisor()
prev: '<a href="../../modules/supervisor/index.html">Prev: The Supervisor Module</a>'
next: '<a href="../../modules/supervisor/expectsSupervisor.html">Next: expectsSupervisor()</a>'
updated_for_v2: true
---

# fromSupervisor()

_fromSupervisor()_ allows you to extract information about the prgrams that are managed by Supervisor.

The source code for these actions can be found in the class `Prose\FromSupervisor`.

## Behaviour And Return Codes

Every action either returns a value on success, or `NULL` on failure. None of these actions throw exceptions on failure.

## getProgramIsRunning()

Use `fromSupervisor()->getProgramIsRunning()` to see if a program managed by Supervisor is running.

{% highlight php startinline %}
$isRunning = fromSupervisor($hostId)->getProgramIsRunning($programName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where Supervisor is running
* `$programName` is the name of the program that Supervisor is managing
* `$isRunning` gets set to _TRUE_ if the program is running, _FALSE_ otherwise