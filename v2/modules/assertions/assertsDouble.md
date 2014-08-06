---
layout: v2/modules-assertions
title: Double Assertions
prev: '<a href="../../modules/assertions/assertsBoolean.html">Prev: Boolean Assertions</a>'
next: '<a href="../../modules/assertions/assertsInteger.html">Next: Integer Assertions</a>'
---

# Double Assertions

_assertsDouble()_ allows you to test a PHP double or float, and to compare it against another PHP double or float.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\AssertsDouble_.

## doesNotEqual()

Use `$st->assertsDouble()->doesNotEqual()` to make sure that two floating point numbers are not the same.

{% highlight php %}
$expected = 1.1;
$actual   = 1.0;
$st->assertsDouble($actual)->doesNotEqual($expected);
{% endhighlight %}

See _[equals()](#equals)_ for a discussion of how this test works.

## equals()

Use `$st->assertsDouble()->equals()` to make sure that two floating point numbers are the same.

{% highlight php %}
$expected = 1.1;
$actual   = 1.1;
$st->assertsDouble($actual)->equals($expected);
{% endhighlight %}

If the test fails, Storyplayer's output will show the differences between the two numbers.

## isDouble()

Use `$st->assertsDouble()->isDouble()` to make sure that something really is a floating point number.

{% highlight php %}
$data = 1.1;
$st->assertsDouble($data)->isDouble();
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{% highlight php %}
$story->addPostTestInspection(function(StoryTeller $st) {
    // get the checkpoint
    $checkpoint = $st->getCheckpoint();

    // make sure the checkpoint contains
    // the final balance
    $st->assertsObject($checkpoint)->hasAttribute("balance");
    $st->assertsDouble($checkpoint->balance)->isDouble();
});
{% endhighlight %}

## isEmpty()

Use `$st->assertsDouble()->isEmpty()` to make sure that a variable is empty.

{% highlight php %}
$data = 0;
$st->assertsDouble($data)->isEmpty();
{% endhighlight %}

## isGreaterThan()

Use `$st->assertsDouble()->isGreaterThan()` to make sure that a floating point number is larger than a value you provide.

{% highlight php %}
$data = 1.1;
$st->assertsDouble($data)->isGreaterThan(1.0);
{% endhighlight %}

## isGreaterThanOrEqualTo()

Use `$st->assertsDouble()->isGreaterThan()` to make sure that a floating point number is at least a value you provide.

{% highlight php %}
$data = 1.1;
$st->assertsDouble($data)->isGreaterThanOrEqualTo(1.1);
{% endhighlight %}

## isLessThan()

Use `$st->assertsDouble()->isLessThan()` to make sure that a floating point number is smaller than a value you provide.

{% highlight php %}
$data = 1.0;
$st->assertsDouble($data)->isLessThan(1.1);
{% endhighlight %}

## isLessThanOrEqualTo()

Use `$st->assertsDouble()->isLessThanOrEqualTo()` to make sure that a floating point number is no larger than a value you provide.

{% highlight php %}
$data = 1.1;
$st->assertsDouble($data)->isLessThanOrEqualTo(1.1);
{% endhighlight %}

## isNotEmpty()

Use `$st->assertsDouble()->isNotEmpty()` to make sure that a floating point number is not empty.

{% highlight php %}
$data = 1.1;
$st->assertsDouble($data)->isNotEmpty();
{% endhighlight %}

## isNull()

Use `$st->assertsDouble()->isNull()` to make sure that the PHP variable is actually NULL, rather than a floating point number.

{% highlight php %}
$data = null;
$st->assertsDouble($data)->isNull()
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isDouble()](#isdouble)_ instead of testing for NULL.

## isNotNull()

Use `$st->assertsDouble()->isNotNull()` to make sure that the PHP variable is not NULL.

{% highlight php %}
$data = 1.1;
$st->assertsDouble($data)->isNotNull();
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isDouble()](#isdouble)_ instead of testing for NULL.

## isNotSameAs()

Use `$st->assertsDouble()->isNotSameAs()` to make sure that two PHP floating point numbers are not references to each other.

{% highlight php %}
$data1 = 1.1;
$data2 = 1.1;

$st->assertsDouble($data1)->isNotSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[doesNotEqual()](#doesnotequal)_ instead.

## isSameAs()

Use `$st->assertsDouble()->isSameAs()` to make sure that two PHP floating point numbers are references to each other.

{% highlight php %}
$data1 = 1.1;
$data2 = &$data1;

$st->assertsDouble($data1)->isSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[equals()](#equals)_ instead.
