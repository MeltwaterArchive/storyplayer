---
layout: modules-shell
title: fromShell()
prev: '<a href="../../modules/shell/child-processes.html">Prev: The Role Of Child Processes In Testing</a>'
next: '<a href="../../modules/shell/expectsShell.html">Next: expectsShell()</a>'
---

# fromShell()

_fromShell()_ allows you to extract information about the processes that your test has started.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\ShellDetermine_.

## Behaviour And Return Codes

Every action either returns a value on success, or _NULL_ on failure. None of these actions throw exceptions on failure.

## getIsScreenRunning()

Use _$st->fromShell()->getIsScreenRunning()_ to see if a process you previously started is still running.

{% highlight php %}
$isRunning = $st->fromShell()->getIsScreenRunning($screenName);
{% endhighlight %}

where:

* _$screenName_ is the name you assigned the process when you called _[usingShell()->startInScreen()](usingShell.html#startinscreen)_
* _$isRunning_ is _TRUE_ if the process is still running, or _FALSE_ otherwise

__See Also__:

* [usingShell()->startInScreen()](usingShell.html#startinscreen)

## getIsProcessRunning()

Use _$st->fromShell()->getIsProcessRunning()_ to see if a process you previously started is still running.

{% highlight php %}
$isRunning = $st->fromShell()->getIsProcessRunning($pid);
{% endhighlight %}

where:

* _$pid_ is the process ID you want to test
* _$isRunning_ is _TRUE_ if the process is still running, or _FALSE_ otherwise

This call is used internally by _getIsScreenRunning()_.

## getScreenSessionDetails()

Use _$st->fromShell()->getScreenSessionDetails()_ to get back all the details currently available for a process you previously started.

{% highlight php %}
$details = $st->fromShell()->getScreenSessionDetails($screenName);
{% endhighlight %}

where:

* _$screenName_ is the name you assigned the process when you called _[usingShell()->startInScreen()](usingShell.html#startinscreen)_
* _$details_ is a plain old PHP object containing all of the current details

We may add additional information into _$details_ in the future, but we expect to keep it backwards-compatible, so that it is safe to use this inside your own Prose modules.

Here are the current details available:

* _$details->screenName_ contains the name of the screen session (which appears when you run `screen -ls` from a terminal)
* _$details->commandLine_ contains the full commandline used by Storyplayer to run the process
* _$details->pid_ contains the process ID of the screen process

__Notes__:

* When you use _[usingShell()->stopInScreen()](usingShell.html#stopinscreen)_, we remove the process's details from our internal list.

## getAllScreenSessions()

Use _$st->fromShell()->getAllScreenSessions()_ to get back all of the details for all of the processes that you have previously started.

{% highlight php %}
$list = $st->fromShell()->getAllScreenSessions();
{% endhighlight %}

where:

* _$list_ is a plain old PHP object, where each attribute is a plain old PHP object containing the current details for a process previously started using _[usingShell()->startInScreen()](usingShell.html#startinscreen)_.

You can iterate over the list safely:

{% highlight php %}
$list = $st->fromShell()->getAllScreenSessions();
foreach ($list as $screenName => $details)
{
	$st->usingShell()->stopProcess($details->pid);
}
{% endhighlight %}

We may add additional information into these PHP objects in the future, but we expect to keep it backwards-compatible, so that it is safe to use inside your own Prose modules. See _[getScreenSessionDetails()](#getscreensessiondetails)_ for a list of the information we track about each shell process.