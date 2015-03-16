---
layout: v2/modules-timer
title: The Timer Module
prev: '<a href="../../modules/savaged/usingSavageD.html">Prev: usingSavageD()</a>'
next: '<a href="../../modules/timer/usingTimer.html">Next: usingTimer()</a>'
---

# The Timer Module

The __Timer__ module allows you to wait for something to happen, and to timeout (which throws an exception) if that something doesn't happen quickly enough.

The source code for this module can be found in this PHP class:

* `Prose\UsingTimer`

## Dependencies

This module relies on [DataSift's Stone library](http://github.com/datasift/Stone), which is installed as part of Storyplayer's installation.

## Using The Timer Module

The basic format of an action is either:

{% highlight php startinline %}
usingTimer()->ACTION($callback, $timeout);
{% endhighlight %}

or

{% highlight php startinline %}
usingTimer()->ACTION($timeout);
{% endhighlight %}

where:

* `$callback` is the method that polls (see [Repeated Polling](#repeated_polling) below)
* `$timeout` is how long to keep polling for (see [The DateInterval Is Your Friend](#the_dateinterval_is_your_friend) below)

## Repeated Polling

Many of the Timer module's actions take a `$callback` parameter.  This is a [PHP anonymous function](http://uk1.php.net/manual/en/functions.anonymous.php).  It takes one parameter ([the `$st` object](../../Prose/the-st-object.html)), and its job is to run one or more tests to see if whatever you're waiting for has happened yet.

The callback function should throw an exception if whatever you're waiting for hasn't happened yet.

For example, here's how _[usingBrowser()->waitForTitle()](../browser/usingBrowser.html#waitfortitle)_ is implemented, using _[waitFor()](usingTimer.html#waitfor)_:

{% highlight php startinline %}
$title = "Welcome To Example.com!";
$timeout = 'PT2S';
usingTimer()->waitFor(function($st) use($title) {
	expectsBrowser()->hasTitle($title);
}, $timeout);
{% endhighlight %}

The callback function uses `expectsBrowser()`, which will throw an exception for you if the current HTML page has the wrong title.  Internally, the Timer module catches this exception as long as the timeout hasn't been reached yet.

After 2 seconds (the value of `$timeout` in the example), if the HTML page in the browser doesn't have the title 'Welcome To Example.com', _waitFor()_ stops polling and throws an exception.

## The DateInterval Is Your Friend

All of the timeout parameters used in this module expect a string that's compatible with [DateInterval's constructor's $interval_spec parameter](http://uk1.php.net/manual/en/dateinterval.construct.php).

An interval follows this format:

* __P__&lt;optional years, months, days&gt;, or
* __P__&lt;optional years, months, days&gt;__T__&lt;optional hours, minutes, seconds&gt;

Here are some examples:

* __PT5M__ - wait for 5 minutes
* __P1D__ - wait for 1 day
* __P1DT12H__ - wait for 1 day, 12 hours

If you forget, and accidentally pass in an integer instead, the Timer module will assume that this is the number of seconds to use for the timeout.