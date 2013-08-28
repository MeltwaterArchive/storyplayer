---
layout: prose
title: From Classes
prev: '<a href="../prose/asserts-classes.html">Prev: Creating Asserts Classes</a>'
next: '<a href="../prose/from-classes.html">Next: Creating From Classes</a>'
---

# From Classes

This page contains any implementation details that are specific to _From_ classes in Prose modules.  For background, please read [Creating Your Own Prose Modules](creating-prose-modules.html) first.

## Base Class

_From_ classes normally extend the `Prose` base class:

{% highlight php %}
namespace Prose;

use DataSift\Storyplayer\Prose\Prose;

class FromFoo extends Prose
{
	// your methods go here
}

{% endhighlight %}

## What Do The Methods Do?

Each public method on your class retrieves information from somewhere, and returns it to the caller.

* If the condition is available, return that information to the caller.
* Otherwise, return `NULL`.

__Please note__:

* It is very important that none of your public methods ever change the state of whatever Storyplayer is being used to test.  If that's not possible, then whatever you're testing is broken, and needs fixing before it can be reliably tested!

## Exceptions

Your _From_ classes should not throw any exceptions.

If your _From_ classes call other code that can throw exceptions, you should always catch those exceptions, and make sure that you log them using `$log`, and then return `NULL` back to the caller.

{% highlight php %}
namespace Prose;

// remember to import Exception, otherwise your try/catch
// will fail to work and you'll wonder why
use Exception;

class FromFoo extends Prose
{
	public function getCurrentState()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get the current state of foo");

		// what is the current state?
		try {
			$fooConnector = new FooConnector();
			$state = $fooConnector->getCurrentState();
		}
		catch (Exception $e) {
			$log->endAction($e->getMessage());
			return NULL;
		}

		// all done
		$log->endAction($state);
		return $state;
	}
}
{% endhighlight %}
