---
layout: modules-hoststable
title: fromHostsTable()
prev: '<a href="../../modules/hoststable/how-hosts-are-remembered.html">Prev: How Hosts Are Remembered</a>'
next: '<a href="../../modules/hoststable/expectsHostsTable.html">Next: expectsHostsTable()</a>'
---

# fromHostsTable()

_fromHostsTable()_ allows you to get the hosts table entry for a named host.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\HostsTableDetermine_.

## Behaviour And Return Codes

Every action returns either a value on success, or _NULL_ on failure.  These actions do throw an exception if you attempt to work with an unknown host.

## getDetailsForHost()

Use _$st->fromHostsTable()->getDetailsForHost()_ to retrieve the host's entry in Storyplayer's [hosts table](how-hosts-are-remembered.html).

{% highlight php %}
$details = $st->fromHostsTable()->getDetailsForHost($hostName);
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$details_ is a PHP object containing the host's entry in the hosts table

__NOTE__

* _$details_ isn't a clone of the hosts table entry; any changes you make to these details will be persistent

## getHostsTable()

Use _$st->fromHostsTable()->getHostsTable()_ to retrieve Storyplayer's [hosts table](how-hosts-are-remembered.html).

{% highlight php %}
$table = $st->fromHostsTable()->getHostsTable();
{% endhighlight %}

where:

* _$table_ is a PHP object containing one attribute for each known host

__NOTE__

* _$table_ isn't a clone of the hosts table; any changes you make to this tbale will be persistent