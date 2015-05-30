---
layout: v2/using-stories
title: Test Setup / Teardown Phases
prev: '<a href="../../using/stories/test-can-run-check.html">Prev: Can-Run Check Phase</a>'
next: '<a href="../../using/stories/pre-test-inspection.html">Next: Pre-Test Inspection Phase</a>'
updated_for_v2: true
---

# Test Setup / Teardown Phases

Repeatable testing is all about making sure that your test runs under the same conditions each and every time.  The only variable in your testing should be the software under test.  Once your test environment has been created, Storyplayer supports automated setup and teardown of your test conditions, to make your tests as repeatable as possible.

*These phases are optional.*

## Running Order

Test conditions are created after the `TestCanRunCheck()` has passed, and they are reverted after the test has completed:

1. Test Can Run Check
1. __Test Setup__
1. Pre-test Inspection
1. Pre-test Prediction
1. Action
1. Post-test Inspection
1. __Test Teardown__

<div class="callout info" markdown="1">
#### If TestSetup() Runs, So Does TestTeardown()

Storyplayer will always run your `TestTeardown()` function, even if the `Action()` or `PostTestInspection()` phases fail. This guarantees that your test can clean up after itself before the next test runs.

It's a good idea to make `TestTeardown()` as robust as possible - it may be cleaning up a test environment that has been damaged by the failed `Action()` phase!
</div>

## Setting Up Your Test Conditions

To setup your test conditions, add a `TestSetup()` function to your story:

{% highlight php startinline %}
$story->addTestSetup(function() {
    // steps go here
});
{% endhighlight %}

## Reverting Your Test Conditions

Once your test has finished, add a `TestTeardown()` function to put your test environment back to how it was before your `TestSetup()` function ran:

{% highlight php startinline %}
$story->addTestTeardown(function() {
    // steps go here
});
{% endhighlight %}

## Templating Your Test Conditions

On larger applications, you'll normally end up grouping stories together because they are testing similiar aspects of your app.  You might find that all the stories inside a group can share the same test conditions.  You can use Storyplayer's [story templating](story-templates.html) to share the same `TestSetup()` and `TestTeardown()` methods across multiple stories.