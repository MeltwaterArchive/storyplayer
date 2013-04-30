---
layout: stories
title: Pre-Test Prediction Phase
prev: '<a href="../stories/test-setup-teardown.html">Prev: Test Setup / Teardown Phases</a>'
next: '<a href="../stories/pre-test-inspection.html">Next: Pre-Test Inspection Phase</a>'
---

# Pre-Test Prediction Phase

    // ========================================================================
    //
    // PRE-TEST PREDICTION
    //
    // ------------------------------------------------------------------------

    $story->setPreTestPrediction(function(StoryTeller $st) {

        // this story should always succeed for any of the valid users
        $st->expectsUser()->isValidForStory();

    });
    
This step is *required*.

Here you create a Storyteller object. 

