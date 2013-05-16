---
layout: modules-uuid
title: fromUuid()
prev: '<a href="../../modules/uuid/index.html">Prev: The UUID Module</a>'
next: '<a href="../../modules/uuid/expectsUuid.html">Next: expectsUuid()</a>'
---

# fromUuid()

_fromUuid()_ allows you to generate new UUID strings.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UuidDetermine_.

## Behaviour And Return Codes

Every action either returns a value on success, or NULL on failure.  None of these actions throw exceptions on failure.

## generateUuid()

Use _$st->fromUuid()->generateUuid()_ to generate a new UUID.

{% highlight php %}
$uuid = $st->fromUuid()->generateUuid();
{% endhighlight %}

where:

* _$uuid_ is a new Version 4 UUID string

You can use _[$st->expectsString()->isUuid()](../assertions/assertsString.html#isuuid)_ to test _$uuid_ and make sure that it is a valud UUID string:

{% highlight php %}
$uuid = $st->fromUuid()->generateUuid();
$st->expectsString($uuid)->isUuid();
{% endhighlight %}