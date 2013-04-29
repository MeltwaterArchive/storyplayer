---
layout: modules-graphite
title: expectsGraphite()
prev: '<a href="../../modules/graphite/fromGraphite.html">Prev: fromGraphite()</a>'
next: '<a href="../../copyright.html">Next: Legal Stuff</a>'
---
# expectsGraphite()

_expectsGraphite()_ allows you to test the data in Graphite.

The source code for these actions can be found in the class _DataSift\Storyplayer\GraphiteExpects_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## metricIsAlwaysZero()

Use _$st->expectsGraphite()->metricIsAlwaysZero()_ to ensure that data in Graphite for a given metric, between two given times, is always zero.

{% highlight php %}
$now = time();
$data = $st->expectsGraphite()->metricIsAlwaysZero('qa.api.http.500', $now - 300, $now);
{% endhighlight %}

_metricIsAlwaysZero()_ takes three parameters:

* _$metric_ - the name of the Graphite metric to retrieve
* _$startTime_ - the time in seconds (since the epoch) to start retrieving data from
* _$endTime_ - the time in seconds (since the epoch) to retrieve data until

If any of the data between _$startTime_ and _$endTime_ has a value higher than zero, then this test fails, and an exception is thrown.

Missing data is treated as having a value of zero, for this test.

## metricSumIs()

Use _$st->expectsGraphite()->metricSumIs()_ to ensure that data in Graphite for a given metric, between two given times, adds up to the value you expect.

{% highlight php %}
$now = time();
$data = $st->expectsGraphite()->metricSumIs('qa.api.http.200', 10000, $now - 300, $now);
{% endhighlight %}

_metricSumIs()_ takes four parameters:

* _$metric_ - the name of the Graphite metric to retrieve
* _$expectedTotal_ - the expected sum of the data
* _$startTime_ - the time in seconds (since the epoch) to start retrieving data from
* _$endTime_ - the time in seconds (since the epoch) to retrieve data until

If the data between _$startTime_ and _$endTime_ is less than, or greater than, _$expectedTotal_, then this test fails, and an exception is thrown.

## metricNeverExceeds()

Use _$st->expectsGraphite()->metricNeverExceeds()_ to ensure that data in Graphite for a given metric, between two given times, never has a value higher than a given amount.

{% highlight php %}
$now = time();
$data = $st->expectsGraphite()->metricNeverExceeds('qa.api.latency', 150, $now - 300, $now);
{% endhighlight %}

_metricNeverExceeds()_ takes four parameters:

* _$metric_ - the name of the Graphite metric to retrieve
* _$expectedMax_ - the expected maximum value of the data
* _$startTime_ - the time in seconds (since the epoch) to start retrieving data from
* _$endTime_ - the time in seconds (since the epoch) to retrieve data until

If any of the data between _$startTime_ and _$endTime_ is greater than _$expectedMax_, then this test fails, and an exception is thrown.

## metricAverageDoesntExceed()

Use _$st->expectsGraphite()->metricAverageDoesntExceed()_ to ensure that the average of all the data in Graphite for a given metric, between two given times, doesn't has a value higher than a given amount.

This test uses the [arithmetic mean](http://en.wikipedia.org/wiki/Arithmetic_mean) for the average of the data.

{% highlight php %}
$now = time();
$data = $st->expectsGraphite()->metricAverageDoesntExceed('qa.api.latency', 100, $now - 300, $now);
{% endhighlight %}

_metricAverageDoesntExceed()_ takes four parameters:

* _$metric_ - the name of the Graphite metric to retrieve
* _$expectedAverage_ - the expected maximum average value of the data
* _$startTime_ - the time in seconds (since the epoch) to start retrieving data from
* _$endTime_ - the time in seconds (since the epoch) to retrieve data until

If the average of the data between _$startTime_ and _$endTime_ is greater than _$expectedAverage_, then this test fails, and an exception is thrown.
