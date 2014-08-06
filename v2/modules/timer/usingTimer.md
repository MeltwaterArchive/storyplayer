---
layout: modules-timer
title: usingTimer()
prev: '<a href="../../modules/timer/index.html">Prev: The Timer Module</a>'
next: '<a href="../../modules/shell/index.html">Next: The UNIX Shell Module</a>'
---

# usingTimer()

_usingTimer()_ allows you to wait for something to happen.  If it doesn't happen within the timeout period, an exception is thrown.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingTimer_.

## Behaviour And Return Codes

If the action succeeds, the action returns control to your code, and does not return a value.

If the action fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## waitFor()

Use `$st->usingTimer()->waitFor()` to wait for something to happen

{% highlight php %}
$st->usingTimer()->waitFor($callback, $timeout);
{% endhighlight %}

where:

* `$callback` is a PHP callback of some kind, which throws an exception if the 'something' hasn't happened yet (see [Repeated Polling](index.html#repeated_polling) for a longer discussion)
* `$timeout` is how long to wait before giving up (see [The DateInterval Is Your Friend](index.html#the_dateinterval_is_your_friend) for a longer discussion)

For example:

{% highlight php %}
// assumes the current page title is not "Welcome to Example.com!"
$st->usingTimer()->waitFor(function($st) {
	$st->expectsBrowser()->hasTitle("Welcome to Example.com!");
}, 'PT5S');
{% endhighlight %}

## waitWhile()

Use `$st->usingTimer()->waitFor()` to wait for something to change

{% highlight php %}
$st->usingTimer()->waitFor($callback, $timeout);
{% endhighlight %}

where:

* `$callback` is a PHP callback of some kind, which throws an exception if the 'something' hasn't changed yet (see [Repeated Polling](index.html#repeated_polling) for a longer discussion)
* `$timeout` is how long to wait before giving up (see [The DateInterval Is Your Friend](index.html#the_dateinterval_is_your_friend) for a longer discussion)

For example:

{% highlight php %}
// assumes the current page title is "Login"
$st->usingTimer()->waitWhile(function($st) {
	$st->expectsBrowser()->hasTitle("Login");
}, 'PT5S');
{% endhighlight %}

## wait()

Use `$st->usingTimer()->wait()` to add a pause to your test.

{% highlight php %}
$st->usingTimer()->wait($timeout, $reason);
{% endhighlight %}

where:

* `$timeout` is how long to wait before giving up (see [The DateInterval Is Your Friend](index.html#the_dateinterval_is_your_friend) for a longer discussion)
* `$reason` is a text message for the log files to explain why this pause is there.