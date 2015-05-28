---
layout: v2/using-stories
title: Introducing Stories
prev: '<a href="../../using/devices/how-to-test.html">Prev: How To Test With Browsers And Devices</a>'
next: '<a href="../../using/stories/tests.html">Next: Tests</a>'
updated_for_v2: true
---

# Introducing Stories

Storyplayer is all about testing the stories that your software supports.

## What Are Stories?

Stories are a popular way of capturing the functional requirements of your software.  They're written as a simple statement that states who is allowed to perform the action, and what those people should be able to do.  For example:

* subscription users can see all of their invoices

Stories have become a popular alternative to _functional requirement specifications_ and _UML use cases_ because they are quick to write, and are easy for everyone involved to understand (especially non-developers).  Stories themselves don't include implementation detail - they describe the _what_, not the _how_ - which means that they only change when you want your software to offer different functionality.

Stories come in two flavours: [user stories](../../learn/fundamentals/user-stories.html) and [service stories](../../learn/fundamentals/service-stories.html).  The key difference between the two is that _user stories_ describe what your end-users can do, whilst _service stories_ describe the behaviour of your backend systems.

Storyplayer supports testing both flavours inside the one tool.

## Turning Stories Into Tests

Each story describes something that a user can do.  In Storyplayer, we turn stories into [tests](tests.html) - the steps that the user would _do_ for that story.  This forms the heart of each test that you write.  For example:

* subscription users can see all of their invoices

might translate into the following steps:

1. login as a subscription user
1. click on 'My Account'
1. click on 'Invoices'

But there's much more to testing a story than simply performing an action against a piece of software.  Detailed testing includes setting up the test conditions in advance, and checking afterwards that the action actually did something - what we call [belt and braces testing](../../learn/fundamentals/belt-and-braces-testing.html).

Storyplayer is one of the few tools around to support such detailed testing, via [test phases](phases.html).

## Tests Are Written In Code

Unlike other tools, Storyplayer's tests aren't written in a domain-specific language (a DSL) of any kind.  [They're written in plain old PHP code](tests.html), backed by our extensive list of [modules](../../modules/index.html) that ship with Storyplayer, and easily extended by [writing your own modules](../../learn/writing-a-module/index.html).  For example:

* subscription users can see all of their invoices

might translate into the following PHP code:

{% highlight php startinline %}
$story->addAction(function() {
    # login as a subscription user
    usingBrowser()->gotoPage('/login');
    usingForm('login_form')->fillOutFields(
        [
            'username' => 'subscription user',
            'password' => 'my password'
        ]
    );
    usingBrowser()->click()->buttonWithText('Login');

    # click on 'My Account'
    usingBrowser()->click()->linkWithText('My Account');

    # click on 'Invoices'
    usingBrowser()->click()->linkWithText('Invoices');
});
{% endhighlight %}

This approach is carefully designed to make it quick and easy to write very readable tests, whilst at the same time reducing the possibility of annoying bugs in your test code.

## Tests Are Stored As Separate PHP Files

Each test is stored as a separate PHP file on disk.  For example:

* subscription users can see all of their invoices

might be stored as the PHP file:

<pre>
| - stories
    |- billing
       |- invoices
          |- CanSeeAllInvoicesStory.php
</pre>

Stories are meant to be independent from each other, and therefore your tests should be too.  _Changing one test should have no impact at all on any other tests._

## Tests Can Be Grouped

[Tests tend to be grouped](grouping-tests.html), and this will be reflected in your library of tests as you build it up.  For example, Storyplayer's own tests include these groups:

<pre>
|- stories
   |- modules
      |- asserts
      |- browser
      |- form
      |- host
</pre>

[You can easily run a single test or any group of tests. Or you can run the lot.](running-tests.html)

## Tests Can Be Templated

You'll start to notice that your stories are sharing common steps. For example:

* subscription users can see all of their invoices

might be part of a library of tests around invoicing, that shares a common template:

{% highlight php startinline %}
$story = newStoryFor('Billing User Stories')
         ->inGroup('Invoices')
         ->called('Can See All Invoices')
         ->basedOn(new InvoiceTestsTemplate());
{% endhighlight %}

You can create classes, called [story templates](story-templates.html), to avoid repeating yourself during your tests. Story templates can do anything that a story can do, and can be shared with as many stories as you like.

Any story can be based on as many story templates as memory allows.

## Tests Are Executed Against A System Under Test

Your tests exist to prove that a specific app, service, or platform works as intended. We call these [the system under test](../../learn/fundamentals/understanding-system-under-test.html). When you run Storyplayer, you tell it which particular system under test to test against.

This allows us to do useful things such as test two different versions of the same app, and to add tests for new versions of an app without breaking existing tests for older versions.

## Tests Are Executed Against Test Environments

When we built Storyplayer, we wanted to be able to test software before it was deployed to our production environment.  That allows us to catch serious problems and regressions.  As a result, stories can be run against [your own machine](../test-environments/your-machine/index.html) or against [isolated test environments](../test-environments/isolated/index.html) - you tell Storyplayer which test environment to use.

But what about after we have deployed software to our production environment? Even automated deployments can go wrong, and complex software often has bugs that only appear in production. Wouldn't it be great if you could test your user stories against production too, using the same library of tests that you've built for your pre-release testing?  With Storyplayer, [you can](../test-environments/production.html).
