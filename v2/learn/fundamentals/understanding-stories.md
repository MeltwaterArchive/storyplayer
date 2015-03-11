---
layout: v2/learn-fundamentals
title: Understanding Stories
prev: '<a href="../../learn/fundamentals/running-storyplayer.html">Prev: Running Storyplayer</a>'
next: '<a href="../../learn/fundamentals/understanding-storyplayer-modules.html">Next: Understanding Storyplayer Modules</a>'
updated_for_v2: true
---

# Understanding Stories

Storyplayer runs [stories](understanding-stories.html) that test a specific [system under test](understanding-system-under-test.html) running inside a specific [test environment](understanding-test-environments.html).

## What Is A Story?

Storyplayer's job is to execute a suite of functional tests.  Each of these tests is typically a [user story](user-stories.html) (when end-to-end testing), or a [service story](service-stories.html) (when functionally testing a single app).

## Writing A Story

A story is a PHP file that is executed by Storyplayer.  In this file, Storyplayer expects you to create a `$story` variable, and expects you to add some code that tells Storyplayer what to do and how to check the results.

You write your tests in plain old PHP. There's no domain-specific language (DSL) to learn. You've got immediate access to all of [Storyplayer's built-in modules](../../modules/index.html) plus any modules of your own that you create. Once you get the hang of writing Storyplayer tests, you'll find that it's incredibly quick to automate any test that you want.

You can put the PHP file anywhere you want.  It must end in `Story.php`, otherwise Storyplayer will not run it.

Here's a very simple story which checks that a web page has the expected title:

{% highlight php linenos %}
<?php

// create a new story
$story = newStoryFor("Storyplayer")
      ->inGroup("Web Pages")
      ->called("Can Get Title Of A Web Page");

// compatibility: Storyplayer v2
$story->requiresStoryplayerVersion(2);

// the Action phase
$story->addAction(function() {
    $checkpoint = getCheckpoint();

    usingBrowser()->gotoPage("http://php.net");
    $checkpoint->title = fromBrowser()->getTitle();
});

// the PostTestInspection phase
$story->addPostTestInspection(function() {
    $checkpoint = getCheckpoint();

    assertsObject($checkpoint)->hasAttribute("title");
    assertsString($checkpoint->title)->equals("PHP: Hypertext Preprocessor");
});
{% endhighlight %}

Let's break this down into its parts.

### Creating A Story

The first line of every story is:

{% highlight php %}
<?php
{% endhighlight %}

This is a marker to tell PHP that the file contains PHP code.  If you forget to add this, PHP will print your file on the screen, and then Storyplayer will exit with a fatal error.

The first part of the story creates a PHP variable called `$story`:

{% highlight php startinline %}
// create a new story
$story = newStoryFor("Storyplayer")
      ->inGroup("Web Pages")
      ->called("Can Get Title Of A Web Page");
{% endhighlight %}

Storyplayer prints this information to the screen (and to the `storyplayer.log` file) so that you know which story is currently running.

You have to call this variable `$story`. If you call it anything else, Storyplayer won't know what you've called it, and Storyplayer will exit with a fatal error.

### Compatibility

The next line of our story is mandatory. It tells Storyplayer which version of Storyplayer your test is written for.  You'll be writing stories for Storyplayer v2:

{% highlight php startinline %}
// compatibility: Storyplayer v2
$story->requiresStoryplayerVersion(2);
{% endhighlight %}

### Adding An Action

The _Action_ is where Storyplayer tries to perform the actions of your user story:

{% highlight php startinline %}
// the Action phase
$story->addAction(function($st) {
    $checkpoint = getCheckpoint();

    usingBrowser()->gotoPage("http://php.net");
    $checkpoint->title = fromBrowser()->getTitle();
});
{% endhighlight %}

In this story, we use a web browser to visit the [PHP.net website](http://php.net) to grab the title of the web page. We then store that title in the _checkpoint_ for later inspection.

The _Action_ is normal PHP code. It calls [Storyplayer's modules](../../modules/index.html) to get the job done as quickly as possible. But there's nothing stopping you from writing any PHP code that you want to.

You'll notice that there's no error checking in the story. All of Storyplayer's modules throw an exception if something goes wrong. Storyplayer catches those exceptions for you.

The _checkpoint_ is a special variable in Storyplayer. It's the only way to pass data between each part of a story. (We call these parts _phases_. The _Action_ is one phase. The _PostTestInspection_ that we're going to look at next is another phase.)

You might find it odd at first to have to use the _checkpoint_, but it's an important part of how Storyplayer makes sure that your tests are as repeatable as possible.  We want you to spend your time debugging the system under test, not your stories themselves. Reducing shared state is one way that we achieve this.

### Checking The Result

The _PostTestInspection_ is where Storyplayer checks to see if the _Action_ actually did what it was supposed to. This is part of the [belt and braces](belt-and-braces-testing.html) testing approach that Storyplayer strongly encourages.

{% highlight php startinline %}
// the PostTestInspection phase
$story->addPostTestInspection(function() {
    $checkpoint = getCheckpoint();

    assertsObject($checkpoint)->hasAttribute("title");
    assertsString($checkpoint->title)->equals("PHP: Hypertext Preprocessor");
});
{% endhighlight %}

In this story, we get the _checkpoint_, and then use the [Assertions module](../../modules/assertions/index.html) to make sure that we have the data that we want.

Once again, there's no error checking. If we don't have the expected data, the assertions module will throw an exception. Storyplayer will catch the exception, and assume that the _PostTestInspection_ has failed.

## Running The Story

To run this story, save it as `CanGetTitleOfAWebPageStory.php`, and then run it like this:

    vendor/bin/storyplayer CanGetTitleOfAWebPageStory.php

If you've [set your computer up](../getting-setup/index.html) correctly and [installed Storyplayer into your project](installing-storyplayer.html), you should see something like this:

    Storyplayer 2.0.0-dev - https://datasift.github.io/storyplayer/
    Copyright (c) 2012-present MediaSift Ltd. All rights reserved.
    Released under the BSD 3-Clause license

    Creating test environment localhost: ................. [OKAY] (0.01 secs)
    Running story Storyplayer > Web Pages > Can Get Title Of A Web Page: 1 2. 3. 4. 5....... 6.... 7.  [PASS] (10.66 secs)
    Destroying test environment localhost: ......... [OKAY] (0.35 secs)

    SUCCESS - 3 PASSED, 0 SKIPPED. Time taken: 11.05 secs

<div class="callout info" markdown="1">
#### Terminals And Colour

If you're running Storyplayer from a terminal, Storyplayer will use different colours to make it easier to read the output. You can switch this off by using the switch `--color=no`.

Storyplayer assumes that your terminal is using the standard UNIX colours: a black background, and white / light-grey text. If you're using a different terminal theme, this might clash with Storyplayer's colours. If that happens, just switch off colour output.
</div>

## Doing More In Stories

In this example, we've shown you a very simple story to get you started. Your own stories will probably also have a [Test Setup](../using/stories/test-setup-teardown.html) and a [Test Teardown](../using/stories/test-setup-teardown.html) phase to create the test conditions and to clean up afterwards.

There are other phases too, but they're rarely used. Full details can be found in the [Stories](../../using/stories/index.html) section of the manual.

## Two Types Of Story

The stories you write will either [test your code](../test-your-code/index.html) or [test your platform](../test-your-platform/index.html).

* __Component tests__: Stories that test your code will be designed to thoroughly test a single app or service. They'll use the app's documented interfaces, and they'll also check log files, databases, and anything else where the app makes changes. These stories normally live in the same code repository as the source code for what they are testing. You'll normally deploy your code into a one-off virtual machine to run this test, to prove that your app or service works as intended.
* __End-to-end tests__: Stories that test your platform will automate the actions that your end-users can do. They'll only use the same APIs and interfaces that your end-users can use, and they'll never attempt to access anything that your end-user isn't allowed to access. These stories normally live in their own code repository. You'll normally run these stories against your test, staging and production platforms, to prove that your product or service works in that environment.

If you're fortunate enough to have a dedicated QA / Test team, we recommend that your QA team starts writing the end-to-end tests and your developers write the component tests as well as the unit tests they already write. That way, your developers are handing over tested apps / services for final end-to-end testing.

It's very useful to keep these two types of tests separate, even if your platform is just a single app. Component tests are intrusive, and normally involve setting up databases with specific content before running the tests. They're normally not safe to run on a platform with real users on it - and if your firewalls and other security measures are working properly, the component tests shouldn't be able to access your database anyway!

Equally, the whole point of platform tests is to prove that your service works on the platform that you're testing. They don't just prove that your code works, but also that you've deployed everything correctly. They help you prove that everything is working after an upgrade, so that you're confident that your end-users aren't in for a nasty surprise.

## Further Reading

* If you're looking for advice on where to put your `Story.php` files, then have a look at our [Guide To Testing Your Code](../test-your-code/index.html) and our [Guide To Testing Your Platform](../test-your-platform/index.html). Both include example folder structures for you to use.
* We have a comprehensive section on [Stories](../../using/stories/index.html) in our reference manual.
* We call this style of coding [Prose](../../using/prose/index.html), and we have a comprehensive section on that too.
* Check out our introduction to [User Stories](user-stories.html) if you're new to them.
* Also check out our introduction to [Service Stories](service-stories.html). That's what we call user stories that describe how your backend systems work.
* Finally, if you want to see some production-quality stories, take a look at our collection of [Worked Examples](../worked-examples/index.html).