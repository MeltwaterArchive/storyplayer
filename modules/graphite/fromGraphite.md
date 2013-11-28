---
layout: modules-graphite
title: fromGraphite()
prev: '<a href="../../modules/graphite/index.html">Prev: The Graphite Module</a>'
next: '<a href="../../modules/graphite/expectsGraphite.html">Next: expectsGraphite()</a>'
---
# fromGraphite()

_fromGraphite()_ allows you to extract data from Graphite.

The source code for these actions can be found in the class _DataSift\Storyplayer\FromGraphite_.

## Behaviour And Return Codes

Every action returns either a value on success, or _NULL_ on failure. None of these actions throw exceptions on failure.

## getDataFor()

Use _$st->fromGraphite()->getDataFor()_ to retrieve data from Graphite for a given metric, between two given times.

{% highlight php %}
$now = time();
$data = $st->fromGraphite()->getDataFor('qa.ogre.memory.VmRSS', $now - 300, $now);
{% endhighlight %}

_getDataFor()_ takes three parameters:

* _$metric_ - the name of the Graphite metric to retrieve
* _$startTime_ - the time in seconds (since the epoch) to start retrieving data from
* _$endTime_ - the time in seconds (since the epoch) to retrieve data until