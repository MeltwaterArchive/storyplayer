---
layout: modules-shell
title: The UNIX Shell Module
prev: '<a href="../../modules/timer/usingTimer.html">Prev: usingTimer()</a>'
next: '<a href="../../modules/shell/child-processes.html">Next: The Role Of Child Processes In Testing</a>'
---

# The UNIX Shell Module

The __UNIX Shell__ module allows you to start and stop child processes that run on the same computer that _storyplayer_ is running on.

The source code for this Prose module can be found in these PHP classes:

* DataSift\Storyplayer\Prose\ExpectsShell
* DataSift\Storyplayer\Prose\FromShell
* DataSift\Storyplayer\Prose\UsingShell

## Everything Is Screened

Whenever this module starts a new process, it runs that process inside a _screen_ session.  Here's why:

* _You can connect to the screen session manually to watch the output from the process._ This can be very handy when debugging a new test, or looking at why a test that used to work no longer does.
* _Screen handles killing off all child processes when it is told to terminate the session._ On UNIX systems, if you try to kill a process that has created its own child processes, those child processes can sometimes live on. If those child processes have sockets open, this can prevent tests running.  By using _screen_, we make sure that everything is tidied up when it's time to stop the process.

## Dependencies

You need to install:

* [GNU Screen](http://www.gnu.org/software/screen/), a terminal emulator from [the Free Software Foundation](http://www.fsf.org/), and
* [bash](http://www.gnu.org/software/bash/), a popular UNIX shell

_bash_ is probably already installed on your computer. _screen_ may not be installed; you will need to install it by hand before you use this module.

## Using The UNIX Shell Module

The basic format of an action is:

{% highlight php %}
$st->MODULE()->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromShell()](fromShell.html)_ - get information about processes that you have started
* _[expectsShell()](expectsShell.html)_ - test processes that you have started
* _[usingShell()](usingShell.html)_ - start and stop processes

and __action__ is one of the documented actions available from __module__.

Here are some examples:

{% highlight php %}
$sessions = $st->usingShell()->getAllScreenSessions();
$st->expectsShell()->isRunningInScreen("zmq-pull");
$st->usingShell()->startInScreen('zmq-pull', BIN_DIR . '/zmq-pull');
{% endhighlight %}