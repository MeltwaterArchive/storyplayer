---
layout: v2/modules-uuid
title: The UUID Module
prev: '<a href="../../modules/testenvironment/index.html">Prev: The TestEnvironment Module</a>'
next: '<a href="../../modules/uuid/fromUuid.html">Next: fromUuid()</a>'
---

# The UUID Module

The __UUID__ module allows you to generate new (and possibly salted) hashes to the length that you request, for use in your tests.

The source code for this Prose module can be found in these PHP classes:

* DataSift\Storyplayer\Prose\FromUuid

## What Are UUIDs?

UUIDs are _[universally-unique identifiers](http://en.wikipedia.org/wiki/Universally_unique_identifier)_; hexadecimal strings that can be generated locally with a very low likelihood that two computers will ever generate the same string.  The idea is that distributed systems can generate ID strings without having to rely on a centralised co-ordination service of any kind.

There are several different versions of the UUID algorithm in existence.  At this moment, Storyplayer only supports Version 4 (completely random UUIDs); support for other types might be added in the future if demand is there.

## Dependencies

You need to install [PECL's uuid extension](http://pecl.php.net/package/uuid):

{% highlight bash %}
sudo pecl install uuid
php -i | grep -i uuid
{% endhighlight %}

If the install is successful, you should see information about the UUID extension appear when you run the `grep` statement above.

## Using The UUID Module

The basic format of an action is:

{% highlight php %}
$st->MODULE()->ACTION()
{% endhighlight %}

where __module__ is one of:

* _[fromUuid()](fromUuid.html)_ - generate new UUIDs
* _[expectsUuid()](expectsUuid.html)_ - test UUIDs

and __action__ is one of the documented actions available from that module.