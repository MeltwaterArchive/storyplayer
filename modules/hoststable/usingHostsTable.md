---
layout: modules-hoststable
title: usingHostsTable()
prev: '<a href="../../modules/hoststable/expectsHostsTable.html">Prev: expectsHostsTable()</a>'
next: '<a href="../../modules/http/index.html">Next: The HTTP Module</a>'
---

# usingHostsTable()

_usingHostsTable()_ allows you to inject and remove entries from Storyplayer's [hosts table](how-hosts-are-remembered.html).

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingHostsTable_.

## Behaviour And Return Codes

If the action succeeds, control is returned to your code, and no value is returned.

If the action fails, an exception is thrown. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## addHost()

Use `$st->usingHostsTable()->addHost()` to inject a new host into the hosts table.

{% highlight php %}
$st->usingHostsTable()->addHost($hostName, $hostDetails);
{% endhighlight %}

where:

* _$hostName_ is the name you want to use when working with this host
* _$hostDetails_ is the entry to inject into the hosts table

This has been added

## removeHost()

Use `$st->usingHostsTable()->removeHost()` to delete a host from the hosts table.

{% highlight php %}
$st->usingHostsTable()->removeHost($hostName, $hostDetails);
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host

__NOTE:__

* Calling _removeHost()_ does nothing to the host itself.  If the host is a virtual machine, it will not be shutdown, and its image on disk will not be deleted.
