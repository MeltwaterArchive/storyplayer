---
layout: v2/modules-assertions
title: String Assertions
prev: '<a href="../../modules/assertions/assertsObject.html">Prev: Object Assertions</a>'
next: '<a href="../../modules/browser/index.html">Next: The Browser Module</a>'
---

# String Assertions

_assertsString()_ allows you to test a PHP string, and to compare it against another PHP string.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\AssertsString_.

## doesNotEndWith()

Use `assertsString()->doesNotEndWith()` to make sure that a string does not end with a given string.

{% highlight php %}
$data = "filename.png";
expectsString($data)->doesNotEndWith('.gif');
{% endhighlight %}

## doesNotEqual()

Use `assertsString()->doesNotEqual()` to make sure that two strings are not the same.

{% highlight php %}
$expected = "filename.png";
$actual   = "filename.gif";
assertsString($actual)->doesNotEqual($expected);
{% endhighlight %}

See _[equals()](#equals)_ for a discussion of how this test works.

## doesNotMatchRegex()

Not implemented yet.

## doesNotStartWith()

Use `assertsString()->doesNotStartWith()` to make sure that a string does not start with a given string.

{% highlight php %}
$data = "theme2/filename.png";
expectsString($data)->doesNotStartWith("theme1");
{% endhighlight %}

## endsWith()

Use `assertsString()->endsWith()` to make sure that a string does end with the string that you expect it to.

{% highlight php %}
$data = "filename.png";
expectsString($data)->doesNotEndWith('.png');
{% endhighlight %}

## equals()

Use `assertsString()->equals()` to make sure that two strings contain the exact same values.

{% highlight php %}
$expected = "filename.png";
$actual   = "filename.png";
assertsString($actual)->equals($expected);
{% endhighlight %}

If the test fails, Storyplayer's output will contain a _[unified diff](http://en.wikipedia.org/wiki/Diff#Unified_format)_ showing the differences between the two strings.

## isEmpty()

Use `assertsString()->isEmpty()` to make sure that a string has no contents.

{% highlight php %}
$data = "";
assertsString($data)->isEmpty();
{% endhighlight %}

## isHash()

Use `assertsString()->isHash()` to make sure that a string is a hash value of some kind.

{% highlight php %}
$data = md5_sum("hello, world!");
assertsString($data)->isHash();
{% endhighlight %}

A hash is any valid hexadecimal string of even length.

## isNotEmpty()

Use `assertsString()->isNotEmpty()` to make sure that a string has contents.

{% highlight php %}
$data = "hello, world!";
assertsString($data)->isNotEmpty();
{% endhighlight %}

## isNull()

Use `assertsString()->isNull()` to make sure that the PHP variable is actually NULL, rather than a string.

{% highlight php %}
$data = null;
assertsString($data)->isNull()
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isString()](#isstring)_ instead of testing for NULL.

## isNotNull()

Use `assertsString()->isNotNull()` to make sure that the PHP variable is not NULL.

{% highlight php %}
$data = "hello, world!";
assertsString($data)->isNotNull();
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isString()](#isstring)_ instead of testing for NULL.

## isNotSameAs()

Use `assertsString()->isNotSameAs()` to make sure that two PHP strings are not references to each other.

{% highlight php %}
$data1 = "hello, world!";
$data2 = "hello, world";

assertsString($data1)->isNotSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[doesNotEqual()](#doesnotequal)_ instead.

## isNotValidJson()

Use `assertsString()->isNotValidJson()` to make sure that a string is not JSON-encoded data.

{% highlight php %}
$data = "hello, world!";
assertsString($data)->isNotValidJson();
{% endhighlight %}

## isSameAs()

Use `assertsString()->isSameAs()` to make sure that two PHP strings are references to each other.

{% highlight php %}
$data1 = "hello, world!";
$data2 = &$data1;

assertsString($data1)->isSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[equals()](#equals)_ instead.

## isString()

Use `assertsString()->isString()` to make sure that something really is a string.

{% highlight php %}
$data = "hello, world!";
assertsString($data)->isString();
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{%highlight php %}
$story->addPostTestInspection(function(StoryTeller $st) {
    // get the checkpoint
    $checkpoint = getCheckpoint();

    // make sure the checkpoint contains
    // the user's country
    assertsObject($checkpoint)->hasAttribute("country");
    assertsString($checkpoint->country)->isString();
});
{% endhighlight %}

## isUuid()

Use `assertsString()->isUuid()` to make sure that a string is a _[universally-unique identifier](http://en.wikipedia.org/wiki/Universally_unique_identifier)_ of some kind:

{% highlight php %}
$uuid = fromUuid()->generateUuid();
expectsString($uuid)->isUuid();
{% endhighlight %}

A UUID is a 32 character hexadecimal string (with four '-' characters at various places).  There are several different versions of the UUID-generation algorithm; at the moment, we don't test for compliance with any of those algorithms.

## isValidJson()

Use `assertsString()->isValidJson()` to make sure that a string contains valid JSON-encoded data.

{% highlight php %}
$response = usingHttp()->get("http://api.example.com/balance");
assertsString($response->body)->isValidJson();
{% endhighlight %}

## matchesRegex()

Not implemented yet.

## startsWith()

Use `assertsString()->startsWith()` to make sure that a string starts with a given string.

{% highlight php %}
$data = "theme2/filename.png";
expectsString($data)->startWith("theme2/");
{% endhighlight %}
