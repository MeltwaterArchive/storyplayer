---
layout: v1/modules-assertions
title: Array Assertions
prev: '<a href="../../modules/assertions/index.html">Prev: The Assertions Module</a>'
next: '<a href="../../modules/assertions/assertsBoolean.html">Next: Boolean Assertions</a>'
---

# Array Assertions

_assertsArray()_ allows you to test a PHP array, and to compare it against another PHP array.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\AssertsArray_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## containsValue()

Use `$st->assertsArray()->containsValue()` to make sure that the array contains the value that you expect it to.

{% highlight php %}
$expectedArray = array(1,2,3,4);
$st->assertsArray($expectedArray)->containsValue(1);
{% endhighlight %}

This test does not search inside multi-dimensional arrays.  For example, the following test will fail:

{% highlight php %}
$expectedArray = array(array(1), 2, 3, 4);
$st->assertsArray($expectedArray)->containsValue(1);
// this line never reached - the test above throws an exception
{% endhighlight %}

## doesNotContainValue()

Use `$st->assertsArray()->doesNotContainValue()` to make sure that the array does not contain the value that you do not expect it to.

{% highlight php %}
$expectedArray = array(1,2,3,4);
$st->assertsArray($expectedArray)->doesNotContainsValue(1);
{% endhighlight %}

See _[containsValue()](#containsvalue)_ for a discussion of the limits of this test.

## doesNotEqual()

Use `$st->assertsArray()->doesNotEqual()` to make sure that two arrays are not the same.

{% highlight php %}
$expectedArray = array(1,2,3,4);
$actualArray   = array(4,5,6,7);
$st->assertsArray($expectedArray)->doesNotEqual($actualArray);
{% endhighlight %}

See _[equals()](#equals)_ for a discussion of how this test works.

## doesNotHaveKey()

Use `$st->assertsArray()->doesNotHaveKey()` to make sure that an array does not contain the key that you do not expect it to.

{% highlight php %}
$data = array("first_name" => "Stuart", "surname" => "Herbert");
$st->assertsArray($data)->doesNotHaveKey("middle_name");
{% endhighlight %}

See _[hasKey()](#haskey)_ for a discussion of the limits of this test.

## equals()

Use `$st->assertsArray()->equals()` to make sure that two arrays contain the exact same values.

{% highlight php %}
$expectedArray = array(1,2,3,4);
$actualArray = array(1,2,3,4);
$st->assertsArray($actualArray)->equals($expectedArray);
{% endhighlight %}

This test does successfully cope with multidimentional arrays.

If the test fails, Storyplayer's output will contain a _[unified diff](http://en.wikipedia.org/wiki/Diff#Unified_format)_ showing the differences between the two arrays.

## hasKey()

Use `$st->assertsArray()->hasKey()` to make sure that an array contains the key that you expect it to.

{% highlight php %}
$data = array("first_name" => "Stuart", "surname" => "Herbert");
$st->assertsArray($data)->hasKey("first_name");
{% endhighlight %}

This test does not search inside multi-dimensional arrays.  For example, the following test will fail:

{% highlight php %}
$data = array("address" => array("line1" => "Enterprise Centre"));

// this test succeeds
$st->assertsArray($data)->hasKey("address");

// this test fails
$st->assertsArray($data)->hasKey("line1");
{% endhighlight %}

## hasLength()

Use `$st->assertsArray()->hasLength()` to make sure that an array has the number of entries that you expect it to.

{% highlight php %}
// single-dimensional array example
$data = array(1,2,3,4);
$st->assertsArray($data)->hasLength(4);

// multi-dimensional array example
$data = array(
    "address" => array(
        "line1" => "Enterprise Centre",
        "line2" => "University of Reading"
    )
);
$st->assertsArray($data)->hasLength(1);
{% endhighlight %}

## isArray()

Use `$st->assertsArray()->isArray()` to make sure that something really is an array.

{% highlight php %}
$data = array(1,2,3,4);
$st->assertsArray($data)->isArray();
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{%highlight php %}
$story->addPostTestInspection(function(StoryTeller $st) {
    // get the checkpoint
    $checkpoint = $st->getCheckpoint();

    // make sure the checkpoint contains
    // the list of countries
    $st->assertsObject($checkpoint)->hasAttribute("countries");
    $st->assertsArray($checkpoint->countries)->isArray();
});
{% endhighlight %}

## isEmpty()

Use `$st->assertsArray()->isEmpty()` to make sure that an array has no contents.

{% highlight php %}
$data = array();
$st->assertsArray($data)->isEmpty();
{% endhighlight %}

## isNotEmpty()

Use `$st->assertsArray()->isNotEmpty()` to make sure that an array has contents.

{% highlight php %}
$data = array(1,2,3,4);
$st->assertsArray($data)->isNotEmpty();
{% endhighlight %}

## isNull()

Use `$st->assertsArray()->isNull()` to make sure that the PHP variable is actually NULL, rather than an array.

{% highlight php %}
$data = null;
$st->assertsArray($data)->isNull()
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isArray()](#isarray)_ instead of testing for NULL.

## isNotNull()

Use `$st->assertsArray()->isNotNull()` to make sure that the PHP variable is not NULL.

{% highlight php %}
$data = array(1,2,3,4);
$st->assertsArray($data)->isNotNull();
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isArray()](#isarray)_ instead of testing for NULL.

## isNotSameAs()

Use `$st->assertsArray()->isNotSameAs()` to make sure that two PHP arrays are not references to each other.

{% highlight php %}
$data1 = array(1,2,3,4);
$data2 = array(1,2,3,4);

$st->assertsArray($data1)->isNotSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[doesNotEqual()](#doesnotequal)_ instead.

## isSameAs()

Use `$st->assertsArray()->isSameAs()` to make sure that two PHP arrays are references to each other.

{% highlight php %}
$data1 = array(1,2,3,4);
$data2 = &$data1;

$st->assertsArray($data1)->isSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[equals()](#equals)_ instead.

## isSameLengthAs()

Use `$st->assertsArray()->isSameLengthAs()` to make sure that two PHP arrays are the same length.

{% highlight php %}
$data1 = array(1,2,3,4);
$data2 = array(5,6,7,8);

$st->assertsArray($data1)->isSameLengthAs($data2);
{% endhighlight %}

This has been added for completeness.