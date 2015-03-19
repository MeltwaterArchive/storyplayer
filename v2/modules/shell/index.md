---
layout: v2/modules-shell
title: The UNIX Shell Module
prev: '<a href="../../modules/savaged/usingSavageD.html">Prev: usingSavageD()</a>'
next: '<a href="../../modules/shell/child-processes.html">Next: The Role Of Child Processes In Testing</a>'
updated_for_v2: true
---

# The UNIX Shell Module

The __UNIX Shell__ module allows you to start and stop child processes that run on the same computer that _Storyplayer_ is running on.

The source code for this Storyplayer module can be found in these PHP classes:

* `Prose\ExpectsShell`
* `Prose\FromShell`
* `Prose\UsingShell`

## The Shell Module Now Runs On Localhost

In SPv1, the UNIX Shell module was its own thing. In SPv2, we've rewritten the module to simply be an alias the [Host module](../host/index.html).

* `expectsShell()` is an alias for `expectsHost("localhost")`
* `fromShell()` is an alias for `fromShell("localhost")`
* `usingShell()` is an alias for `usingShell("localhost")`

Storyplayer always adds _localhost_ to your test environment. You do not need to define it yourself (although you can if you want to). See [Test Environment Configuration](../../using/configuration/test-environment-config.html#localhost) for details.

## Dependencies

You need to install:

* [GNU Screen](http://www.gnu.org/software/screen/), a terminal emulator from [the Free Software Foundation](http://www.fsf.org/), and
* [bash](http://www.gnu.org/software/bash/), a popular UNIX shell

_bash_ is probably already installed on your computer. _screen_ may not be installed; you will need to install it by hand before you use this module.

<div class="callout warning" markdown="1">
#### OSX Users Need To Replace Apple's screen

Apple's OSX already includes `screen`. Unfortunately, it doesn't behave identically to the original GNU Screen. Please follow our [Setup Your Computer](../../learn/getting-setup/index.html) instructions and replace Apple's `screen` with the original.

What's different?

* When you close a GNU Screen, it automatically shuts down all child processes. Storyplayer relies heavily on this functionality to make sure that we do not leave rogue processes behind afterwards. Unfortunately, Apple's Screen does not shut down all child processes :(
</div>

## Using The UNIX Shell Module

The basic format of an action is:

{% highlight php startinline %}
MODULE()->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromShell()](fromShell.html)_ - get information about processes that you have started
* _[expectsShell()](expectsShell.html)_ - test processes that you have started
* _[usingShell()](usingShell.html)_ - start and stop processes

and __action__ is one of the documented actions available from __module__.

Here are some examples:

{% highlight php startinline %}
$sessions = usingShell()->getAllScreenSessions();
expectsShell()->isRunningInScreen("zmq-pull");
usingShell()->startInScreen('zmq-pull', BIN_DIR . '/zmq-pull');
{% endhighlight %}