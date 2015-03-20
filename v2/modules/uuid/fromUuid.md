---
layout: v2/modules-uuid
title: fromUuid()
prev: '<a href="../../modules/uuid/index.html">Prev: The UUID Module</a>'
next: '<a href="../../modules/uuid/expectsUuid.html">Next: expectsUuid()</a>'
updated_for_v2: true
---

# fromUuid()

_fromUuid()_ allows you to generate new UUID strings.

The source code for these actions can be found in the class `Prose\FromUuid`.

## Behaviour And Return Codes

Every action either returns a value on success, or `NULL` on failure.  None of these actions throw exceptions on failure.

## generateUuid()

Use `fromUuid()->generateUuid()` to generate a new UUID.

{% highlight php startinline %}
$uuid = fromUuid()->generateUuid();
{% endhighlight %}

where:

* `$uuid` gets set to a new Version 4 UUID string

You can use _[assertsString()->isUuid()](../assertions/assertsString.html#isuuid)_ to test `$uuid` and make sure that it is a valid UUID string:

{% highlight php startinline %}
$uuid = fromUuid()->generateUuid();
assertsString($uuid)->isUuid();
{% endhighlight %}