---
layout: v2/modules-assertions
title: Array Assertions
prev: '<a href="../../modules/assertions/index.html">Prev: The Assertions Module</a>'
next: '<a href="../../modules/assertions/assertsBoolean.html">Next: Boolean Assertions</a>'
---

# Array Assertions

_assertsArray()_ allows you to test a PHP array, and to compare it against another PHP array.

The source code for these actions can be found in the class `Prose\AssertsArray`.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## containsValue()

Use `assertsArray()->containsValue()` to make sure that the array contains the value that you expect it to.

{% highlight php startinline %}
$data = [ 1,2,3,4 ];
assertsArray($data)->containsValue(1);
{% endhighlight %}

This test does not search inside multi-dimensional arrays.  For example, the following test will fail:

{% highlight php startinline %}
$data =  [ [1], 2, 3, 4 ];
assertsArray($data)->containsValue(1);
// this line never reached - the test above throws an exception
{% endhighlight %}

## doesNotContainValue()

Use `assertsArray()->doesNotContainValue()` to make sure that the array does not contain the value that you do not expect it to.

{% highlight php startinline %}
$data = [ 1,2,3,4 ];
assertsArray($data)->doesNotContainsValue(1);
{% endhighlight %}

See _[containsValue()](#containsvalue)_ for a discussion of the limits of this test.

## doesNotEqual()

Use `assertsArray()->doesNotEqual()` to make sure that two arrays are not the same.

{% highlight php startinline %}
$expectedData = [ 1,2,3,4 ];
$actualData   = [ 4,5,6,7 ];
assertsArray($actualArray)->doesNotEqual($expectedArray);
{% endhighlight %}

See _[equals()](#equals)_ for a discussion of how this test works.

## doesNotHaveKey()

Use `assertsArray()->doesNotHaveKey()` to make sure that an array does not contain the key that you do not expect it to.

{% highlight php startinline %}
$data = [ "first_name" => "Stuart", "surname" => "Herbert" ];
assertsArray($data)->doesNotHaveKey("middle_name");
{% endhighlight %}

See _[hasKey()](#haskey)_ for a discussion of the limits of this test.

## equals()

Use `assertsArray()->equals()` to make sure that two arrays contain the exact same values.

{% highlight php startinline %}
$expectedArray = [ 1,2,3,4 ];
$actualArray = [ 1,2,3,4 ];
assertsArray($actualArray)->equals($expectedArray);
{% endhighlight %}

This test does successfully cope with multidimentional arrays.

If the test fails, Storyplayer's output will contain a _[unified diff](http://en.wikipedia.org/wiki/Diff#Unified_format)_ showing the differences between the two arrays.

## hasKey()

Use `assertsArray()->hasKey()` to make sure that an array contains the key that you expect it to.

{% highlight php startinline %}
$data = [ "first_name" => "Stuart", "surname" => "Herbert" ];
assertsArray($data)->hasKey("first_name");
{% endhighlight %}

This test does not search inside multi-dimensional arrays.  For example, the following test will fail:

{% highlight php startinline %}
$data = [ "address" => [ "line1" => "Enterprise Centre" ] ];

// this test succeeds
assertsArray($data)->hasKey("address");

// this test fails
assertsArray($data)->hasKey("line1");
{% endhighlight %}

## hasLength()

Use `assertsArray()->hasLength()` to make sure that an array has the number of entries that you expect it to.

{% highlight php startinline %}
// single-dimensional array example
$data = [ 1,2,3,4 ];
assertsArray($data)->hasLength(4);

// multi-dimensional array example
$data = [
    "address" => [
        "line1" => "Enterprise Centre",
        "line2" => "University of Reading"
    ]
];
assertsArray($data)->hasLength(1);
{% endhighlight %}

## isArray()

Use `assertsArray()->isArray()` to make sure that something really is an array.

{% highlight php startinline %}
$data = [ 1,2,3,4 ];
assertsArray($data)->isArray();
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{%highlight php startinline %}
$story->addPostTestInspection(function(StoryTeller $st) {
    // get the checkpoint
    $checkpoint = getCheckpoint();

    // make sure the checkpoint contains
    // the list of countries
    assertsObject($checkpoint)->hasAttribute("countries");
    assertsArray($checkpoint->countries)->isArray();
});
{% endhighlight %}

## isEmpty()

Use `assertsArray()->isEmpty()` to make sure that an array has no contents.

{% highlight php startinline %}
$data = [ ];
assertsArray($data)->isEmpty();
{% endhighlight %}

## isNotEmpty()

Use `assertsArray()->isNotEmpty()` to make sure that an array has contents.

{% highlight php startinline %}
$data = [ 1,2,3,4 ];
assertsArray($data)->isNotEmpty();
{% endhighlight %}

## isNull()

Use `assertsArray()->isNull()` to make sure that the PHP variable is actually NULL, rather than an array.

{% highlight php startinline %}
$data = null;
assertsArray($data)->isNull()
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isArray()](#isarray)_ instead of testing for NULL.

## isNotNull()

Use `assertsArray()->isNotNull()` to make sure that the PHP variable is not NULL.

{% highlight php startinline %}
$data = [ 1,2,3,4 ];
assertsArray($data)->isNotNull();
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isArray()](#isarray)_ instead of testing for NULL.

## isSameLengthAs()

Use `assertsArray()->isSameLengthAs()` to make sure that two PHP arrays are the same length.

{% highlight php startinline %}
$data1 = [ 1,2,3,4 ];
$data2 = [ 5,6,7,8 ];

assertsArray($data1)->isSameLengthAs($data2);
{% endhighlight %}