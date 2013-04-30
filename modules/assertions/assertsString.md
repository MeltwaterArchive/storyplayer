---
layout: modules-assertions
title: String Assertions
prev: '<a href="../../modules/assertions/assertsObject.html">Prev: Object Assertions</a>'
next: '<a href="../../modules/browser/index.html">Next: The Browser Module</a>'
---

# String Assertions

_assertsString()_ allows you to test a PHP string, and to compare it against another PHP string.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\StringExpects_.

## doesNotEndWith()

Use _$st->assertsString()->doesNotEndWith()_ to make sure that a string does not end with a given string.

{% highlight php %}
$data = "filename.png";
$st->expectsString($data)->doesNotEndWith('.gif');
{% endhighlight %}

## doesNotEqual()

Use _$st->assertsString()->doesNotEqual()_ to make sure that two strings are not the same.

{% highlight php %}
$expected = "filename.png";
$actual   = "filename.gif";
$st->assertsString($actual)->doesNotEqual($expected);
{% endhighlight %}

See _[equals()](#equals)_ for a discussion of how this test works.

## doesNotMatchRegex()

Not implemented yet.

## doesNotStartWith()

Use _$st->assertsString()->doesNotStartWith()_ to make sure that a string does not start with a given string.

{% highlight php %}
$data = "theme2/filename.png";
$st->expectsString($data)->doesNotStartWith("theme1");
{% endhighlight %}

## endsWith()

Use _$st->assertsString()->endsWith()_ to make sure that a string does end with the string that you expect it to.

{% highlight php %}
$data = "filename.png";
$st->expectsString($data)->doesNotEndWith('.png');
{% endhighlight %}

## equals()

Use _$st->assertsString()->equals()_ to make sure that two strings contain the exact same values.

{% highlight php %}
$expected = "filename.png";
$actual   = "filename.png";
$st->assertsString($actual)->equals($expected);
{% endhighlight %}

If the test fails, Storyplayer's output will contain a _[unified diff](http://en.wikipedia.org/wiki/Diff#Unified_format)_ showing the differences between the two strings.

## isEmpty()

Use _$st->assertsString()->isEmpty()_ to make sure that a string has no contents.

{% highlight php %}
$data = "";
$st->assertsString($data)->isEmpty();
{% endhighlight %}

## isHash()

Use _$st->assertsString()->isHash()_ to make sure that a string is a hash value of some kind.

{% highlight php %}
$data = md5_sum("hello, world!");
$st->assertsString($data)->isHash();
{% endhighlight %}

A hash is any valid hexadecimal string of even length.

## isNotEmpty()

Use _$st->assertsString()->isNotEmpty()_ to make sure that a string has contents.

{% highlight php %}
$data = "hello, world!";
$st->assertsString($data)->isNotEmpty();
{% endhighlight %}

## isNull()

Use _$st->assertsString()->isNull()_ to make sure that the PHP variable is actually NULL, rather than a string.

{% highlight php %}
$data = null;
$st->assertsString($data)->isNull()
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isString()](#isstring)_ instead of testing for NULL.

## isNotNull()

Use _$st->assertsString()->isNotNull()_ to make sure that the PHP variable is not NULL.

{% highlight php %}
$data = "hello, world!";
$st->assertsString($data)->isNotNull();
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isString()](#isstring)_ instead of testing for NULL.

## isNotSameAs()

Use _$st->assertsString()->isNotSameAs()_ to make sure that two PHP strings are not references to each other.

{% highlight php %}
$data1 = "hello, world!";
$data2 = "hello, world";

$st->assertsString($data1)->isNotSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[doesNotEqual()](#doesnotequal)_ instead.

## isNotValidJson()

Use _$st->assertsString()->isNotValidJson()_ to make sure that a string is not JSON-encoded data.

{% highlight php %}
$data = "hello, world!";
$st->assertsString($data)->isNotValidJson();
{% endhighlight %}

## isSameAs()

Use _$st->assertsString()->isSameAs()_ to make sure that two PHP strings are references to each other.

{% highlight php %}
$data1 = "hello, world!";
$data2 = &$data1;

$st->assertsString($data1)->isSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[equals()](#equals)_ instead.

## isString()

Use _$st->assertsString()->isString()_ to make sure that something really is a string.

{% highlight php %}
$data = "hello, world!";
$st->assertsString($data)->isString();
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{%highlight php %}
$story->setPostTestInspection(function(StoryTeller $st) {
    // get the checkpoint
    $checkpoint = $st->getCheckpoint();

    // make sure the checkpoint contains
    // the user's country
    $st->assertsObject($checkpoint)->hasAttribute("country");
    $st->assertsString($checkpoint->country)->isString();
});
{% endhighlight %}

## isValidJson()

Use _$st->assertsString()->isValidJson()_ to make sure that a string contains valid JSON-encoded data.

{% highlight php %}
$response = $st->usingHttp()->get("http://api.example.com/balance");
$st->assertsString($response->body)->isValidJson();
{% endhighlight %}

## matchesRegex()

Not implemented yet.

## startsWith()

Use _$st->assertsString()->startsWith()_ to make sure that a string starts with a given string.

{% highlight php %}
$data = "theme2/filename.png";
$st->expectsString($data)->startWith("theme2/");
{% endhighlight %}
