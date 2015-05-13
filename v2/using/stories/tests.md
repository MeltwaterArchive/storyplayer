---
layout: v2/using-stories
title: Tests
prev: '<a href="../../using/stories/index.html">Prev: Introducing Stories</a>'
next: '<a href="../../using/stories/phases.html">Next: The Eight Phases Of A test</a>'
updated_for_v2: true
---

# Tests

## What Is A Test?

A test:

* is a separate PHP file on disk
* defines __one__ test for __one__ story
* calls `newStoryFor()` to create a new `$story` variable
* adds one or more [phases](phases.html) to the story
* runs against the target [test environment](../test-environments/index.html)
* uses [the checkpoint](the-checkpoint.html) to share data between the phases
* uses Storyplayer's [modules](../modules/index.html) to interact with the system under test
* can reuse [story templates](story-templates.html) to avoid duplicating code

## An Empty Test

Here's what an empty test looks like.  This is what we start from whenever we're writing a new test.

{% highlight php startinline %}
#<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor($storyCategory)
         ->inGroup($storyGroup)
         ->called($storyName)
         ->basedOn($template1)
         ->andBasedOn($template2);

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// CHECK IF TEST NEEDS SKIPPING
//
// ------------------------------------------------------------------------

$story->addTestCanRunCheck(function() {
    // check that all dependencies / requirements are met
    //
    // return TRUE if the test can execute
    // return FALSE if the test must be skipped
});

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // inject test data
    // start any service mocks
    //
    // do anything else that's unique to this test
});

$story->setTestTeardown(function() {
    // stop any service mocks
    //
    // undo anything else that you did in addTestSetup()
});

// ========================================================================
//
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

$story->addPreTestPrediction(function() {
    // do anything that will spot if this test should fail
});

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPreTestInspection(function() {
    // get the checkpoint
    $checkpoint = $st->getCheckpoint();

    // cache any relevant data in the checkpoint
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // do what the user would do in the user or service story
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // look at anything that should have changed, and make sure that it
    // really did change
});
{% endhighlight %}

## The Metadata

Every story starts by creating a `$story` variable:

{% highlight php startinline %}
$story = newStoryFor($storyCategory)
         ->inGroup($storyGroup)
         ->called($storyName);
{% endhighlight %}

where:

* `$storyCategory` is a string: the name of a category that this story belongs to
* `$storyGroup` is a string or an array of strings: the name of the group or nested groups that this story belongs to
* `$storyName` is a string: the name of this story

Here are some examples taken from Storyplayer's own tests:

{% highlight php startinline %}
$story = newStoryFor('Storyplayer')
         ->inGroup('Config')
         ->called('Can get system-under-test storySettings');
{% endhighlight %}

{% highlight php startinline %}
$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Browser"])
         ->called('Can ensure only one heading');
{% endhighlight %}

{% highlight php startinline %}
$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'ZeroMQ'])
         ->called('Can check can send a multi-part message without blocking');
{% endhighlight %}

When these tests are run, Storyplayer writes this out to the console:

{% highlight bash %}
Running story Storyplayer > Config > Can get system-under-test storySettings ...
Running story Storyplayer > Modules > Browser > Can ensure only one heading ...
Running story Storyplayer > Modules > ZeroMQ > Can check can send a multi-part message without blocking ...
{% endhighlight %}

This helps anyone who runs the tests understand what the test does, and how related tests are grouped together.

<div class="callout info" markdown="1">
#### Always Call It $story

Storyplayer expects your test to create a variable with the name `$story`. If you give it a different name, Storyplayer will report a fatal error.
</div>

## Compatibility

Every test needs to tell Storyplayer which version of Storyplayer it was written for:

{% highlight php startinline %}
$story->requiresStoryplayer($majorVersion);
{% endhighlight %}

where:

* `$majorVersion` is always __2__.

If any attempt is made to use a different version of Storyplayer, the test will be skipped.

<div class="callout info" markdown="1">
#### Why Do We Need This?

DataSift already had a large number of tests written for Storyplayer v1 when we introduced SPv2. This was the easiest way to have Storyplayer skip exists tests that hadn't yet been ported to SPv2.

This will also help one day in the (distant!) future when we release SPv3, SPv4 and so on.
</div>

## Performing Work

`addTestCanRunCheck()` et al are collectively known as the [test phases](phases.html). They are discussed in detail in the next page of this manual.

All test phases are optional, but your test must define at least one phase. If it doesn't, Storyplayer will report a fatal error.

Each test phase is written as a PHP closure. It takes no parameters, and returns nothing. The only way to pass data between phases is to store data in [the checkpoint](the-checkpoint.html).

Each test phases calls Storyplayer's [modules](../../modules/index.html) to interact with the system under test and/or the test environment.

{% highlight php startinline %}
$story->addPostTestInspection(function() {
    $checkpoint = getCheckpoint();

    // what title are we expecting?
    $expectedTitle = fromStoryplayer()->getStorySetting("modules.http.remotePage.title");

    // do we have the title we expected?
    assertsObject($checkpoint)->hasAttribute('title');
    assertsString($checkpoint->title)->equals($expectedTitle);
});
{% endhighlight %}

Each line of code normally calls a function that starts with one of:

* `assertsXXX()` - for checking that you have the data that you expect
* `fromXXX()` - for retrieving data from Storyplayer, your system under test or your test environment
* `expectsXXX()` - for checking your assumptions about your system under test or test environment
* `usingXXX()` - for doing things to your system under test or test environment

These are PHP functions defined by our modules. They return new objects for you to then work with. We have [a comprehensive reference manual for all of our bundled modules](../../modules/index.html).

<div class="callout info" markdown="1">
#### Writing In Prose

