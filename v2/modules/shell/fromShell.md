---
layout: v2/modules-shell
title: fromShell()
prev: '<a href="../../modules/shell/child-processes.html">Prev: The Role Of Child Processes In Testing</a>'
next: '<a href="../../modules/shell/expectsShell.html">Next: expectsShell()</a>'
---

# fromShell()

_fromShell()_ allows you to extract information about the processes that your test has started.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromShell_.

## Behaviour And Return Codes

Every action either returns a value on success, or `NULL` on failure. None of these actions throw exceptions on failure.

## getIsScreenRunning()

Use `fromShell()->getIsScreenRunning()` to see if a process you previously started is still running.

{% highlight php %}
$isRunning = fromShell()->getIsScreenRunning($screenName);
{% endhighlight %}

where:

* `$screenName` is the name you assigned the process when you called _[usingShell()->startInScreen()](usingShell.html#startinscreen)_
* `$isRunning` is _TRUE_ if the process is still running, or _FALSE_ otherwise

__See Also__:

* [usingShell()->startInScreen()](usingShell.html#startinscreen)

## getIsProcessRunning()

Use `fromShell()->getIsProcessRunning()` to see if a process you previously started is still running.

{% highlight php %}
$isRunning = fromShell()->getIsProcessRunning($pid);
{% endhighlight %}

where:

* `$pid` is the process ID you want to test
* `$isRunning` is _TRUE_ if the process is still running, or _FALSE_ otherwise

This call is used internally by _getIsScreenRunning()_.

## getScreenSessionDetails()

Use `fromShell()->getScreenSessionDetails()` to get back all the details currently available for a process you previously started.

{% highlight php %}
$details = fromShell()->getScreenSessionDetails($screenName);
{% endhighlight %}

where:

* `$screenName` is the name you assigned the process when you called _[usingShell()->startInScreen()](usingShell.html#startinscreen)_
* `$details` is a plain old PHP object containing all of the current details

We may add additional information into `$details` in the future, but we expect to keep it backwards-compatible, so that it is safe to use this inside your own Prose modules.

Here are the current details available:

* _$details->screenName_ contains the name of the screen session (which appears when you run `screen -ls` from a terminal)
* _$details->commandLine_ contains the full commandline used by Storyplayer to run the process
* _$details->pid_ contains the process ID of the screen process

__Notes__:

* When you use _[usingShell()->stopInScreen()](usingShell.html#stopinscreen)_, we remove the process's details from our internal list.

## getAllScreenSessions()

Use `fromShell()->getAllScreenSessions()` to get back all of the details for all of the processes that you have previously started.

{% highlight php %}
$list = fromShell()->getAllScreenSessions();
{% endhighlight %}

where:

* `$list` is a plain old PHP object, where each attribute is a plain old PHP object containing the current details for a process previously started using _[usingShell()->startInScreen()](usingShell.html#startinscreen)_.

You can iterate over the list safely:

{% highlight php %}
$list = fromShell()->getAllScreenSessions();
foreach ($list as $screenName => $details)
{
	usingShell()->stopProcess($details->pid);
}
{% endhighlight %}

We may add additional information into these PHP objects in the future, but we expect to keep it backwards-compatible, so that it is safe to use inside your own Prose modules. See _[getScreenSessionDetails()](#getscreensessiondetails)_ for a list of the information we track about each shell process.