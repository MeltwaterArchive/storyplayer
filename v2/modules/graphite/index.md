---
layout: v2/modules-graphite
title: The Graphite Module
prev: '<a href="../../modules/fs/index.html">Prev: The Filesystem Module</a>'
next: '<a href="../../modules/graphite/fromGraphite.html">Next: fromGraphite()</a>'
---

# The Graphite Module

## Introduction

The _Graphite_ module allows you to extract data from [Graphite](https://github.com/graphite-project), and to use that data in your tests.

The source code for this Prose module can be found in these PHP classes:

* `Prose\ExpectsGraphite`
* `Prose\FromGraphite`

## How Does Data Get Into Graphite?

This module doesn't write data into Graphite, it only reads data that's already been collected by Graphite.  So how does the data get into Graphite in the first place?

The kind of data that goes into Graphite is data about your test, such as:

* throughput
* latency
* resource usage
* responses

This is data gathered when your own command-line utilities are pumping data into your back-end component, and any monitoring that you have in place whilst your test is running.  It is these tools that need to report data into Graphite for you.

For example, at DataSift, we've built three test tools that log data into Graphite:

* _Hornet_, our evil test tool, which generates high volumes of test data and writes it into whatever backend component we're testing
* _zmq-pull_, a lightweight ZMQ client that pulls data out of backend component, and
* _[SavageD](https://github.com/datasift/SavageD/)_, our real-time process and server monitoring tool

We use Storyplayer to control all three tools (using the [Shell module](../shell/index.html) to start and stop _Hornet_ and _zmq-pull_, and using the [SavageD module](../savaged/index.html) to tell SavageD what to monitor), and we use the Graphite module in the [post-test inspection phase](../../stories/post-test-inspection.html) to look at the data in Graphite and decide whether or not the test passed.

This allows us to test non-functional requirements at the same time that we're testing functional requirements.  Being able to test both in one test run can save a lot of time when preparing software for release :)

## Dependencies

This module relies on [Graphite](https://github.com/graphite-project), which you will need to install somewhere.  Most software that reports to Graphite does so via [Etsy's statsd](https://github.com/etsy/statsd); you might need that too.  (We use it heavily at DataSift).

We also recommend following [Stuart's real-time graphing with Graphite instructions](http://blog.stuartherbert.com/php/2011/09/21/real-time-graphing-with-graphite/) to get Graphite working and reporting at per-second resolution.  Being able to see what has happened to the software under test second by second often provides deeper insights than looking at aggregated per-minute stats.

## Configuration

Once you have Graphite (and optionally statsd) installed, you'll need to update the _storyplayer.json_ config file with the URL where Graphite can be found.

{% highlight json %}
{
    "environments": {
        <your-environment-name>: {
            "graphite": {
                "url": <url-to-graphite>
            },
            "statsd": {
                "host": <host-or-ip-address>
            }
        }
    }
}
{% endhighlight %}

## Using The Graphite Module

The basic format of an action is:

{% highlight php %}
MODULE()->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromGraphite()](fromGraphite.html)_ - get data from Graphite
* _[expectsGraphite()](expectsGraphite.html)_ - test the data available from Graphite

and __action__ is one of the documented actions available from __module__.