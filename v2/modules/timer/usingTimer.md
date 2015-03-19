---
layout: v2/modules-timer
title: usingTimer()
prev: '<a href="../../modules/timer/index.html">Prev: The Timer Module</a>'
next: '<a href="../../modules/uuid/index.html">Next: The UUID Module</a>'
updated_for_v2: true
---

# usingTimer()

_usingTimer()_ allows you to wait for something to happen.  If it doesn't happen within the timeout period, an exception is thrown.

The source code for these actions can be found in the class `Prose\UsingTimer`.

## Behaviour And Return Codes

If the action succeeds, the action returns control to your code, and does not return a value.

If the action fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## waitFor()

Use `usingTimer()->waitFor()` to wait for something to happen.

{% highlight php startinline %}
usingTimer()->waitFor($callback, $timeout);
{% endhighlight %}

where:

* `$callback` is a PHP callback of some kind, which throws an exception if the 'something' hasn't happened yet (see [Repeated Polling](index.html#repeated_polling) for a longer discussion)
* `$timeout` is how long to wait before giving up (see [The DateInterval Is Your Friend](index.html#the-dateinterval-is-your-friend) for a longer discussion)

For example:

{% highlight php startinline %}
// assumes the current page title is not "Welcome to Example.com!"
usingTimer()->waitFor(function() {
    expectsBrowser()->hasTitle("Welcome to Example.com!");
}, 'PT5S');
{% endhighlight %}

## waitWhile()

Use `usingTimer()->waitFor()` to wait for something to change.

{% highlight php startinline %}
usingTimer()->waitFor($callback, $timeout);
{% endhighlight %}

where:

* `$callback` is a PHP callback of some kind, which throws an exception if the 'something' hasn't changed yet (see [Repeated Polling](index.html#repeated_polling) for a longer discussion)
* `$timeout` is how long to wait before giving up (see [The DateInterval Is Your Friend](index.html#the-dateinterval-is-your-friend) for a longer discussion)

For example:

{% highlight php startinline %}
// assumes the current page title is "Login"
usingTimer()->waitWhile(function() {
    expectsBrowser()->hasTitle("Login");
}, 'PT5S');
{% endhighlight %}

## wait()

Use `usingTimer()->wait()` to add a pause to your test.

{% highlight php startinline %}
usingTimer()->wait($timeout, $reason);
{% endhighlight %}

where:

* `$timeout` is how long to wait before giving up (see [The DateInterval Is Your Friend](index.html#the-dateinterval-is-your-friend) for a longer discussion)
* `$reason` is a text message for the log files to explain why this pause is there.