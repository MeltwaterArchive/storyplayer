---
layout: modules-shell
title: expectsShell()
prev: '<a href="../../modules/shell/fromShell.html">Prev: fromShell()</a>'
next: '<a href="../../modules/shell/usingShell.html">Next: usingShell()</a>'
---

# expectsShell()

_expectsShell()_ allows you to test the state of a process you previously started using [usingShell()](usingShell.html).

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\ExpectsShell_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception.  _Do not catch exceptions thrown by these actions_.  Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## isRunningInScreen()

Use `$st->expectsShell()->isRunningInScreen()` to make sure that a process you previously started is still running.

{% highlight php %}
$st->expectsShell()->isRunningIsScreen($screenName);
{% endhighlight %}

where:

* `$screenName` is the name you assigned the process when you called _[usingShell()->startInScreen()](usingShell.html#startinscreen)_

## isNotRunningInScreen()

Use `$st->expectsShell()->isRunningInScreen()` to make sure that a process you previously started is no longer running.

{% highlight php %}
$st->expectsShell()->isNotRunningIsScreen($screenName);
{% endhighlight %}

where:

* `$screenName` is the name you assigned the process when you called _[usingShell()->startInScreen()](usingShell.html#startinscreen)_