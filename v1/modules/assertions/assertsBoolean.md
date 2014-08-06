---
layout: v1/modules-assertions
title: Boolean Assertions
prev: '<a href="../../modules/assertions/assertsArray.html">Prev: Array Assertions</a>'
next: '<a href="../../modules/assertions/assertsDouble.html">Next: Double Assertions</a>'
---

# Boolean Assertions

_assertsBoolean()_ allows you to test a PHP boolean, and to compare it against another PHP boolean.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\AssertsBoolean_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## doesNotEqual()

Use `$st->assertsBoolean()->doesNotEqual()` to make sure that two booleans are not the same.

{% highlight php %}
$expected = true;
$actual   = false;
$st->assertsBoolean($actual)->doesNotEqual($expected);
{% endhighlight %}

See _[equals()](#equals)_ for a discussion of how this test works.

## equals()

Use `$st->assertsBoolean()->equals()` to make sure that two boolean values are the same.

{% highlight php %}
$expected = true;
$actual   = true;
$st->assertsBoolean($actual)->equals($expected);
{% endhighlight %}

If the test fails, Storyplayer's output will contain a _[unified diff](http://en.wikipedia.org/wiki/Diff#Unified_format)_ showing the differences between the two booleans.

## isBoolean()

Use `$st->assertsBoolean()->isBoolean()` to make sure that something really is a boolean.

{% highlight php %}
$data = true;
$st->assertsBoolean($data)->isBoolean();
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{%highlight php %}
$story->addPostTestInspection(function(StoryTeller $st) {
    // get the checkpoint
    $checkpoint = $st->getCheckpoint();

    // make sure the checkpoint contains
    // the state of the 'Remember Me' flag
    $st->assertsObject($checkpoint)->hasAttribute("remember_me");
    $st->assertsBoolean($checkpoint->remember_me)->isBoolean();
});
{% endhighlight %}

## isFalse()

Use `$st->assertsBoolean()->isFalse()` to make sure that the PHP variable is FALSE.

{% highlight php %}
$data = false;
$st->assertsBoolean($data)->isFalse();
{% endhighlight %}

See _[isTrue()](#istrue)_ for a discussion on what TRUE and FALSE means to this module.

## isNull()

Use `$st->assertsBoolean()->isNull()` to make sure that the PHP variable is actually NULL, rather than a boolean.

{% highlight php %}
$data = null;
$st->assertsBoolean($data)->isNull()
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isBoolean()](#isboolean)_ instead of testing for NULL.

## isNotNull()

Use `$st->assertsBoolean()->isNotNull()` to make sure that the PHP variable is not NULL.

{% highlight php %}
$data = true;
$st->assertsBoolean($data)->isNotNull();
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isBoolean()](#isboolean)_ instead of testing for NULL.

## isNotSameAs()

Use `$st->assertsBoolean()->isNotSameAs()` to make sure that two PHP booleans are not references to each other.

{% highlight php %}
$data1 = true;
$data2 = true;

$st->assertsBoolean($data1)->isNotSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[doesNotEqual()](#doesnotequal)_ instead.

## isSameAs()

Use `$st->assertsBoolean()->isSameAs()` to make sure that two PHP booleans are references to each other.

{% highlight php %}
$data1 = true;
$data2 = &$data1;

$st->assertsBoolean($data1)->isSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[equals()](#equals)_ instead.
