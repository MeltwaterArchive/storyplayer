---
layout: stories
title: Post-Test Inspection Phase
prev: '<a href="../stories/action.html">Prev: Action Phase</a>'
next: '<a href="../prose/index.html">Next: Introducing Prose</a>'
---

# Post-Test Inspection Phase

	// ========================================================================
	//
	// POST-TEST INSPECTION
	//
	// ------------------------------------------------------------------------

	$story->setPostTestInspection(function(StoryTeller $st) {

		// get the checkpoint
		$checkpoint = $st->getCheckpoint();

		// do we have the title we expected?
		$st->expectsObject($checkpoint)->hasAttribute('title');
		$st->expectsString($checkpoint->title)->equals("Twitter");

	});

This phase is *required*.

Here you compate the results you got with the results you were expecting.
