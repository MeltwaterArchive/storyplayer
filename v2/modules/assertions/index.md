---
layout: v2/modules-assertions
title: The Assertions Module
prev: '<a href="../../modules/ec2/usingEc2Instance.html">Prev: usingEc2Instance()</a>'
next: '<a href="../../modules/assertions/assertsArray.html">Next: Array Assertions</a>'
---

# The Assertions Module

## Introduction

The __Assertions__ module allows you to test data that you've obtained from other modules.

The source code for this Prose module can be found in these PHP classes:

* DataSift\Storyplayer\Prose\AssertsArray
* DataSift\Storyplayer\Prose\AssertsDouble
* DataSift\Storyplayer\Prose\AssertsInteger
* DataSift\Storyplayer\Prose\AssertsObject
* DataSift\Storyplayer\Prose\AssertsString

## Dependencies

These dependencies are automatically installed when you install Storyplayer:

* [Stone](https://github.com/datasift/Stone) - DataSift's QA toolkit

Additionally, this module uses the standard UNIX _diff_ tool for some of its actions.

## Using The Assertions Module

The basic format of an action is:

{% highlight php %}
$st->MODULE($actualData)->COMPARISON($expectedData);
{% endhighlight %}

where __module__ is one of:

* _[assertsArray()](assertsArray.html)_ - assertions about PHP arrays
* _[assertsDouble()](assertsDouble.html)_ - assertions about PHP floats and doubles
* _[assertsInteger()](assertsInteger.html)_ - assertions about PHP integers
* _[assertsObject()](assertsObject.html)_ - assertions about PHP objects
* _[assertsString()](assertsString.html)_ - assertions about PHP strings

and __comparison__ is one of the methods available on the __module__ you choose.

Here are some examples:

{% highlight php %}
// array comparison
$expectedCountries = array ("United Kingdom", "United States");
$actualCountries = $st->fromBrowser()->getOptions()->fromDropdownLabelled("Countries");
$st->expectsArray($actualCountries)->equals($expectedCountries);
{% endhighlight %}

{% highlight php %}
// string comparison
$expectedTitle = "Welcome To Storyplayer";
$actualTitle = $st->fromBrowser()->getTitle();
$st->expectsString($actualTitle)->equals($expectedTitle);
{% endhighlight %}