---
layout: v2/using-stories
title: The Pre-Test Prediction Phase
prev: '<a href="../../using/stories/pre-test-inspection.html">Prev: Pre-Test Inspection Phase</a>'
next: '<a href="../../using/stories/action.html">Next: Action Phase</a>'
updated_for_v2: true
---

# The Pre-Test Prediction Phase

Imagine a website with three types of user: a free trial, a subscription user, and an admin user.  It takes a lot of time and discipline to write all of separate tests required to prove what an admin user can do, but a free user cannot (for example).  And you have to double the number of tests when you throw a logged-out user into that mix.

Wouldn't it be better if you could just take one of the admin user tests, and simply run it with all the other types of users, and have the story know the difference, so that it can say _PASS_ when the test fails for the other types of users?  That's what the `PreTestPrediction()` is for.

*This phase is optional.*

<div class="callout info" markdown="1">
#### Advanced Feature

The `PreTestPrediction()` phase was added originally to help Stuart investigate his hypothesis for _Continuous Assurance_. Don't worry if none of your tests use this phase!

If you want to know more about _Continuous Assurance_, do get in touch with Stuart.
</div>

## Running Order

The `PreTestPrediction()` happens once the test conditions have been setup, and data has been gathered in the [PreTestInspection()](pre-test-inspection.html):

1. Test Can Run Check
1. Test Setup
1. Pre-test Inspection
1. __Pre-test Prediction__
1. Action
1. Post-test Inspection
1. Test Teardown

## How To Make A Prediction

To make a pre-test prediction, add a `PreTestPrediction()` function to your test:

{% highlight php startinline %}
$story->addPreTestPrediction(function() {
    // steps go here
});
{% endhighlight %}

Storyplayer expects that your `PreTestPrediction()` will throw an exception of some kind if the test should fail.  Storyplayer's many `ExpectsXXX()` modules are perfect for doing this.

<div class="callout info" markdown="1">
#### A Light Touch Is Essential

When you're creating your pre-test prediction, it's important that your `PreTestPrediction()` makes as few changes to whatever you are testing as possible.  You don't want your pre-test prediction to stop your test from being repeatable and reliable!
</div>

## Templating Your Pre-Test Predictions

Tests for your website will probably end up sharing the same `PreTestPrediction()` over time.  You can use Storyplayer's [story templating](story-templates.html) to share the same _PreTestPrediction()_ function across multiple tests.