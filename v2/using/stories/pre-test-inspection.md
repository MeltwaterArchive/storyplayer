---
layout: v2/using-stories
title: "Pre-Test Inspection Phase"
prev: '<a href="../stories/pre-test-prediction.html">Prev: The Pre-Test Prediction Phase</a>'
next: '<a href="../stories/action.html">Next: Action Phase</a>'
---

# The Pre-Test Inspection Phase

In your testing, you can't use the absence of errors to mean that everything worked.  That's important, sure, but you also need to check and make sure that whatever [`Action()`](action.html) you did actually changed something.

How do you know if something changed?  Use the `PreTestInspection()` to remember the state before your story's `Action()` executes, and then use the [`PostTestInspection()`](post-test-inspection.html) afterwards to check what you've remembered against the actual state of your app.

This phase is *optional*.

## Running Order

The `PreTestInspection()` happens once the test conditions have been setup, and after any [`PreTestPrediction()`](pre-test-prediction.html) has been made:

1. Test Environment Setup
1. Test Setup
1. Pre-test Prediction
1. __Pre-test Inspection__
1. Action
1. Post-test Inspection
1. Test Teardown
1. Test Environment Teardown

## How To Inspect Before Your Story Makes Changes

To make a pre-test inspection, add a `PreTestInspection()` function to your story:

{% highlight php %}
$story->addPreTestInspection(function(StoryTeller $st) {
	// get the checkpoint
	// we are going to store the state in here
	$checkpoint = $st->getCheckpoint();

	// steps go here
});
{% endhighlight %}

Use Storyplayer's `FromXXX()` modules to get the information that your story needs to remember, and store this information in the _[checkpoint object](the-checkpoint.html)_.  You can then re-use this information in your `PostTestInspection()`.

As with the [`PreTestPrediction()`](pre-test-inspection), it's important that your `PreTestInspecton()` makes as few changes as possible to whatever you are testing.  You don't want your pre-test inspection to stop your test from being repeatable and reliable.

## Templating Your Pre-Test Inspections

Each `PreTestInspection()` tends to be unique to each story, but if you do find yourself with common `PreTestInspection()` functions, you can use Storyplayer's [story templating](story-templates.html) to share the same _PreTestInspection()_ function across multiple stories.