---
layout: v2/modules-host
title: The Host Module
prev: '<a href="../../modules/graphite/expectsGraphite.html">Prev: expectsGraphite()</a>'
next: '<a href="../../modules/host/supported-hosts.html">Next: Supported Hosts</a>'
updated_for_v2: true
---

# The Host Module

The __Host__ module allows you to inspect and run commands on the computers in your test environment.

The source code for this Prose module can be found in these PHP classes:

* `Prose\ExpectsHost`
* `Prose\FromHost`
* `Prose\UsingHost`

## Dependencies

This module has no dependencies of its own.

## Using The Host Module

The basic format of an action is:

{% highlight php startinline %}
MODULE($hostName)->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromHost()](fromHost.html)_ - get information about a host
* _[expectsHost()](expectsHost.html)_ - test the state of a host
* _[usingHost()](usingHost.html)_ - perform actions on the host

and __action__ is one of the methods available on the __module__ you choose.