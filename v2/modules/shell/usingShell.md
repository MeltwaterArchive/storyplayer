---
layout: v2/modules-shell
title: usingShell()
prev: '<a href="../../modules/shell/expectsShell.html">Prev: expectsShell()</a>'
next: '<a href="../../modules/supervisor/index.html">Next: The Supervisor Module</a>'
---

# usingShell()

_usingShell()_ allows you to start and stop processes on the same computer that Storyplayer is running on.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingShell_.

## Behaviour And Return Codes

Every action starts and stops processes on the same computer that Storyplayer is running on.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, the action throws an exception.  _Do not catch exceptions thrown by these actions_.  Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action must succeed.

## runCommand()

Use `$st->usingShell()->runCommand()` to run an arbitrary command on the same computer that Storyplayer is running on.

{%highlight php %}
$result = $st->usingShell()->runCommand($command);
{% endhighlight %}

where:

* `$command` is a UNIX command to run
* `$result` is a _CommandResult_ object containing both the command's return code, and the command's output

This command is mostly for use by other modules (such as the _[Provisioning](../provisioning/index.html)_ module), but you're free to use it in your tests if you like.

## startInScreen()

Use `$st->usingShell()->startInScreen()` to start a process on the same computer that Storyplayer is running on.

{% highlight php %}
$st->usingShell()->startInScreen($screenName, $commandLine);
{% endhighlight %}

where:

* `$screenName` is the name you want to give to this process
* `$commandLine` is the command to run

You will re-use `$screenName` when:

* calling _[fromShell()->getIsScreenRunning()](fromShell.html#getisscreenrunning)_ to see if the process you have started is still running
* calling _[expectsShell()->isRunningInScreen()](expectsShell.html#isrunninginscreen)_ to make sure that the process you have started is still running
* calling _[usingShell()->stopInScreen()](#stopinscreen)_ to stop the process from your test

In the background, Storyplayer starts a new `screen` session, and inside that session then uses the `bash` shell to execute `$commandLine`.  You will need to escape any double quotes (") that appear in `$commandLine`.

## stopInScreen()

Use `$st->usingShell()->stopInScreen()` to stop a process that was originally started using _[usingShell()->startInScreen()](#startinscreen)_:

{% highlight php %}
$st->usingShell()->stopInScreen($screenName);
{% endhighlight %}

where:

* `$screenName` is the same name that you used when you called _[usingShell()->startInScreen()](#startinscreen)_

## stopAllScreens()

Use `$st->stopAllScreens()` to stop all processes that have been started using _[usingShell()->startInScreen()](#startinscreen)_:

{% highlight php %}
$st->usingShell()->stopAllScreens();
{% endhighlight %}

If there are no processes running, no error is thrown.

## stopProcess()

Use `$st->stopProcess()` to stop process that is running on the same computer that Storyplayer is running on.

{% highlight php %}
$st->usingShell()->stopProcess($pid);
{% endhighlight %}

where:

* `$pid` is the UNIX process ID that you want to stop

Normally, you would call _[usingShell()->stopInScreen()](#stopinscreen)_ instead of _stopProcess()_.