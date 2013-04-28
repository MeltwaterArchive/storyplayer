---
layout: modules-assertions
title: Object Assertions
prev: '<a href="../../modules/assertions/assertsInteger.html">Prev: Integer Assertions</a>'
next: '<a href="../../modules/assertions/assertsString.html">Next: String Assertions</a>'
---

# Object Assertions

_assertsObject()_ allows you to test a PHP object and its contents, and to compare it against another PHP object.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\ObjectExpects_.

## doesNotEqual()

Use _$st->assertsObject()->doesNotEqual()_ to make sure that two objects are not the same.

{% highlight php %}
$expectedObject = (object)array("count" => 1);
$actualObject   = (object)array("count" => 2);
$st->assertsObject($actualObject)->doesNotEqual($expectedObject);
{% endhighlight %}

See _[equals()](#equals)_ for a discussion of how this test works.

## doesNotHaveAttribute()

Use _$st->assertsObject()->doesNotHaveAttribute()_ to make sure that an object does not have an attribute it should not have.

{% highlight php %}
$obj = (object)array("count" => 1);
$st->assertsObject($obj)->doesNotHaveAttribute("length");
{% endhighlight %}

## doesNotHaveMethod()

Use _$st->assertsObejct()->doesNotHaveMethod()_ to make sure that an object does not have a particular method defined.

{% highlight php %}
$obj = (object)array("count" => 1);
$st->assertsObject($obj)->doesNotHaveMethod("__construct");
{% endhighlight %}

This has been added for completeness (it's part of Stone's _ObjectComparitor_ that we use for comparing objects); you'll probably never need to use it in a story.

## equals()

Use _$st->assertsObject()->equals()_ to make sure that two objects contain the exact same values.

{% highlight php %}
$expectedObject = (object)array("count" => 1);
$actualObject   = (object)array("count" => 1);
$st->assertsObject($actualObject)->equals($expectedObject);
{% endhighlight %}

This test does successfully cope with objects that have complex attributes, such as arrays and other objects.

If the test fails, Storyplayer's output will contain a _[unified diff](http://en.wikipedia.org/wiki/Diff#Unified_format)_ showing the differences between the two objects.

## hasAttribute()

Use _$st->assertsObject()->hasAttribute()_ to make sure that an object has an attribute that you expect to exist.

{% highlight php %}
$obj = (object)array("count" => 1);
$st->assertsObject($obj)->hasAttribute("count");
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{%highlight php %}
$story->setPostTestInspection(function(StoryTeller $st) {
    // get the checkpoint
    $checkpoint = $st->getCheckpoint();

    // make sure the checkpoint contains
    // the list of countries
    $st->assertsObject($checkpoint)->hasAttribute("countries");
    $st->assertsArray($checkpoint->countries)->isArray();
});
{% endhighlight %}

## hasMethod()

Use _$st->assertsObject()->hasMethod()_ to make sure than an object has a method that you expect to exist.

{% highlight php %}
$st->expectsObject($st)->hasMethod('expectsObject');
{% endhighlight %}

This has been added for completeness (it's part of Stone's _ObjectComparitor_ that we use for comparing objects); you'll probably never need to use it in a story.

## isEmpty()

Use _$st->assertsObject()->isEmpty()_ to make sure that an object has no attributes.

{% highlight php %}
$data = (object)array();
$st->assertsObject($data)->isEmpty();
{% endhighlight %}

## isNotEmpty()

Use _$st->assertsObject()->isNotEmpty()_ to make sure that an object has attributes.

{% highlight php %}
$data = (object)array(1,2,3,4);
$st->assertsObject($data)->isNotEmpty();
{% endhighlight %}

## isInstanceOf()

Use _$st->assertsObject()->isInstanceOf()_ to make sure that an object is an instance of the class that you're expecting.

{% highlight php %}
$st->assertsObject($st)->isInstanceOf("DataSift\Storyplayer\PlayerLib\Storyteller");
{% endhighlight %}

This has been added for completeness (it's part of Stone's _ObjectComparitor_ that we use for comparing objects); you'll probably never need to use it in a story.

## isNull()

Use _$st->assertsObject()->isNull()_ to make sure that the PHP variable is actually NULL, rather than an object.

{% highlight php %}
$data = null;
$st->assertsObject($data)->isNull()
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isObject()](#isobject)_ instead of testing for NULL.

## isNotInstanceOf()

Use _$st->assertsObject()->isNotInstanceOf()_ to make sure that an object is not an instance of the class that you're expecting.

{% highlight php %}
$st->assertsObject($st)->isNotInstanceOf("stdClass");
{% endhighlight %}

This has been added for completeness (it's part of Stone's _ObjectComparitor_ that we use for comparing objects); you'll probably never need to use it in a story.

## isNotNull()

Use _$st->assertsObject()->isNotNull()_ to make sure that the PHP variable is not NULL.

{% highlight php %}
$data = (object)array(1,2,3,4);
$st->assertsObject($data)->isNotNull();
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isObject()](#isobject)_ instead of testing for NULL.

## isNotSameAs()

Use _$st->assertsObject()->isNotSameAs()_ to make sure that two PHP objects are different PHP variables.

{% highlight php %}
$data1 = (object)array(1,2,3,4);
$data2 = (object)array(1,2,3,4);

$st->assertsObject($data1)->isNotSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[doesNotEqual()](#doesnotequal)_ instead.

## isObject()

Use _$st->assertsObject()->isObject()_ to make sure that something really is an object.

{% highlight php %}
$data = (object)array(1,2,3,4);
$st->assertsObject($data)->isObject();
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{%highlight php %}
$story->setPostTestInspection(function(StoryTeller $st) {
    // get the checkpoint
    $checkpoint = $st->getCheckpoint();

    // make sure the checkpoint contains
    // the list of countries
    $st->assertsObject($checkpoint)->hasAttribute("countries");
    $st->assertsArray($checkpoint->countries)->isArray();
});
{% endhighlight %}

## isSameAs()

Use _$st->assertsObject()->isSameAs()_ to make sure that two PHP objects are references to each other, or are in fact the same object.

{% highlight php %}
$data1 = (object)array(1,2,3,4);
$data2 = &$data1;

$st->assertsObject($data1)->isSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[equals()](#equals)_ instead.