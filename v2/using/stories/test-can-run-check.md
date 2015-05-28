---
layout: v2/using-stories
title: Can-Run Check Phase
prev: '<a href="../../using/stories/phases.html">Prev: Test Phases</a>'
next: '<a href="../../using/stories/test-setup-teardown.html">Next: Test Setup / Teardown Phases</a>'
---

# The Can-Run Check Phase

When you have a lot of tests, you may find that not every test should run.  Some tests may only work against a particular test environment, or they may only work with a specific version of your system under test.  If you find yourself in this situation, you don't want a test to run when you know it will always fail.

When that happens, write the `TestCanRunCheck()` phase to tell Storyplayer whether or not your test should be skipped.

This phase is *optional*.

## Running Order

The `TestCanRunCheck()` happens before the test's `TestSetup()` phase runs:

1. __Test Can Run Check__
1. Test Setup
1. Pre-test Prediction
1. Pre-test Inspection
1. Action
1. Post-test Inspection
1. Test Teardown

## How To Check That Your Test Can Run

To check if your test can run, add a `TestCanRunCheck()` function to your test:

{% highlight php startinline %}
$story->addTestCanRunCheck(function() {
	// your checks go here
});
{% endhighlight %}

Your test will be skipped if:

* your check returns `FALSE`. This must be PHP's actual `FALSE`. `NULL`, `0` and anything else that might equate to `FALSE` is not accepted.
* one of the steps in your check throws an exception. Storyplayer's modules throw exceptions for you if their actions fail for any reason.

## Templating Your Checks

You can use Storyplayer's [story templating](story-templates.html) to share the same `TestCanRunCheck()` function across multiple stories.