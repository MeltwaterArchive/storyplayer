---
layout: modules-hoststable
title: expectsHostsTable()
prev: '<a href="../../modules/host/fromHost.html">Prev: fromHost()</a>'
next: '<a href="../../modules/host/usingHost.html">Next: usingHost()</a>'
---

# expectsHostsTable()

_expectsHost()_ allows you to make sure that the hosts table contains the data that you expect.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\HostsTableExpects_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## hasEntryForHost()

Use _$st->expectsHostsTable()->hasEntryForHost()_ to ensure that a host has an entry in the hosts table.

{% highlight php %}
$st->expectsHostsTable()->hasEntryForHost($hostName);
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host

If the host has no entry, an exception is thrown.

