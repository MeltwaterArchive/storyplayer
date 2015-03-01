---
layout: v2/modules-savaged
title: The SavageD Module
prev: '<a href="../../modules/runtimetable/index.html">Prev: The RuntimeTable Module</a>'
next: '<a href="../../modules/savaged/usingSavageD.html">Next: usingSavageD()</a>'
---

# The SavageD Module

The __SavageD__ module allows you to control one or more (possibly) remote SavageD daemons.

The source code for this Prose module can be found in this PHP class:

* `Prose\UsingSavageD`

## What Is SavageD?

[SavageD](https://github.com/datasift/SavageD) is a process and server monitoring process developed by DataSift.  It logs results in real-time via [Etsy's statsd](https://github.com/etsy/statsd) into [Graphite](https://github.com/graphite-project).

It is controlled via a REST-like API, which makes it perfect for use with Storyplayer:

1. Storyplayer starts the process that you want to test
1. Storyplayer then tells SavageD the process(es) to monitor
1. When the test is finished, Storyplayer then tells SavageD what to stop monitoring

This is much easier than trying to manipulate config files, and because you don't need to restart SavageD to change what it is monitoring, it's great for monitoring different phases of a complex story.

SavageD is written using NodeJS, and you can write your own NPM plugins to extend what it can monitor.

## Dependencies

This module relies on [SavageD](https://github.com/datasift/SavageD), which you will need to install for yourself.  It needs to be deployed onto the same (possibly remote) server, or inside the same virtual machine, where the software you're testing has been deployed.  (SavageD needs to be on the same box because it gets its data from the machine's /proc filesystem).

For example, we normally deploy it and the backend service we're testing, into a VM deployed and managed by [the Vagrant module](../modules/vagrant.html).

To use SavageD, you'll also need Etsy's statsd and Graphite installed and working.  Refer to their documentation for assistance there.

## Configuration

You must add SavageD's HTTP port number to your [configuration](../../stories/configuration.html):

{% highlight php %}
{
	"environments": {
		"defaults": {
			...
			"savaged": {
				"httpPort": 3091
			}
		}
	}
}
{% endhighlight %}

If this setting is missing from your environments section, you'll see an exception thrown at runtime when you try to use the SavageD module.

## Using The SavageD Module

The basic format of an action is:

{% highlight php %}
usingSavageD($ipAddress)->ACTION();
{% endhighlight %}

where:

* `$ipAddress` is the IP address (not hostname!!) of the machine where SavageD is installed and running,
* __action__ is one of the documented actions available from _[usingSavageD](usingSavageD.html)_

`$ipAddress` might be a setting retrieved from your environments config file, or it might be the IP address of a virtual machine started by [the Vagrant module](../modules/vagrant.html).

To get and/or test the data created by SavageD, use the [Graphite module](../graphite/index.html).