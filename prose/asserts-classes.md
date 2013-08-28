---
layout: prose
title: Asserts Classes
prev: '<a href="../prose/creating-prose-modules.html">Prev: Creating Your Own Prose Modules</a>'
next: '<a href="../prose/expects-classes.html">Next: Creating Expects Classes</a>'
---

# Asserts Classes

This page contains any implementation details that are specific to _Asserts_ classes in Prose modules.  For background, please read [Creating Your Own Prose Modules](creating-prose-modules.html) first.

## Base Class

_Asserts_ classes normally extend the `DataSift\Storyplayer\Prose\AssertionsBase` class, and provide a _Comparitor_ object which knows how to examine and test a PHP data type or a specific type of object:

{% highlight php %}
namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\ComparisonLib\ArrayComparitor;

class AssertsArray extends AssertionsBase
{
	public function __construct(StoryTeller $st, $params)
	{
		parent::__construct($st, new ArrayComparitor($params[0]));
	}
}
{% endhighlight %}

`AssertionsBase` proxies method calls through to the supplied _Comparitor_ object for you.

## Exceptions

If any of the assertions fail, `AssertionsBase` throws an [E5xx_ExpectFailed](exceptions.html#E5xx_ExpectFailed) exception.