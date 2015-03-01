---
layout: v2/using-stories
title: Post-Test Inspection Phase
prev: '<a href="../../using/stories/action.html">Prev: Action Phase</a>'
next: '<a href="../../using/stories/story-templates.html">Next: Story Templates</a>'
---

# The Post-Test Inspection Phase

In your testing, you can't use the absence of errors to mean that everything worked.  That's important, sure, but you also need to check and make sure that whatever [`Action()`](action.html) you did actually changed something.

How do you know if something changed?  Use the [`PreTestInspection()`](pre-test-inspection.html) to remember the state before your story's `Action()` executes, and then use the `PostTestInspection()` afterwards to check what you've remembered against the actual state of your app.

This phase is *optional*.

## Running Order

The `PostTestInspection()` happens after the story's `Action()` phase has completed:

1. Test Environment Setup
1. Test Setup
1. Pre-test Prediction
1. Pre-test Inspection
1. Action
1. __Post-test Inspection__
1. Test Teardown
1. Test Environment Teardown

## How To Inspect After Your Story Has Made Changes

To make a post-test inspection, add a `PostTestInspection()` function to your story:

{% highlight php startinline %}
$story->addPostTestInspection(function(StoryTeller $st) {
	// get the checkpoint
	// we are going compare against the state stored in here
	$checkpoint = $st->getCheckpoint();

	// steps go here
});
{% endhighlight %}

Repeat the steps you took in your `PreTestInspection()` to get the new state of whatever it is you are testing, and then use the _[Assertions module](../modules/Assertions/index.html)_ to make sure that your action actually did anything useful.

## Templating Your Post-Test Inspections

As with `PreTestInspection()`, each `PostTestInspection()` tends to be unique to each story, but if you do find yourself with common `PreTestInspection()` functions, you can use Storyplayer's [story templating](story-templates.html) to share the same _PostTestInspection()_ function across multiple stories.