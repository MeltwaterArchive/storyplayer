---
layout: v2/modules-hoststable
title: expectsHostsTable()
prev: '<a href="../../modules/hoststable/fromHostsTable.html">Prev: fromHostsTable()</a>'
next: '<a href="../../modules/hoststable/usingHostsTable.html">Next: usingHostsTable()</a>'
---

# expectsHostsTable()

_expectsHost()_ allows you to make sure that the hosts table contains the data that you expect.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\ExpectsHostsTable_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## hasEntryForHost()

Use `expectsHostsTable()->hasEntryForHost()` to ensure that a host has an entry in the hosts table.

{% highlight php %}
expectsHostsTable()->hasEntryForHost($hostName);
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host

If the host has no entry, an exception is thrown.

