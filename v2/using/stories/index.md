---
layout: v2/using-stories
title: Introducing Stories
prev: '<a href="../../using/devices/how-to-test.html">Prev: How To Test With Browsers And Devices</a>'
next: '<a href="../../using/stories/user-stories.html">Next: User Stories</a>'
---

# Introducing Stories

Storyplayer is all about testing the stories that your software supports.

## What Are Stories?

Stories are a popular way of capturing the functional requirements of your software.  They're written as a simple statement that states who is allowed to perform the action, and what those people should be able to do.  For example:

* subscription users can see all of their invoices

Stories have become a popular alternative to _functional requirement specifications_ and _UML use cases_ because they are quick to write, and are easy for everyone involved to understand (especially non-developers).  Stories themselves don't include implementation detail - they describe the _what_, not the _how_ - which means that they only change when you want your software to offer different functionality.

Stories come in two flavours: [user stories](user-stories.html) and [service stories](service-stories.html).  The key difference between the two is that _user stories_ describe what your end-users can do, whilst _service stories_ describe the behaviour of your backend systems.

Storyplayer supports testing both flavours inside the one tool.

## Turning Stories Into Story Tests

Each story describes something that a user can do.  In Storyplayer, we turn stories into _[actions](action.html)_ - the steps that the user would _do_ for that story.  This forms the heart of each story test that you write.  For example:

* subscription users can see all of their invoices

might translate into the following steps:

1. login as a subscription user
1. click on 'My Account'
1. click on 'Invoices'

But there's much more to testing a story than simply performing an action against a piece of software.  There are [eight phases](phases.html) to animating and testing a story, and Storyplayer is one of the few tools out there that supports all of these phases.

## Story Tests Are Written In Code

Unlike other tools, Storyplayer's story tests aren't written in a DSL of any kind.  They're written in plain old PHP code, backed by our extensive list of [modules](../modules/index.html) that ship with Storyplayer, and easily extended by [writing your own modules](../prose/creating-prose-modules.html).  For example:

* subscription users can see all of their invoices

might translate into the following PHP code:

{% highlight php startinline %}
$story->addAction(function($st) {
	# login as a subscription user
	$st->usingBrowser()->gotoPage('/login');
	$st->usingForm('login_form')->fillOutFields(
		array(
			'username' => 'subscription user',
			'password' => 'my password'
		)
	);
	$st->usingBrowser()->click()->buttonWithText('Login');

	# click on 'My Account'
	$st->usingBrowser()->click()->linkWithText('My Account');

	# click on 'Invoices'
	$st->usingBrowser()->click()->linkWithText('Invoices');
});
{% endhighlight %}

This approach (which we call [Prose](../prose/index.html)) is carefully designed to make it quick and easy to write very readable tests, whilst at the same time reducing the possibility of silly bugs in your test code.

## Tests Can Be Templated

Stories tend to be grouped, and this will be reflected in your library of story tests as you build it up.  You'll start to notice that your stories are sharing common steps (especially the [test environment setup and teardown phases](test-environment-setup-teardown.html) of service stories). For example:

* subscription users can see all of their invoices

might be part of a library of tests around invoicing, that shares a common template:

{% highlight php startinline %}
$story = newStoryFor('Billing User Stories')
         ->inGroup('Invoices')
         ->called('Can See All Invoices')
         ->basedOn(new InvoiceTestsTemplate());
{% endhighlight %}

You can create classes, called [story templates](story-templates.html), to avoid repeating yourself during your tests. Story templates can do anything that a story can do, and can be shared with as many stories as you like.

## Tests Are Stored As PHP Scripts

Each test is stored as a separate PHP script on disk.  For example:

* subscription users can see all of their invoices

might be stored as the script:

<pre>
| - stories
    |- billing
       |- invoices
          |- CanSeeAllInvoicesStory.php
</pre>

Stories are meant to be independent from each other, and therefore story tests should be too.  _Changing one story test should have no impact at all on any other story tests._

__Note__ that we said _script_, and not _class_.  Writing stories as scripts also avoids the `VeryLongClassNameToAvoidDuplicates` problem too :)

Storyplayer can run a single story test script, or you can build a set of scripts (which we call [tales](tales.html)) to run one after the other.

## Tests Are Executed Against Environments

When we built Storyplayer, we wanted to be able to test software before it was deployed to our production environment.  That allows us to catch serious problems and regressions.  As a result, stories can be run against [your own machine](../environments/your-machine/index.html) or against [isolated test environments](../environments/isolated/index.html) - you tell Storyplayer which environment to use.

But what about after we have deployed software to our production environment? Even automated deployments can go wrong, and complex software often has bugs that only appear in production. Wouldn't it be great if you could test your user stories against production too, using the same library of story tests that you've built for your pre-release testing?  With Storyplayer, [you can](../environments/production.html).
