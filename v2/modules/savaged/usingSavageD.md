---
layout: v2/modules-savaged
title: usingSavageD()
prev: '<a href="../../modules/savaged/index.html">Prev: The SavageD Module</a>'
next: '<a href="../../modules/timer/index.html">Next: The Timer Module</a>'
updated_for_v2: true
---

# usingSavageD()

_usingSavageD()_ allows you to tell the [SavageD daemon](https://github.com/datasift/SavageD) which processes and server stats to monitor.

The source code for these actions can be found in the class `Prose\UsingSavageD`.

## Behaviour And Return Codes

If the action succeeds, control is returned to your code, and no value is returned.

If the action fails, an exception is thrown. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## API Call Order

Follow these steps to monitor your system-under-test via SavageD:

1. _[setStatsPrefix()](#setstatsprefix)_ to tell SavageD what name to give to stats logged into Graphite.
1. _watchServer\*()_ to tell SavageD what aspects of the server (if any) you want to monitor.
1. _[watchProcess()](#watchprocess)_ to tell SavageD that you want to monitor a given process.
1. _watchProcess\*()_ to tell SavageD what aspects of the process you want to monitor.
1. _[startMonitoring()](#startmonitoring)_ to start logging data into Graphite.

The key things here are:

* that you can't successfully call any of the _watchProcess\*()_ APIs until you've first called _[watchProcess()](#watchprocess)_, and
* don't call _[startMonitoring()](#startmonitoring)_ until you've called _[setStatsPrefix()](#setstatsprefix)_, otherwise your stats won't appear in Graphite in the place you're expecting them to!

## deleteStatsPrefix()

Use `usingSavageD()->deleteStatsPrefix()` to tell SavageD to stop using any previously-given prefix when writing stats into Graphite.

{% highlight php startinline %}
usingSavageD($hostId)->deleteStatsPrefix();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running

Normally, you only need to use this if you're writing stories to run against a production environment where SavageD has been installed.

See _[setStatsPrefix()](#setstatsprefix)_ for a discussion about the stats prefix.

## setStatsPrefix()

Use `usingSavageD()->setStatsPrefix()` to tell SavageD to use the prefix of your choice when writing stats into Graphite.

{% highlight php startinline %}
usingSavageD($hostId)->setStatsPrefix($prefix);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running
* `$prefix` is the Graphite stats path to use for all stats logged by SavageD

This will tell SavageD to make all stats written into statsd to start with 'qa.test1.';

At the moment, SavageD's stats prefix is global; that is, SavageD can't log some metrics with one prefix, and other prefixes with a different prefix.

## startMonitoring()

Use `usingSavageD()->startMonitoring()` to tell SavageD to start writing stats to Graphite via statsd.

{% highlight php startinline %}
usingSavageD($hostId)->startMonitoring();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running

## stopMonitoring()

Use `usingSavageD()->stopMonitoring()` to tell SavageD to stop writing stats to Graphite via statsd.

{% highlight php startinline %}
usingSavageD($hostId)->stopMonitoring();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running

## stopWatchingProcess()

Use `usingSavageD()->stopWatchingProcess()` to tell SavageD to stop writing stats about a specific process.

{% highlight php startinline %}
usingSavageD($hostId)->stopWatchingProcess($alias);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running
* `$alias` is the alias you originally used when you called _[watchProcess()](#watchProcess)_

When you call _stopWatchingProcess()_, SavageD will forget about any monitoring you've setup for the specified process. If you want to start monitoring the process later during the same test, you'll need to setup the monitoring for that process again from scratch - i.e., you can't just call _startWatchingProcess()_ and have all of your previous stats start appearing once again.

It's always a good idea to call _stopWatchingProcess()_ in your [test teardown phase](../../using/stories/test-setup-teardown.html).

## stopWatchingProcessCpu()

Use `usingSavageD()->stopWatchingProcessCpu()` to tell SavageD to stop writing CPU-related stats about a specific process.

{% highlight php startinline %}
usingSavageD($hostId)->stopWatchingProcessCpu($alias);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running
* `$alias` is the alias you originally used when you called _[watchProcess()](#watchProcess)_

Normally, you'd call _stopWatchingProcess()_ in your [test teardown phase](../../using/stories/test-setup-teardown.html), but _stopWatchingProcessCpu()_ is here if you need more fine-grained control.

## stopWatchingProcessMemory()

Use `usingSavageD()->stopWatchingProcessMemory()` to tell SavageD to stop writing memory-related stats about a specific process.

{% highlight php startinline %}
usingSavageD($hostId)->stopWatchingProcessMemory($alias);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running
* `$alias` is the alias you originally used when you called _[watchProcess()](#watchProcess)_.

Normally, you'd call _stopWatchingProcess()_ in your [test teardown phase](../../using/stories/test-setup-teardown.html), but _stopWatchingProcessMemory()_ is here if you need more fine-grained control.

## stopWatchingServerCpu()

Use `usingSavageD()->stopWatchingServerCpu()` to tell SavageD to stop writing CPU-related stats about the server that SavageD is running on.

{% highlight php startinline %}
usingSavageD($hostId)->stopWatchingServerCpu();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running

## stopWatchingServerLoadavg()

Use `usingSavageD()->stopWatchingServerLoadavg()` to tell SavageD to stop writing load-average related stats about the server that SavageD is running on.

{% highlight php startinline %}
usingSavageD($hostId)->stopWatchingServerLoadavg();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running

## watchProcess()

Use `usingSavageD()->watchProcess()` to tell SavageD that you want to monitor a specific process.  The process must be running on the same machine as SavageD.

{% highlight php startinline %}
usingSavageD($hostId)->watchProcess($alias, $pid);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running
* `$alias` is the prefix for the stats you want to write into Graphite.  `$alias` needs to be unique.  You'll also re-use `$alias` when you call _[watchProcessCpu()](#watchprocesscpu)_ et al

For example:

{% highlight php startinline %}
usingSavageD($ipAddress)->setStatsPrefix('qa.test1');
usingSavageD($ipAddress)->watchProcess('queue-server', 10056);
usingSavageD($ipAddress)->watchProcessCpu('queue-server');
usingSavageD($ipAddress)->startMonitoring();
{% endhighlight %}

tells SavageD to log CPU-related information about process ID 10056 to Graphite.  These stats will begin with the string 'qa.test1.queue-server'.

_Be aware that statsd normally adds its own prefix onto the stats that are written into Graphite._

## watchProcessCpu()

Use `usingSavageD()->watchProcessCpu()` to tell SavageD to write CPU-related stats for a given process.  You must first call _[watchProcess()](#watchprocess)_ before calling this method, and no stats are written to Graphite until you call _[startMonitoring()](#startmonitoring)_.

{% highlight php startinline %}
usingSavageD($hostId)->watchProcessCpu($alias);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running

The stats will appear as _$prefix.$alias.cpu.\*_ in Graphite, giving you the %CPU usage for each of the per-process CPU counters that your Linux kernel tracks.  100% CPU usage is the equivalent of using all of a single CPU core.  If your computer has multiple CPU cores, and your process is multi-threaded, it's possible for a process to use more than 100% CPU.

_Be aware that the kernel itself does not report CPU usage in percentages. SavageD has to calculate the percentages based on sampling the CPU-related stats once a second.  SavageD needs a couple of seconds after you start monitoring to grab a starting sample of stats to calculate the percentages from._

## watchProcessMemory()

Use `usingSavageD()->watchProcessMemory()` to tell SavageD to write memory-related stats for a given process.  You must first call _[watchProcess()](#watchprocess)_ before calling this method, and no stats are written to Graphite until you call _[startMonitoring()](#startmonitoring)_.

{% highlight php startinline %}
usingSavageD($hostId)->watchProcessMemory($alias);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running

The stats will appear as _$prefix.$alias.memory.\*_ in Graphite, giving you the usage amount in bytes.

_Be aware that the Linux kernel's memory counters are not additive, which can be confusing at first. When we write tests, we focus on two of the stats more than most: VmRSS + VmSwap. We make sure that VmSwap remains zero (ie the process doesn't swap to disk at all whilst under test), and that VmRSS is within an acceptable size._

## watchServerCpu()

Use `usingSavageD()->watchServerCpu()` to tell SavageD to write CPU-related stats about the server that SavageD is running on. No stats are written to Graphite until you call _[startMonitoring()](#startmonitoring)_.

{% highlight php startinline %}
usingSavageD($hostId)->watchServerCpu($alias);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running

The stats will appear as _$prefix.$alias.cpu.\*_ in Graphite, giving you the %CPU usage for each of the per-CPU counters that your Linux kernel tracks.  100% CPU usage is the equivalent of using all of a single CPU core.  If your computer has multiple CPU cores, the total CPU usage will be 100% * no of cores.

_Be aware that the kernel itself does not report CPU usage in percentages. SavageD has to calculate the percentages based on sampling the CPU-related stats once a second.  SavageD needs a couple of seconds after you start monitoring to grab a starting sample of stats to calculate the percentages from._

## watchServerLoadavg()

Use `usingSavageD()->watchServerLoadavg()` to tell SavageD to write load-average related stats about the server that SavageD is running on. No stats are written to Graphite until you call _[startMonitoring()](#startmonitoring)_.

{% highlight php startinline %}
usingSavageD($hostId)->watchServerLoadavg($alias);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment where SavageD is installed and running

The stats will appear as _$prefix.$alias.loadavg.\*_ in Graphite, giving you the load average figures for 1 minute, 5 minutes and 15 minutes.

_The load average is the number of processes in a runnable state.  When the load average is higher than the number of CPU cores in your computer, that can be an indication that you're trying to do too much on the machine. However, it's more nuanced than that, and we recommend that you focus more on measuring response latency and concurrent users / throughput to get a more accurate picture._