---
layout: v2/using-modules
title: Creating Your Own Storyplayer Modules
prev: '<a href="../../using/modules/module-namespaces.html">Prev: Module Namespaces</a>'
next: '<a href="../../using/test-environments/index.html">Next: Test Environments</a>'
---

# Creating Your Own Storyplayer Modules

If you've jumped straight to this page, please make sure you're familiar with the role of [the $st object](the-st-object.html), [how modules are loaded](module-loading.html) and [the PHP namespaces used for modules](module-namespaces.html) before attempting to create your own Prose modules.

## What Is A Prose Module?

In your stories, when you call `$st->usingBrowser()` (for example), the `$st` object creates and returns a `UsingBrowser` object for the story to use.  [Browser](../modules/browser/index.html) is one of the modules that ships with Storyplayer, and `UsingBrowser` is one of the classes that collectively form the _Browser_ module.

Think of a Prose module as a library of PHP code that's designed to work in Storyplayer.  The _Browser_ module, for example, is just a set of PHP classes that makes it easy to work with any web browser that supports the WebDriver protocol.

Any Prose module is available for use in any story, and in any other Prose module - basically, anywhere that has access to the `$st` object.  This makes it really easy to re-use code, without you having to remember to import the module first.  As long as the `$st` object can find the module via the PSR-0 autoloader, it can do the rest. _Form_, for example, is a Prose module that makes it easy to work with HTML forms in a web browser. Internally, it reuses the _Browser_ module, to avoid reinventing the wheel.

## Why Do I Want To Create My Own Modules?

There are two main reasons why you'll want to create your own Prose modules.

1. _You might need additional functionality that isn't included in Storyplayer itself._ For example, your stories might need to talk to a proprietary product or service, or to a piece of open-source software that Storyplayer doesn't yet have a Prose module for.
1. _To re-use functionality between stories._ For example, you might create a Prose module for each section of your website - a registration module, a login module, an account settings module, and so on - so that you don't need to repeat these code in multiple stories.  _This is similar to the [Page Object concept](https://code.google.com/p/selenium/wiki/PageObjects) popularised by the Selenium community._

## The Classes Behind A Prose Module

A Prose module consists of one or more of these classes:

* _Prose\\AssertsModule_ - throw an exception if a condition isn't met
* _Prose\\ExpectsModule_ throw an exception if a condition isn't met
* _Prose\\FromModule_ - get some information
* _Prose\\UsingModule_ - do something

where __Module__ is the name of your module.

For example, if I created a 'DataSiftApi' module, it would have these classes:

* _Prose\\UsingDataSiftApi_
* _Prose\\FromDataSiftApi_
* _Prose\\ExpectsDataSiftApi_

It _might_ also have a `Prose\AssertsDataSiftApi` class too, but it's quite rare that I create those kinds of classes.  I only use those for testing the state of data types and variables, and they get their own class because it's normally helpful for them to extend `DataSift\Storyplayer\Prose\AssertionsBase` instead of [extending the `Prose` class](#the_prose_base_class).

A module can have additional classes too, such as a shared base class, or helper classes of some kind.  For example, the [Browser](../modules/browser/index.html) has several `TargettedBrowser` classes, whilst the [Provisioning](../modules/provisioning/index.html) module has its additional classes in the `DataSift\Storyplayer\ProvisioningLib` namespace instead.

## The Prose Base Class

Each of the classes that can be loaded by the `$st` object must extend the `DataSift\Storyplayer\Prose\Prose` class.

This class provides:

* a common `__construct()` method; if you override this, make sure you call `parent::__construct()` first in your constructor;
* a `__call()` method to throw an exception if anyone ever calls a method that doesn't exist. This is needed to make sure that the running test fails in a graceful way if anyone ever does call a method that doesn't exist;
* some common helper methods - these will be turned into PHP traits once we drop support for PHP 5.3 in 2014, so that they're only available in the specific modules that need them

## Getting The $st Object

Each method in your Prose module's classes will need access to Storyplayer's `$st` object.  This is available as a property of your class (thanks to the `Prose` base class), and you'll normally create a local variable for it to save yourself a lot of extra typing:

{% highlight php startinline %}
namespace Prose;

use DataSift\Storyplayer\Prose;

class UsingFoo extends Prose
{
	public function doSomething()
	{
		// shorthand
		$st = $this->st;

		// rest of your action goes here
	}
}
{% endhighlight %}

## Adding Logging

Each method in your Prose module's classes must tell the user what it is doing.  Be verbose - when a test fails, whoever is running Storyplayer is going to be relying on this information to work out where the test failed and why.  There are few things worse than having to re-run a test several times (with more and more debugging) to figure out why it no longer worse.

{% highlight php startinline %}
namespace Prose;

use DataSift\Storyplayer\Prose;

class UsingFoo extends Prose
{
	public function doSomething()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("do something");

		// activity goes here

		// all done
		$log->endAction();
	}
}
{% endhighlight %}

Normally, your methods will be very short, and the single log message will be enough.  Sometimes, however, your method might need to contain several steps in order to be useful.  When this happens, you can call `$log->addStep()` to perform additional logging:

{% highlight php startinline %}
namespace Prose;

use DataSift\Storyplayer\Prose;

class UsingFoo extends Prose
{
	public function doSomething()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("do something");

		// activity goes here

		// another step?
		$log->addStep("polling foo for a response", function() use($st) {
			// sub-step action goes here
		});

		// all done
		$log->endAction();
	}
}
{% endhighlight %}

`$log->addStep()` takes two parameters:

* _$message_ - the text to output
* _$callback_ - a PHP lambda to execute

## Per-Verb Considerations

There's a few things that you have to do differently in your Prose modules, depending on which verb (_asserts_, _expects_, _from_ or _using_) that your Prose module class implements.  These are covered in the next few pages of the manual.