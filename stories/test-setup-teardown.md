---
layout: stories
title: Test Setup / Teardown Phases
prev: '<a href="../stories/test-environment-setup-teardown.html">Prev: Test Environment Setup / Teardown Phases</a>'
next: '<a href="../stories/pre-test-prediction.html">Next: Pre-Test Prediction Phase</a>'
---

# Test Setup / Teardown Phases

Repeatable testing is all about making sure that your test runs under the same conditions each and every time.  The only variable in your testing should be the software under test.  Once your test environment has been created, Storyplayer supports automated setup and teardown of your test conditions, to make your tests as repeatable as possible.

*This phase is optional.*

## Running Order

Creating and destroying test environments are the first and last phases of a story:

1. Test Environment Setup
1. __Test Setup__
1. Pre-test Prediction
1. Pre-test Inspection
1. Action
1. Post-test Inspection
1. __Test Teardown__
1. Test Environment Teardown

## Setting Up Your Test Conditions

To setup your test conditions, add a `TestSetup()` function to your story:

{% highlight php %}
$story->addTestSetup(function(StoryTeller $st) {
	// steps go here
});
{% endhighlight %}

## Reverting Your Test Conditions

Once your test has finished, add a `TestTeardown()` function to put your test environment back to how it was before your `TestSetup()` function ran:

{% highlight php %}
$story->addTestTeardown(function(StoryTeller $st) {
	// steps go here
});
{% endhighlight %}

## Templating Your Test Conditions

On larger applications, you'll normally end up grouping stories together because they are testing similiar aspects of your app.  You might find that all the stories inside a group can share the same test conditions.  You can use Storyplayer's [story templating](story-templates.html) to share the same _TestSetup()_ and _TestTeardown()_ methods across multiple stories.