We call this style of programming _prose_, because it makes your tests very easy to read and understand quickly.
</div>

<div class="callout info" markdown="1">
#### Why Do We Return New Objects Every Time?

In a word: _statelessness_.

One of the guiding principles behind my design of Storyplayer is to ensure that your tests remain as simple as possible, even when testing complex applications.

Stateless tests - and stateless modules - are a key part of achieving that simplicity. Simply put, there's less moving parts when things are stateless. There's less code to go wrong, and it's much easier to make that code reliable and robust in the first place.

If your test needs state, you can store that shared data in [the checkpoint](the-checkpoint.html), where it's easy to see (and test) the state of your test. Modules rarely need state, and if they do, it tends to be in the form of an open connection (e.g. to a database). That can be stored as a variable in your test, and passed in as a parameter to each module.

Throughout my career, I've watched too many people waste their time on debugging their tests rather than using their tests to debug their system under test. With Storyplayer's design, you'll find that your focus shifts to the design of your tests, not the code that brings them to life. And that's how it should be.
</div>

<div class="callout info" markdown="1">
#### Why Use Global Functions For Calling Modules?

You may have noticed that you call Storyplayer modules using global functions:

{% highlight php startinline %}
assertsObject($checkpoint)->hasAttribute('title');
{% endhighlight %}

We do this to make Storyplayer as modular as possible, without you having to worry about managing modules in your tests.

A key part of Storyplayer's long-term usefulness is that it is modular. Need to test something that Storyplayer doesn't have a module for? Just write your own. Find yourself repeating the same code when performing a single action against your system under test? Wrap it in your own module. Storyplayer creates and destroys instances of these modules for you. You never have to call `new XXX()` in a test. You never have to remember when you need a new instance of a module. Storyplayer handles all of that for you.

If this was done via objects, then either you'd have to create and destroy the objects yourself all the time (which adds noise and potential bugs to your tests) or we'd have to provide a module manager object to do that for you. In SPv1, that's exactly what we did, with the `$st` object. But that made autocompletion for IDEs impossible, because there's no way in a dynamic language for `$st` to be able to tell the IDE what modules are available.

With global functions, they're picked up by your IDE for autocompletion. They make it extremely easy to write new modules that are autocompletion-friendly. And we believe that your tests are incredibly easy to read too.
</div>

## Sharing Common Code Via Templates

As you build up your library of tests, you'll find that you start repeating code in your tests.

* Individual actions are best wrapped up in [your own modules](../../learn/writing-a-module/index.html).
* [Story templates](story-templates.html) are a great way to share common code when you find yourself repeating a whole test phase multiple times - especially test setup / teardown actions.

## Saving Tests

Each of your tests goes in its own PHP file. Make sure each PHP file ends in `Story.php`. Storyplayer assumes that any other PHP files are not stories, and it won't load them for you.

Storyplayer uses an old UNIX trick to decide which order to play your stories in. Stories are sorted by their full filename (including the name of the folder that they are in). To control the order that Storyplayer runs your tests in, simply stick a number at the front of a filename to force it to run before another file.

Storyplayer doesn't care where your stories are. We have some recommendations for where to put your tests when you are [testing your code](../../learn/test-your-code/index.html) or [testing your platform](../../learn/test-your-platform/index.html). In particular, grouping your stories into sub-folders on disk can make it really easy when you only want to run a specific set of tests during software development.

For example, here's how Storyplayer's own tests are organised on disk.

<pre>
storyplayer/
  - src/
    - tests/
      - stories/
        - config/
          - 10a-CanGetPerUserModuleSettingsStory.php
        - modules/
          - asserts/
            - 10a-CanAssertIsArrayStory.php
            - 10b-CanAssertArrayContainsKeyStory.php
            - 50a-CanAssertIsObjectStory.php
          - host/
            - 20-CanDetectWhenScreenSessionFailsToStartStory.php
</pre>

I can run just the _asserts_ tests with this command:

{% highlight bash %}
vendor/bin/storyplayer src/tests/stories/modules/asserts

# these tests run in this order:
# src/tests/stories/modules/asserts/10a-CanAssertIsArrayStory.php
# src/tests/stories/modules/asserts/10b-CanAssertArrayContainsKeyStory.php
# src/tests/stories/modules/asserts/50a-CanAssertIsObjectStory.php
{% endhighlight %}

or I can run all of the _modules_ tests with this command:

{% highlight bash %}
vendor/bin/storyplayer src/tests/stories/modules

# these tests run in this order
# src/tests/stories/modules/asserts/10a-CanAssertIsArrayStory.php
# src/tests/stories/modules/asserts/10b-CanAssertArrayContainsKeyStory.php
# src/tests/stories/modules/asserts/50a-CanAssertIsObjectStory.php
# src/tests/stories/modules/host/20-CanDetectWhenScreenSessionFailsToStartStory.php
{% endhighlight %}

or I can run all of Storyplayer's tests with this command:

{% highlight bash %}
vendor/bin/storyplayer src/tests/stories

# these tests run in this order
# src/tests/stories/config/10a-CanGetPerUserModuleSettingsStory.php
# src/tests/stories/modules/asserts/10a-CanAssertIsArrayStory.php
# src/tests/stories/modules/asserts/10b-CanAssertArrayContainsKeyStory.php
# src/tests/stories/modules/asserts/50a-CanAssertIsObjectStory.php
# src/tests/stories/modules/host/20-CanDetectWhenScreenSessionFailsToStartStory.php
{% endhighlight %}

## Example Working Stories

Storyplayer is _self-hosting_: it can be used to test itself.

We're slowly building up a library of these self-tests, which are [hosted on GitHub](https://github.com/datasift/storyplayer/tree/develop/src/tests/stories).  Have a read of them to see how Storyplayer can be used.
