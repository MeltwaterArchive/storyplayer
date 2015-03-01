---
layout: v2/modules-host
title: The Host Module
prev: '<a href="../../modules/graphite/expectsGraphite.html">Prev: expectsGraphite()</a>'
next: '<a href="../../modules/host/supported-hosts.html">Next: Supported Hosts</a>'
---

# The Host Module

The __Host__ module allows you to inspect and run commands on other computers, such as [Vagrant](../vagrant/index.html) virtual machines.  We call these computers _hosts_.

The source code for this Prose module can be found in these PHP classes:

* `Prose\ExpectsHost`
* `Prose\FromHost`
* `Prose\UsingHost`

## Dependencies

This module has no dependencies of its own, but you will probably want to use it with the [Vagrant](../vagrant/index.html) module.

## Using The Host Module

The basic format of an action is:

{% highlight php %}
$st->MODULE($hostName)->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromHost()](fromHost.html)_ - get information about a host
* _[expectsHost()](expectsHost.html)_ - test the state of a host
* _[usingHost()](usingHost.html)_ - perform actions on the host

and __action__ is one of the methods available on the __module__ you choose.

Before you can use the _Host_ module, you first need to create a host to work with.  (The _Host_ module is designed to work with hosts that have already been created).  You do this using any of the following modules:

* _[usingHostsTable()->addHost()](../hoststable/usingHostsTable.html#addhost)_
* _[usingVagrant()->createVm()](../vagrant/usingVagrant.html#createvm)_