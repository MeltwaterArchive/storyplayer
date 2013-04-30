---
layout: stories
title: Action Phase
prev: '<a href="../stories/pre-test-inspection.html">Prev: Pre-Test Inspection Phase</a>'
next: '<a href="../stories/post-test-inspection.html">Next: Post-Test Inspection Phase</a>'
---

# Action Phase

    // ========================================================================
    //
    // POSSIBLE ACTION(S)
    //
    // ------------------------------------------------------------------------

    $story->addAction(function(StoryTeller $st) {

	    // get the checkpoint, to store data in
	    $checkpoint = $st->getCheckpoint();

        // load the home page
        $st->usingBrowser()->gotoPage("https://twitter.com");

        // get the title of the test page
        $checkpoint->title = $st->fromBrowser()->getTitle();

    });

This phase is **required**.

Here you execute the actual test.
