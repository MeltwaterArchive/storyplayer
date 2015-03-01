---
layout: v2/modules-hoststable
title: fromHostsTable()
prev: '<a href="../../modules/hoststable/how-hosts-are-remembered.html">Prev: How Hosts Are Remembered</a>'
next: '<a href="../../modules/hoststable/expectsHostsTable.html">Next: expectsHostsTable()</a>'
---

# fromHostsTable()

_fromHostsTable()_ allows you to get the hosts table entry for a named host.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromHostsTable_.

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  These actions do throw an exception if you attempt to work with an unknown host.

## getDetailsForHost()

Use `fromHostsTable()->getDetailsForHost()` to retrieve the host's entry in Storyplayer's [hosts table](how-hosts-are-remembered.html).

{% highlight php %}
$details = fromHostsTable()->getDetailsForHost($hostName);
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host
* `$details` is a PHP object containing the host's entry in the hosts table

__NOTE__

* `$details` isn't a clone of the hosts table entry; any changes you make to these details will be persistent

## getHostsTable()

Use `fromHostsTable()->getHostsTable()` to retrieve Storyplayer's [hosts table](how-hosts-are-remembered.html).

{% highlight php %}
$table = fromHostsTable()->getHostsTable();
{% endhighlight %}

where:

* `$table` is a PHP object containing one attribute for each known host

__NOTE__

* `$table` isn't a clone of the hosts table; any changes you make to this tbale will be persistent