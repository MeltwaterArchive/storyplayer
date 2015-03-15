---
layout: v2/modules-host
title: expectsHost()
prev: '<a href="../../modules/host/fromHost.html">Prev: fromHost()</a>'
next: '<a href="../../modules/host/usingHost.html">Next: usingHost()</a>'
updated_for_v2: true
---

# expectsHost()

_expectsHost()_ allows you to make sure that the host is in the state you need it to be.

The source code for these actions can be found in the class `Prose\ExpectsHost`.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## hasFileWithPermissions()

Use `expectsHost()->hasFileWithPermissions()` to ensure that a given file exists on the host.

{% highlight php startinline %}
expectsHost($hostId)->hasFileWithPermissions($filename, $owner, $group, $mode);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$filename` is the path to the file to check for
* `$owner` is the username who must own the file
* `$group` is the name of the group which must own the file
* `$mode` is the octal mode for the permissions that the file must have

## hasFolderWithPermissions()

Use `expectsHost()->hasFolderWithPermissions()` to ensure that a given folder exists on the host.

{% highlight php startinline %}
expectsHost($hostId)->hasFolderWithPermissions($folder, $owner, $group, $mode);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$folder` is the path to the folder to check for
* `$owner` is the username who must own the folder
* `$group` is the name of the group which must own the folder
* `$mode` is the octal mode for the permissions that the folder must have

## hostIsRunning()

Use `expectsHost()->hostIsRunning()` to ensure that a host is up and running.

{% highlight php startinline %}
expectsHost($hostId)->hostIsRunning();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment

If the host is not running (for example, it failed to start after being created or rebooted), an exception is thrown.

## hostIsNotRunning()

Use `expectsHost()->hostIsNotRunning()` to ensure that a host is currently shutdown.

{% highlight php startinline %}
expectsHost($hostId)->hostIsNotRunning();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment

If the host is running (for example, it failed to shutdown when requested to), an exception is thrown.

## packageIsInstalled()

Use `expectsHost()->packageIsInstalled()` to ensure that a package is installed on the guest operating system.

{% highlight php startinline %}
expectsHost($hostId)->packageIsInstalled($packageName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$packageName` is the name of the package that must be installed

If the package is not installed, an exception is thrown.

## packageIsNotInstalled()

Use `expectsHost()->packageIsNotInstalled()` to ensure that a package is not installed on the guest operating system.

{% highlight php startinline %}
expectsHost($hostId)->packageIsNotInstalled($packageName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$packageName` is the name of the package that must not be installed

If the package is installed, an exception is thrown.

## processIsRunning()

Use `expectsHost()->processIsRunning()` to ensure that a process is running.

{% highlight php startinline %}
expectsHost($hostId)->processIsRunning($processName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$processName` is the string to search the output of `ps` for

If the process is not running, an exception is thrown.

## processIsNotRunning()

Use `expectsHost()->processIsNotRunning()` to ensure that a process is not running.

{% highlight php startinline %}
expectsHost($hostId)->processIsNotRunning($processName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$processName` is the string to search the output of `ps` for

If the process is running, an exception is thrown.

## screenIsRunning()

Use `expectsHost()->screenIsRunning()` to ensure that a screen session is running.

{% highlight php startinline %}
expectsHost($hostId)->screenIsRunning($sessionName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$sessionName` is the name of the screen session you're checking on

## screenIsNotRunning()

Use `expectsHost()->screenIsNotRunning()` to ensure that a screen session is not running.

{% highlight php startinline %}
expectsHost($hostId)->screenIsNotRunning($sessionName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$sessionName` is the name of the screen session you're checking on
