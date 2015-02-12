---
layout: v2/learn-fundamentals
title: Understanding Storyplayer Modules
prev: '<a href="../../learn/fundamentals/understanding-stories.html">Prev: Understanding Stories</a>'
next: '<a href="../../learn/fundamentals/user-stories.html">Next: User Stories</a>'
---

# Understanding Storyplayer Modules

Storyplayer is a rich and powerful test framework. It ships with many modules so that you can start writing tests straight away. Every module's name follows one simple rule to make it easy to find the right module.

And, if you need to do something that we don't have a module for, you can extend Storyplayer with your own modules too. These can be maintained in your own source code repo, and imported into your project using Composer just like Storyplayer itself.

## Nothing To Include Or Requre

When your story is executed, Storyplayer has already imported all of the available modules. You do not need to `require` or `include` any files to get access to the modules.

You can start using modules straight away in your story's phases.

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

  If your editor / IDE includes support for open-buffer based completion, just keep the `Prose/functions.php` open in your project to give you completion suggestions.

* Read [the module documentation](../../modules/index.html).

  We've built an extensive reference manual covering all of the modules that ship with Storyplayer. This also include any configuration you need to do for a particular module, and examples of how to use each module.
</div>

## The Main Verbs

There are 4 main verbs used by Storyplayer modules:

* __assertsXXXX__: use these modules to check the value of a PHP variable

  These modules are very similar to the tests you may have already used in [PHPUnit](http://phpunit.de) or other unit test tools.

  They're mostly used in the `PostTestInspection` phase to make sure that the _checkpoint_ contains the data you're expecting. Use these modules to avoid uncatchable PHP fatal errors!

* __fromXXXX__: use these modules to retrieve data from something or somewhere

  These modules know how to get data from your test environment.  Just like with HTTP `GET` requests, these modules should not change anything in your test environment.

  They're mostly used to get data to store in the _checkpoint_, or as part of `if` statements in more involved test cases.

* __expectsXXXX__: use these modules to guarantee that a specific condition has been met

  These modules normally call one or more of the `fromXXXX` modules to get the data they need. Once they have the data, they check it to make sure that the data is good.

  They're used in the `TestSetup` and `Action` phases to confirm that things have happened as expected. They're also used in the `PostTestInspection` to confirm that the `Action` phase actually made changes to your test environment.

* __usingXXXX__: use these modules to perform an action of some kind

  These modules do things. They start and stop software. They execute SQL statements. They make HTTP API calls. They click links in the web browser. They perform actions that are expected to change things.

  They're used in all phases.

<div class="callout info" markdown="1">
#### Exceptions To The Rule

When we first created Storyplayer back in 2011, we wanted to make sure that tests were as easy to read as possible. Some things just don't make sense when they're forced to fit into our four main verbs.

We have a few exceptions to our naming rule. They are:

* `getCheckpoint` - used like this: `$checkpoint = getCheckpoint();`
* `hostWithRole` - used like this: `foreach(hostWithRole($role) as $hostId) { ... }`

Exceptions are rare, and we will keep it that way. But given the choice, we'd rather introduce an exception when it makes sense than force something into our naming rule.
</div>

## Errors Are Handled For You

We want you writing tests and debugging problems with the system under test - not with your tests themselves. Powerful modules are one way we help with that. Another way is to keep your code clean of error handling.

Every Storyplayer module has built-in error checking. If an error occurs, the module will throw an exception, and your test will stop at that point. Storyplayer will catch the exception for you, and mark your test as failed. It will also write a detailed report into the `storyplayer.log` file.

Always write your stories as if each line of code will succeed, and let Storyplayer's modules do the error checking for you.

<div class="callout warning" markdown="1">
#### Handle Your Own Errors

Part of the Storyplayer's power is that you write your tests in plain old PHP. You're free to import and use any third-party code that you need to. But if you do, you also need to do some of the error checking yourself.

* Any third-party code that throws exceptions is safe to use. Storyplayer always checks for all exceptions. You don't need to do a thing.

* Any third-party code that uses PHP's legacy `trigger_error()` system should also be safe to use. Storyplayer checks for these kinds of errors too.

* That leaves third-party code that uses function / method return values to report errors. Storyplayer cannot check these for you. You have to check return values yourself.
</div>

## Writing Your Own Modules

Storyplayer is modular. It ships with [its own set of powerful modules](../../modules.index.html). You can write your own modules to extend Storyplayer.

When should you consider writing your own modules? Here are the main situations:

1. If you need to interact with software or an API, and there's no suitable Storyplayer module yet.

   You can just import and use any existing PHP extension, library or component from your stories. Take the time to wrap these in a Storyplayer module. Use the module to make sure that the right error checking is done every time.

   Once the module is written, everyone on your project can quickly re-use the module. They don't need to duplicate all the error checking in their stories every time.

1. If you find yourself repeating the same steps in your stories.

   Storyplayer modules can call other Storyplayer modules, just like stories can. Wrap common code from your stores in a Storyplayer module. Use the module to make sure that all of your stories are consistent in their actions and error checking.

   The module reduces the amount of code your team has to write in their stories. It gives you a single place to edit if the sequence of steps change.

Modules are written as PHP classes. We've created [a guide to writing Storyplayer modules](../writing-a-module/index.html) to show you how to do so.

## Further Reading

* We have [a complete reference to all Storyplayer modules](../../modules/index.html) available.
* Our [Worked Examples](../worked-examples/index.html) show you how to use modules in your stories.
* We've created [a guide to writing Storyplayer modules](../writing-a-module/index.html) if you need to write your own.