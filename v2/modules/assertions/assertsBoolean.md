---
layout: v2/modules-assertions
title: Boolean Assertions
prev: '<a href="../../modules/assertions/assertsArray.html">Prev: Array Assertions</a>'
next: '<a href="../../modules/assertions/assertsDouble.html">Next: Double Assertions</a>'
---

# Boolean Assertions

_assertsBoolean()_ allows you to test a PHP boolean, and to compare it against another PHP boolean.

The source code for these actions can be found in the class `Prose\AssertsBoolean`.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## doesNotEqual()

Use `assertsBoolean()->doesNotEqual()` to make sure that two booleans are not the same.

{% highlight php startinline %}
$expected = true;
$actual   = false;
assertsBoolean($actual)->doesNotEqual($expected);
{% endhighlight %}

See _[equals()](#equals)_ for a discussion of how this test works.

## equals()

Use `assertsBoolean()->equals()` to make sure that two boolean values are the same.

{% highlight php startinline %}
$expected = true;
$actual   = true;
assertsBoolean($actual)->equals($expected);
{% endhighlight %}

If the test fails, Storyplayer's output will contain a _[unified diff](http://en.wikipedia.org/wiki/Diff#Unified_format)_ showing the differences between the two booleans.

## isBoolean()

Use `assertsBoolean()->isBoolean()` to make sure that something really is a boolean.

{% highlight php startinline %}
$data = true;
assertsBoolean($data)->isBoolean();
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{% highlight php startinline %}
$story->addAction(function() {
    // get the checkpoint
    $checkpoint = getCheckpoint();

    // set the 'Remember Me' flag
    $checkpoint->remember_me = true;
});

$story->addPostTestInspection(function() {
    // get the checkpoint
    $checkpoint = getCheckpoint();

    // make sure the checkpoint contains
    // the state of the 'Remember Me' flag
    assertsObject($checkpoint)->hasAttribute("remember_me");
    assertsBoolean($checkpoint->remember_me)->isBoolean();
});
{% endhighlight %}

## isFalse()

Use `assertsBoolean()->isFalse()` to make sure that the PHP variable is `FALSE`.

{% highlight php startinline %}
$data = false;
assertsBoolean($data)->isFalse();
{% endhighlight %}

See _[isTrue()](#istrue)_ for a discussion on what `TRUE` and `FALSE` means to this module.

## isNull()

Use `assertsBoolean()->isNull()` to make sure that the PHP variable is actually `NULL`, rather than a boolean.

{% highlight php startinline %}
$data = null;
assertsBoolean($data)->isNull()
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isBoolean()](#isboolean)_ instead of testing for `NULL`.

## isNotNull()

Use `assertsBoolean()->isNotNull()` to make sure that the PHP variable is not NULL.

{% highlight php startinline %}
$data = true;
assertsBoolean($data)->isNotNull();
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isBoolean()](#isboolean)_ instead of testing for `NULL`.
