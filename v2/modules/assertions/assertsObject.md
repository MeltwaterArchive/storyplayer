---
layout: v2/modules-assertions
title: Object Assertions
prev: '<a href="../../modules/assertions/assertsInteger.html">Prev: Integer Assertions</a>'
next: '<a href="../../modules/assertions/assertsString.html">Next: String Assertions</a>'
updated_for_v2: true
---

# Object Assertions

_assertsObject()_ allows you to test a PHP object and its contents, and to compare it against another PHP object.

The source code for these actions can be found in the class `Prose\AssertsObject`.

## Support For IteratorAggregate

In PHP, a developer can create PHP objects that appear to have attributes:

{% highlight php startinline %}
$obj->attribute1 = true;
echo (isset($obj->attribute1)) . PHP_EOL;
{% endhighlight %}

but are actually faking these attributes using [PHP's magic methods](http://php.net/manual/en/language.oop5.magic.php). Storyplayer's own `Checkpoint` is an example of this.

If the class implements support for [PHP's IteratorAggregate interface](http://php.net/manual/en/class.iteratoraggregate.php), we can check these objects too.

## doesNotEqual()

Use `assertsObject()->doesNotEqual()` to make sure that two objects are not the same.

{% highlight php startinline %}
$expectedData = (object)[ "count" => 1 ];
$actualData   = (object)[ "count" => 2 ];
assertsObject($actualData)->doesNotEqual($expectedData);
{% endhighlight %}

See _[equals()](#equals)_ for a discussion of how this test works.

## doesNotHaveAttribute()

Use `assertsObject()->doesNotHaveAttribute()` to make sure that an object does not have an attribute it should not have.

{% highlight php startinline %}
$obj = (object)[ "count" => 1 ];
assertsObject($obj)->doesNotHaveAttribute("length");
{% endhighlight %}

## doesNotHaveMethod()

Use `assertsObejct()->doesNotHaveMethod()` to make sure that an object does not have a particular method defined.

{% highlight php startinline %}
$obj = (object)[ "count" => 1 ];
assertsObject($obj)->doesNotHaveMethod("getCount");
{% endhighlight %}

This has been added for completeness (it's part of Stone's _ObjectComparitor_ that we use for comparing objects); you'll probably never need to use it in a story.

## equals()

Use `assertsObject()->equals()` to make sure that two objects contain the exact same values.

{% highlight php startinline %}
$expectedData = (object)[ "count" => 1 ];
$actualData   = (object)[ "count" => 1 ];
assertsObject($actualData)->equals($expectedData);
{% endhighlight %}

This test does successfully cope with objects that have complex attributes, such as arrays and other objects.

If the test fails, Storyplayer's output will contain a _[unified diff](http://en.wikipedia.org/wiki/Diff#Unified_format)_ showing the differences between the two objects.

## hasAttribute()

Use `assertsObject()->hasAttribute()` to make sure that an object has an attribute that you expect to exist.

{% highlight php startinline %}
$obj = (object)[ "count" => 1 ];
assertsObject($obj)->hasAttribute("count");
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{% highlight php startinline %}
$story->addPostTestInspection(function() {
    // get the checkpoint
    $checkpoint = getCheckpoint();

    // make sure the checkpoint contains
    // the list of countries
    assertsObject($checkpoint)->hasAttribute("countries");
    assertsArray($checkpoint->countries)->isArray();
});
{% endhighlight %}

## hasMethod()

Use `assertsObject()->hasMethod()` to make sure than an object has a method that you expect to exist.

{% highlight php startinline %}
$obj = (object)[ "count" => 1 ];
expectsObject($obj)->hasMethod('expectsObject');
{% endhighlight %}

This has been added for completeness (it's part of Stone's _ObjectComparitor_ that we use for comparing objects); you'll probably never need to use it in a story.

## isEmpty()

Use `assertsObject()->isEmpty()` to make sure that an object has no attributes.

{% highlight php startinline %}
$data = (object)[ ];
assertsObject($data)->isEmpty();
{% endhighlight %}

## isNotEmpty()

Use `assertsObject()->isNotEmpty()` to make sure that an object has attributes.

{% highlight php startinline %}
$data = (object)array(1,2,3,4);
assertsObject($data)->isNotEmpty();
{% endhighlight %}

## isInstanceOf()

Use `assertsObject()->isInstanceOf()` to make sure that an object is an instance of the class that you're expecting.

{% highlight php startinline %}
$obj = new \stdClass;
assertsObject($obj)->isInstanceOf("stdClass");
{% endhighlight %}

This has been added for completeness (it's part of Stone's _ObjectComparitor_ that we use for comparing objects); you'll probably never need to use it in a story.

## isNull()

Use `assertsObject()->isNull()` to make sure that the PHP variable is actually NULL, rather than an object.

{% highlight php startinline %}
$data = null;
assertsObject($data)->isNull()
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isObject()](#isobject)_ instead of testing for NULL.

## isNotInstanceOf()

Use `assertsObject()->isNotInstanceOf()` to make sure that an object is not an instance of the class that you're expecting.

{% highlight php startinline %}
$obj = new \stdClass;
assertsObject($obj)->isNotInstanceOf("DataSift\Storyplayer\PlayerLib\Story_Checkpoint");
{% endhighlight %}

This has been added for completeness (it's part of Stone's _ObjectComparitor_ that we use for comparing objects); you'll probably never need to use it in a story.

## isNotNull()

Use `assertsObject()->isNotNull()` to make sure that the PHP variable is not NULL.

{% highlight php startinline %}
$data = (object)[ 1,2,3,4 ];
assertsObject($data)->isNotNull();
{% endhighlight %}

This has been added for completeness; we'd always recommend using _[isObject()](#isobject)_ instead of testing for NULL.

## isNotSameAs()

Use `assertsObject()->isNotSameAs()` to make sure that two PHP objects are different PHP variables.

{% highlight php startinline %}
$data1 = (object)[ 1,2,3,4 ];
$data2 = (object)[ 1,2,3,4 ];

assertsObject($data1)->isNotSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[doesNotEqual()](#doesnotequal)_ instead.

## isObject()

Use `assertsObject()->isObject()` to make sure that something really is an object.

{% highlight php startinline %}
$data = (object)[ 1,2,3,4 ];
assertsObject($data)->isObject();
{% endhighlight %}

This is most often used in the [post-test inspection phase](../../stories/post-test-inspection.html) to validate the data in the [checkpoint](../../stories/the-checkpoint.html):

{% highlight php startinline %}
$story->addPostTestInspection(function() {
    // get the checkpoint
    $checkpoint = getCheckpoint();

    // make sure the checkpoint contains
    // the list of countries
    assertsObject($checkpoint)->hasAttribute("countries");
    assertsArray($checkpoint->countries)->isArray();
});
{% endhighlight %}

## isSameAs()

Use `assertsObject()->isSameAs()` to make sure that two PHP objects are references to each other, or are in fact the same object.

{% highlight php startinline %}
$data1 = (object)[ 1,2,3,4 ];
$data2 = $data1;

assertsObject($data1)->isSameAs($data2);
{% endhighlight %}

This has been added for completeness; you'll probably use _[equals()](#equals)_ instead.