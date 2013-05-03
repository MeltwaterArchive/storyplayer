---
layout: stories
title: "Example: Testing A Website"
prev: '<a href="../stories/post-test-inspection.html">Prev: Post-Test Inspection Phase</a>'
next: '<a href="../stories/soa-example.html">Next: Example: Testing A Service</a>'
---

# An Example Story

The story below is one of of Storyplayer's own tests, which loads a web pages and examines the contents.  It's a very simple story, but it's a nice illustration of how to use the [Checkpoint] (the-checkpoint.html) to share test data between phases of a story.

{% highlight php %}
<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer Service Stories')
         ->inGroup('Web Pages')
         ->called('Can retrieve a heading by ID');

// ========================================================================
//
// TEST ENVIRONMENT SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// ========================================================================
//
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function(StoryTeller $st) {
    // get the checkpoint, to store data in
    $checkpoint = $st->getCheckpoint();

    // load our test page
    $st->usingBrowser()->gotoPage("file://" . __DIR__ . '/../testpages/index.html');

    // get a h2 by its ID
    $checkpoint->content = $st->fromBrowser()->getText()->fromHeadingWithId('self_test_website');
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->setPostTestInspection(function(StoryTeller $st) {
    // get the checkpoint
    $checkpoint = $st->getCheckpoint();

    // do we have the content we expected?
    $st->expectsObject($checkpoint)->hasAttribute('content');
    $st->expectsString($checkpoint->content)->equals("Self-Test Website");
});
{% endhighlight %}