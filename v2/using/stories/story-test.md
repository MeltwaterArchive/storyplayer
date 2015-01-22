---
layout: v2/using-stories
title: The Story Test
prev: '<a href="../stories/service-stories.html">Prev: Service Stories</a>'
next: '<a href="../stories/phases.html">Next: The Eight Phases Of A Story Test</a>'
---

# The Story Test

## What Is A Story Test?

A story test:

* is a separate PHP script on disk
* defines __one__ test for __one__ story
* calls `newStoryFor()` to create a new `$story` variable
* adds up to [eight phases](phases.html) to the story
* uses [the checkpoint](the-checkpoint.html) to share data between the phases
* uses Storyplayer's [modules](../modules/index.html) to do anything
* can reuse [story templates](story-templates.html) to avoid duplicating code

## An Empty Story Test

Here's what an empty story test looks like.  This is what we start from whenever we're writing a new story test.

{% highlight php %}
<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor(<story category>)
         ->inGroup(<story group>)
         ->called(<story name>)
         ->basedOn(<story template>)
         ->andBasedOn(<another story template>);

// ========================================================================
//
// TEST ENVIRONMENT SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestEnvironmentSetup(function(StoryTeller $st) {
    // add steps here to create the test environment
});

$story->addTestEnvironmentTeardown(function(StoryTeller $st) {
    // add steps here to destroy the test environment
});

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function(StoryTeller $st) {
    // inject test data
    // start any service mocks
    //
    // do anything else that's unique to this test
});

$story->setTestTeardown(function(StoryTeller $st) {
    // stop any service mocks
    //
    // undo anything else that you did in addTestSetup()
});

// ========================================================================
//
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

$story->addPreTestPrediction(function(StoryTeller $st) {
    // do anything that will spot if this test should fail
});

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPreTestInspection(function(StoryTeller $st) {
    // get the checkpoint
    $checkpoint = $st->getCheckpoint();

    // cache any relevant data in the checkpoint
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function(StoryTeller $st) {
    // do what the user would do in the user or service story
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function(StoryTeller $st) {
    // look at anything that should have changed, and make sure that it
    // really did change
});
{% endhighlight %}

## Example Working Stories

Storyplayer is _self-hosting_: it can be used to test itself.

We're slowly building up a library of these self-tests, which are [hosted on GitHub](https://github.com/datasift/storyplayer/tree/develop/src/tests/stories).  Have a read of them to see how Storyplayer can be used.