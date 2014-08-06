---
layout: v2/stories
title: The Pre-Test Prediction Phase
prev: '<a href="../stories/test-setup-teardown.html">Prev: Test Setup / Teardown Phases</a>'
next: '<a href="../stories/pre-test-inspection.html">Next: </a>'
---

# The Pre-Test Prediction Phase

Imagine a website with three types of user: a free trial, a subscription user, and an admin user.  It takes a lot of time and discipline to write all of separate tests required to prove what an admin user can do, but a free user cannot (for example).  And you have to double the number of tests when you throw a logged-out user into that mix.

Wouldn't it be better if you could just take one of the admin user stories, and simply run it with all the other types of users, and have the story know the difference, so that it can say _PASS_ when the story fails for the other types of users?  That's what the `PreTestPrediction()` is for.

*This phase is optional.*

## Running Order

The `PreTestPrediction()` happens once the test conditions have been setup:

1. Test Environment Setup
1. Test Setup
1. __Pre-test Prediction__
1. Pre-test Inspection
1. Action
1. Post-test Inspection
1. Test Teardown
1. Test Environment Teardown

## How To Make A Prediction

To make a pre-test prediction, add a `PreTestPrediction()` function to your story:

{% highlight php %}
$story->addPreTestPrediction(function(StoryTeller $st) {
	// steps go here
});
{% endhighlight %}

Storyplayer expects that your `PreTestPrediction()` will throw an exception of some kind if the story should fail.  Storyplayer's many `ExpectsXXX()` modules are perfect for doing this.

When you're creating your pre-test prediction, it's important that your code makes as few changes to whatever you are testing as possible.  You don't want your pre-test prediction to stop your test from being repeatable and reliable!

## Templating Your Pre-Test Predictions

Stories that test your website will probably end up sharing the same `PreTestPrediction()` over time.  You can use Storyplayer's [story templating](story-templates.html) to share the same _PreTestPrediction()_ function across multiple stories.