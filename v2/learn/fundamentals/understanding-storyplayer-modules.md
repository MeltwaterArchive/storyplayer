---
layout: v2/learn-fundamentals
title: Understanding Storyplayer Modules
prev: '<a href="../../learn/fundamentals/understanding-stories.html">Prev: Understanding Stories</a>'
next: '<a href="../../learn/fundamentals/user-stories.html">Next: User Stories</a>'
---

# Understanding Storyplayer Modules

Storyplayer is a rich and powerful test framework. It ships with many modules so that you can start writing tests straight away. Every module's name follows one simple rule to make it easy to find the right module.

And, if you need to do something that we don't have a module for, you can extend Storyplayer with your own modules too. These can be maintained in your own source code repo, and imported into your project using Composer just like Storyplayer itself.

## Autocompletion Support

All Storyplayer modules have names like:

* `assertsObject()`
* `fromHost()`
* `expectsHttpResponse()`
* `usingBrowser()`

They use a _verbNoun_ structure to make it easy to discover and use the right module for the job at hand. Simply start typing one of our verbs in your IDE / editor of choice, and its auto-completion support will list all of the available choices.

{% highlight php startinline %}
// check that $checkpoint really is an object
assertsObject($checkpoint)->isObject();

// get the hostname for a machine in the test environment
$hostname = fromHost($hostId)->getHostname();

// make sure our HTTP call was successful
expectsHttpResponse($response)->hasStatusCode(200);

// open a web page in our chosen web browser
usingBrowser()->gotoPage("http://www.php.net");
{% endhighlight %}

<div class="callout info" markdown="1">
#### Not Using Auto-Completion?

There's a couple of ways you can discover Storyplayer modules:

* Search for files in the `Prose` namespace.

  Most modern editors have Sublime Text-style support for quickly searching for files in a project. Search for the file `Prose/functions.php` to pull up the list of modules that ship with Storyplayer.

* Read [the module documentation](../../modules/index.html).

  We've built an extensive reference manual covering all of the modules that ship with Storyplayer. This also include any configuration you need to do for a particular module, and examples of how to use each module.
</div>

<div class="callout info" markdown="1">
#### Exceptions To The Rule

When we first created Storyplayer back in 2011, we wanted to make sure that tests were as easy to read as possible. Over the years, it's been impossible to make everything fit into our 4 main verbs and still make sense when someone reads it.  Our aim of readability is more important to us.

We have a few exceptions to our naming rule. They are:

* `getCheckpoint` - used like this: `$checkpoint = getCheckpoint();`
* `hostWithRole` - used like this: `foreach(hostWithRole($role) as $hostId) { ... }`

We think these exceptions are more readable like this.
</div>

## The Main Verbs

There are 4 main verbs used by Storyplayer modules:

* __assertsXXXX__: use these modules to check the value of a PHP variable

  These modules are very similar to the tests you may have already used in [PHPUnit](http://phpunit.de) or other unit test tools.

* __fromXXXX__: use these modules to retrieve data from something or somewhere

  These modules know how to go and get the data you need to see what is happening to your system under test running in your test environment.

* __expectsXXXX__: use these modules to guarantee that a specific condition has been met

  These modules normally call one or more of the `fromXXXX` modules to get the data they need.

* __usingXXXX__: use these modules to perform an action of some kind

## Errors Are Handled For You



## Writing Your Own Modules

