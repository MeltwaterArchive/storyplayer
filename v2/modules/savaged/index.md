---
layout: v2/modules-savaged
title: The SavageD Module
prev: '<a href="../../modules/provisioning/adding-more-engines.html">Prev: Adding Additional Provisioning Engines</a>'
next: '<a href="../../modules/savaged/usingSavageD.html">Next: usingSavageD()</a>'
updated_for_v2: true
---

# The SavageD Module

The __SavageD__ module allows you to control one or more (possibly) remote SavageD daemons.

The source code for this module can be found in this PHP class:

* `Prose\UsingSavageD`

## What Is SavageD?

[SavageD](https://github.com/datasift/SavageD) is a process and server monitoring process developed by DataSift.  It logs results in real-time via [Etsy's statsd](https://github.com/etsy/statsd) into [Graphite](https://github.com/graphite-project).

It is controlled via a REST-like API, which makes it perfect for use with Storyplayer:

1. Use Storyplayer to start the process that you want to test
1. Use Storyplayer to tell SavageD the process(es) to monitor
1. When the test is finished, use Storyplayer to tell SavageD to stop monitoring

This is much easier than trying to manipulate config files, and because you don't need to restart SavageD to change what it is monitoring, it's great for monitoring different phases of a complex story.

SavageD is written using NodeJS, and you can write your own NPM plugins to extend what it can monitor.

## Dependencies

This module relies on [SavageD](https://github.com/datasift/SavageD), which you will need to install for yourself.  It needs to be deployed into your test environment, onto the host that you want to monitor.  (SavageD needs to be on the same box because it gets its data from the machine's /proc filesystem).

To use SavageD, you'll also need Etsy's statsd and Graphite installed and working.  Refer to their documentation for assistance there.

## Configuration

You must add SavageD's HTTP port number to your [test environment configuration](../../using/configuration/test-environment-config.html):

{% highlight json %}
{
    "groups":
    [
        {
            "type": "...",
            "details": {
                "machines": {
                    "hostId": {
                        "moduleSettings": {
                            "savaged": {
                                "httpPort": 9030
                            }
                        }
                    }
                }
            }
        }
    ]
}
{% endhighlight %}

Please note that you need to add this _module setting_ to each host where SavageD is running. This allows you to have SavageD listening on different ports on different hosts.

## Using The SavageD Module

The basic format of an action is:

{% highlight php startinline %}
usingSavageD($hostId)->ACTION();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running
* __action__ is one of the documented actions available from _[usingSavageD](usingSavageD.html)_

To get and/or test the data created by SavageD, use the [Graphite module](../graphite/index.html).