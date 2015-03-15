---
layout: v2/modules-host
title: fromHost()
prev: '<a href="../../modules/host/supported-hosts.html">Prev: Supported Hosts</a>'
next: '<a href="../../modules/host/expectsHost.html">Next: expectsHost()</a>'
updated_for_v2: true
---

# fromHost()

_fromHost()_ allows you to get information about a computer in your test environment.

The source code for these actions can be found in the class `Prose\FromHost`.

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  These actions do throw an exception if you attempt to work with an unknown host.

## downloadFile()

Use `fromHost()->downloadFile()` to download a file from a given host in your test environment.

{% highlight php startinline %}
fromHost($hostId)->downloadFile($src, $dest)
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$src` is the path to the file on the remote host
* `$dest` is the path on your computer where you want to download the file to

## getAllScreenSessions()

Use `fromHost()->getAllScreenSessions()` to retrieve what Storyplayer knows about all `screen` sessions on a given host in your test environment.

{% highlight php startinline %}
$sessions = fromHost($hostId)->getAllScreenSessions();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$sessions` gets set to an array of PHP object containing details about the screen sessions. If there are no sessions, an empty array is returned.

## getAppSetting()

Use `fromHost()->getAppSetting()` to retrieve

{% highlight php startinline %}
$appSetting = fromHost($hostId)->getAppSetting($appSettingName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$appSettingName` is the dot.notation.path to the setting you want
* `$appSetting` gets set to the setting you asked for

Use _[appSetting()](#appSetting)_ to retrieve a single value, and _[appSettings()](#appSettings)_ to retrieve a group of settings.

## getAppSettings()

Use `fromHost()->getAppSettings()` to retrieve

{% highlight php startinline %}
$appSettings = fromHost($hostId)->getAppSettings($appName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$appName` is the name of the app you want the settings of
* `$appSettings` gets set to a PHP object containing the app settings you requested

Use _[appSetting()](#appSetting)_ to retrieve a single value, and _[appSettings()](#appSettings)_ to retrieve a group of settings.

## getDetails()

Use `fromHost()->getDetails()` to retrieve the host's entry in Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html).

{% highlight php startinline %}
$details = fromHost($hostId)->getDetails();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$details` gets set to a PHP object containing the host's entry in the hosts table

__NOTE__

* `$details` isn't a clone of the hosts table entry; any changes you make to these details will be persistent

## getFileDetails()

Use `fromHost()->getFileDetails()` to return details about a file on a given host in your test environment.

{% highlight php startinline %}
$details = fromHost($hostId)->getFileDetails($path);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$path` is the path to the file on remote host to investigate
* `$details` gets set to a PHP object containing details about the file

## getHostIsRunning()

Use `fromHost()->getHostIsRunning()` to determine if the specified host is currently running or not.

{% highlight php startinline %}
$isRunning = fromHost($hostId)->getHostIsRunning();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$isRunning` gets set to _TRUE_ if the host is currently running, or _FALSE_ otherwise

If the host is not running, this could be because your test has stopped the host or powered it off.  If your test has destroyed the host, then calling this action will throw an exception.

## getHostname()

Use `fromHost()->getHostname()` to get the host's network hostname.

{% highlight php startinline %}
$hostname = fromHost($hostId)->getHostname();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$hostname` gets set to the host's network hostname

## getInstalledPackageDetails()

Use `fromHost()->getInstalledPackageDetails()` to get information about an installed package from the guest operating system's inventory.

{% highlight php startinline %}
$details = fromHost($hostId)->getInstalledPackageDetails($packageName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$packageName` is the name of the package that you want details about
* `$details` gets set to a PHP object containing information about the package

__NOTE__

* The contents of `$details` are currently operating-system specific.
* If the package is not installed, _isset($details->version)_ will always be _FALSE_.

## getIpAddress()

Use `fromHost()->getIpAddress()` to get the host's IPv4 address.

{% highlight php startinline %}
$ipAddress = fromHost($hostId)->getIpAddress();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$ipAddress` gets set to the host's IPv4 address

__NOTE__

* If the virtual machine has multiple active network interfaces, only one will be returned.  This is an area which may require more work in a future release of Storyplayer.

## getPid()

Use `fromHost()->getPid()` to get the process ID of a running process.

{% highlight php startinline %}
$pid = fromHost($hostId)->getPid($processName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$processName` is the string to search the output of `ps` for
* `$pid` gets set to the process ID of the process that you searched for, or `NULL` if the process is not running

__NOTE__

* If multiple processes match `$processName`, only one process ID will be returned.  This is an area which may require more work in a future release of Storyplayer.

## getPidIsRunning()

Use `fromHost()->getPidIsRunning()` to determine if a process is currently running or not.

{% highlight php startinline %}
$isRunning = fromHost($hostId)->getPidIsRunning($pid);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$pid` is the process ID to check
* `$isRunning` gets set to _TRUE_ if the process is running, or _FALSE_ if the process is not running

## getProcessIsRunning()

Use `fromHost()->getProcessIsRunning()` to determine if a process is currently running or not.

{% highlight php startinline %}
$isRunning = fromHost($hostId)->getProcessIsRunning($processName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$processName` is the string to search the output of `ps` for
* `$isRunning` gets set to _TRUE_ if the process is running, or _FALSE_ if the process is not running

## getScreenSessionDetails()

Use `fromHost()->getScreenSessionDetails()` to retrieve what Storyplayer knows about a given `screen` session in your test environment.

{% highlight php startinline %}
$sessionData = fromHost($hostId)->getScreenSessionDetails($sessionName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$sessionName` is the name of the `screen` session to check on
* `$sessionData` gets set to a PHP object containing details about the screen session, or `NULL` if the session cannot be found.

## getScreenIsRunning()

Use `fromHost()->getScreenIsRunning()` to see if a given `screen` session is currently active.

{% highlight php startinline %}
$isRunning = fromHost($hostId)->getScreenIsRunning($sessionName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$sessionName` is the name of the `screen` session to check on
* `$isRunning` gets set to _TRUE_ if the session is running, or _FALSE_ if the session is not running

## getSshUsername()

Use `fromHost()->getSshUsername()` to get the default username used for SSH'ing into the host.

{% highlight php startinline %}
$sshUsername = fromHost($hostName)->getSshUsername();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$sshUsername` is the default SSH username for that host

## getSshKeyFile()

Use `fromHost()->getSshKeyFile()` to get the path to the SSH private key file that Storyplayer will use in _[usingHost()->runCommand()](usingHost.html#runcommand)_ et al.

{% highlight php startinline %}
$sshKeyFile = fromHost($hostName)->getSshKeyFile();
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host
* `$sshKeyFile` is the default SSH key file for that host

The SSH private key file is set when the host is originally created (e.g. when _[usingVagrant()->createVm()](../vagrant/usingVagrant.html#createvm)_ is called).
