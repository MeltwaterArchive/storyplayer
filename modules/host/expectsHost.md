---
layout: modules-host
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

Use _$st->expectsHost()->hostIsRunning()_ to ensure that a host is up and running.

{% highlight php %}
$st->expectsHost($hostName)->hostIsRunning();
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host

If the host is not running (for example, it failed to start after being created or rebooted), an exception is thrown.

## hostIsNotRunning()

Use _$st->expectsHost()->hostIsNotRunning()_ to ensure that a host is currently shutdown.

{% highlight php %}
$st->expectsHost($hostName)->hostIsNotRunning();
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host

If the host is running (for example, it failed to shutdown when requested to), an exception is thrown.

## packageIsInstalled()

Use _$st->expectsHost()->packageIsInstalled()_ to ensure that a package is installed on the guest operating system.

{% highlight php %}
$st->expectsHost($hostName)->packageIsInstalled($packageName);
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$packageName_ is the name of the package that must be installed

If the package is not installed, an exception is thrown.

## packageIsNotInstalled()

Use _$st->expectsHost()->packageIsNotInstalled()_ to ensure that a package is not installed on the guest operating system.

{% highlight php %}
$st->expectsHost($hostName)->packageIsNotInstalled($packageName);
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$packageName_ is the name of the package that must not be installed

If the package is installed, an exception is thrown.

## processIsRunning()

Use _$st->expectsHost()->processIsRunning()_ to ensure that a process is running.

{% highlight php %}
$st->expectsHost($hostName)->processIsRunning($processName);
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$processName_ is the string to search the output of `ps` for

If the process is not running, an exception is thrown.

## processIsNotRunning()

Use _$st->expectsHost()->processIsNotRunning()_ to ensure that a process is not running.

{% highlight php %}
$st->expectsHost($hostName)->processIsNotRunning($processName);
{% endhighlight %}

where:

* _$hostName_ is the name you set when you created the host
* _$processName_ is the string to search the output of `ps` for

If the process is running, an exception is thrown.
