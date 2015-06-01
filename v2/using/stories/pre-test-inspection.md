---
layout: v2/using-stories
title: "Pre-Test Inspection Phase"
prev: '<a href="../../using/stories/test-setup-teardown.html">Prev: Test Setup / Teardown Phases</a>'
next: '<a href="../../using/stories/pre-test-prediction.html">Next: The Pre-Test Prediction Phase</a>'
updated_for_v2: true
---

# The Pre-Test Inspection Phase

In your testing, you can't use the absence of errors to mean that everything worked.  That's important, sure, but you also need to check and make sure that whatever [`Action()`](action.html) you did actually changed something.

How do you know if something changed?  Use the `PreTestInspection()` to remember the state before your test's `Action()` executes, and then use the [`PostTestInspection()`](post-test-inspection.html) afterwards to check what you've remembered against the actual state of your app.

*This phase is optional.*

## Running Order

The `PreTestInspection()` happens once the test conditions have been setup, and after the optional [`PreTestPrediction()`](pre-test-prediction.html) has been made:

1. Test Can Run Check
1. Test Setup
1. Pre-test Prediction
1. __Pre-test Inspection__
1. Action
1. Post-test Inspection
1. Test Teardown

## How To Inspect Before Your Story Makes Changes

To make a pre-test inspection, add a `PreTestInspection()` function to your test:

{% highlight php startinline %}
$story->addPreTestInspection(function() {
    // get the checkpoint
    // we are going to store the state in here
    $checkpoint = $st->getCheckpoint();

    // steps go here
});
{% endhighlight %}

Use Storyplayer's `FromXXX()` modules to get the information that your test needs to remember, and store this information in the _[checkpoint object](the-checkpoint.html)_.  You can then re-use this information in your `PostTestInspection()`.

<div class="callout info" markdown="1">
#### A Light Touch Is Essential

As with the [`PreTestPrediction()`](pre-test-inspection), it's important that your `PreTestInspecton()` makes as few changes as possible to whatever you are testing.  You don't want your pre-test inspection to stop your test from being repeatable and reliable.
</div>

## Templating Your Pre-Test Inspections

Each `PreTestInspection()` tends to be unique to each test, but if you do find yourself with common `PreTestInspection()` functions, you can use Storyplayer's [story templating](story-templates.html) to share the same _PreTestInspection()_ function across multiple tests.