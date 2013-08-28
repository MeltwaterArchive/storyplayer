---
layout: prose
title: Using Classes
prev: '<a href="../prose/from-classes.html">Prev: From Classes</a>'
next: '<a href="../environments/index.html">Next: Test Environments</a>'
---

# Using Classes

This page contains any implementation details that are specific to _Using_ classes in Prose modules.  For background, please read [Creating Your Own Prose Modules](creating-prose-modules.html) first.

## Base Class

_Using_ classes normally extend the `Prose` base class:

{% highlight php %}
namespace Prose;

use DataSift\Storyplayer\Prose\Prose;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;

class UsingFoo extends Prose
{
	// your methods go here
}

{% endhighlight %}

## What Do The Methods Do?

Each public method on your _Using_ classes normally makes a change to your test environment, or to whatever app you are testing.

* If the change succeeds, returns nothing to the caller
* If the change fails for any reason, throws an exception

## Exceptions

You should throw an [E5xx_ActionFailed](exceptions.html#E5xx_ActionFailed) exception if your method is unable to complete whatever task they perform.

If your _Using_ classes call other code that can throw exceptions, you should always catch those exceptions, make sure that you log them using `$log`, and then throw an `E5xx_ActionFailed` back to the caller.

{% highlight php %}
namespace Prose;

// remember to import Exception, otherwise your try/catch
// will fail to work and you'll wonder why
use Exception;

// our Storyplayer imports
use DataSift\Storyplayer\Prose\Prose;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;

class UsingFoo extends Prose
{
	public function setState($newState)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("set the state of foo to '{$newState}'");

		// change the state
		try {
			$fooConnector = new FooConnector();
			$fooConnector->setState($newState);
		}
		catch (Exception $e) {
			$msg = $e->getMessage();
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// all done
		$log->endAction();
	}
}
{% endhighlight %}
