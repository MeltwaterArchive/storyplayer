---
layout: v2/modules-host
title: expectsHost()
prev: '<a href="../../modules/host/fromHost.html">Prev: fromHost()</a>'
next: '<a href="../../modules/host/usingHost.html">Next: usingHost()</a>'
---

# expectsHost()

_expectsHost()_ allows you to make sure that the host is in the state you need it to be.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\ExpectsHost_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## hostIsRunning()

Use `expectsHost()->hostIsRunning()` to ensure that a host is up and running.

{% highlight php %}
expectsHost($hostName)->hostIsRunning();
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host

If the host is not running (for example, it failed to start after being created or rebooted), an exception is thrown.

## hostIsNotRunning()

Use `expectsHost()->hostIsNotRunning()` to ensure that a host is currently shutdown.

{% highlight php %}
expectsHost($hostName)->hostIsNotRunning();
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host

If the host is running (for example, it failed to shutdown when requested to), an exception is thrown.

## packageIsInstalled()

Use `expectsHost()->packageIsInstalled()` to ensure that a package is installed on the guest operating system.

{% highlight php %}
expectsHost($hostName)->packageIsInstalled($packageName);
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host
* `$packageName` is the name of the package that must be installed

If the package is not installed, an exception is thrown.

## packageIsNotInstalled()

Use `expectsHost()->packageIsNotInstalled()` to ensure that a package is not installed on the guest operating system.

{% highlight php %}
expectsHost($hostName)->packageIsNotInstalled($packageName);
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host
* `$packageName` is the name of the package that must not be installed

If the package is installed, an exception is thrown.

## processIsRunning()

Use `expectsHost()->processIsRunning()` to ensure that a process is running.

{% highlight php %}
expectsHost($hostName)->processIsRunning($processName);
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host
* `$processName` is the string to search the output of `ps` for

If the process is not running, an exception is thrown.

## processIsNotRunning()

Use `expectsHost()->processIsNotRunning()` to ensure that a process is not running.

{% highlight php %}
expectsHost($hostName)->processIsNotRunning($processName);
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host
* `$processName` is the string to search the output of `ps` for

If the process is running, an exception is thrown.
