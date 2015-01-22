---
layout: v2/using-prose
title: Expects Classes
prev: '<a href="../../using/prose/asserts-classes.html">Prev: Asserts Classes</a>'
next: '<a href="../../using/prose/from-classes.html">Next: From Classes</a>'
---

# Expects Classes

This page contains any implementation details that are specific to _Expects_ classes in Prose modules.  For background, please read [Creating Your Own Prose Modules](creating-prose-modules.html) first.

## Base Class

_Expects_ classes normally extend the `Prose` base class:

{% highlight php %}
namespace Prose;

use DataSift\Storyplayer\Prose\Prose;
use DataSift\Storyplayer\Prose\E5xx_ExpectFailed;

class ExpectsFoo extends Prose
{
	// your methods go here
}

{% endhighlight %}

## What Do The Methods Do?

Each public method on your class tests that a condition is true.

* If the condition is true, your method ends and returns back to the caller.
* Otherwise, your method needs to throw an exception.

Normally, your methods will get the information about the condition that you're testing from one of two places:

* either a parameter passed into your method, or
* (more commonly) it will call your _From_ class to get the information

If your _Expects_ class is getting information for itself, that's normally a design smell that tells you that you need to move that work into your _From_ class.

__Please note__:

* It is very important that none of your public methods ever change the state of whatever Storyplayer is being used to test.  If that's not possible, then whatever you're testing is broken, and needs fixing before it can be reliably tested!

## Exceptions

If any of the assertions fail, `AssertionsBase` throws an [E5xx_ExpectFailed](exceptions.html#E5xx_ExpectFailed) exception:

{% highlight php %}
class ExpectsFoo extends Prose
{
	public function fooIsReadyForUse()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure foo is ready for use");

		// is foo really ready?
		$state = $st->fromFoo()->getCurrentState();
		if ($state != 'ready')
		{
			throw new E5xx_ExpectFailed(__METHOD__, "foo is ready", "foo is not ready; state is '{$state}'");
		}

		// all done
		$log->endAction();
	}
}
{% endhighlight %}

In the example, note how much information we're providing in the exception:

* where the exception was thrown from
* what we expected to find
* what we actually found when we looked (with specifics)

This level of detail really helps when trying to track down why a test is failing.  Please make sure you provide it in all of your modules.