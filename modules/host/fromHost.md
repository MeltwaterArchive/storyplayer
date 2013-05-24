---
layout: modules-host
title: fromHost()
prev: '<a href="../../modules/host/supported-hosts.html">Prev: Supported Hosts</a>'
next: '<a href="../../modules/host/expectsHost.html">Next: expectsHost()</a>'
---

# fromHost()

_fromHost()_ allows you to get information about host and its current state.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\HostDetermine_.

## Behaviour And Return Codes

Every action returns either a value on success, or _NULL_ on failure.  These actions do throw an exception if you attempt to work with an unknown host.

## getDetails()

Use _$st->fromHost()->getDetails()_ to retrieve the host's entry in Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html).

{% highlight php %}
$details = $st->fromHost($hostName)->getDetails();
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$details_ is a PHP object containing the host's entry in the hosts table

__NOTE__

* _$details_ isn't a clone of the hosts table entry; any changes you make to these details will be persistent

## getHostIsRunning()

Use _$st->fromHost()->getHostIsRunning()_ to determine if the specified host is currently running or not.

{% highlight php %}
$isRunning = $st->fromHost($hostName)->getHostIsRunning();
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$isRunning_ is _TRUE_ if the host is currently running, or _FALSE_ otherwise

If the host is not running, this could be because your test has stopped the host or powered it off.  If your test has destroyed the host, then calling this action will throw an exception.

## getInstalledPackageDetails()

Use _$st->fromHost()->getInstalledPackageDetails()_ to get information about an installed package from the guest operating system's inventory.

{% highlight php %}
$details = $st->fromHost($hostName)->getInstalledPackageDetails($packageName);
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$packageName_ is the name of the package that you want details about
* _$details_ is a PHP object containing information about the package

__NOTE__

* The contents of _$details_ are currently operating-system specific.
* If the package is not installed, _isset($details->version)_ will always be _FALSE_.

## getIpAddress()

Use _$st->fromHost()->getIpAddress()_ to get the host's current IP address.

{% highlight php %}
$ipAddress = $st->fromHost($hostName)->getIpAddress();
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$ipAddress_ is the IP address of an active network interface

__NOTE__

* If the virtual machine has multiple active network interfaces, only one will be returned.  This is an area which may require more work in a future release of Storyplayer.

## getPid()

Use _$st->fromHost()->getPid()_ to get the process ID of a running process.

{% highlight php %}
$pid = $st->fromHost($hostName)->getPid($processName);
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$processName_ is the string to search the output of `ps` for
* _$pid_ is the process ID of the process that you searched for, or _NULL_ if the process is not running

__NOTE__

* If multiple processes match _$processName_, only one process ID will be returned.  This is an area which may require more work in a future release of Storyplayer.

## getProcessIsRunning()

Use _$st->fromHost()->getProcessIsRunning()_ to determine if a process is currently running or not.

{% highlight php %}
$isRunning = $st->fromHost($hostName)->getProcessIsRunning($processName);
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$processName_ is the string to search the output of `ps` for
* _$isRunning_ is _TRUE_ if the process is running, or _FALSE_ if the process is not running
