---
layout: stories
title: Post-Test Inspection Phase
prev: '<a href="../stories/action.html">Prev: Action Phase</a>'
next: '<a href="../stories/test-users.html">Next: Test Users</a>'
---

# Post-Test Inspection Phase

This phase is *required*.

Here you compare the results you got with the results you expected. Storyplayer ships with a wide range of [assertions](/storyplayer/modules/assertions/index.html) to make your job easier.

The arguments of the assertions are extracted from the Storyplayer's [Checkpoint object](/storyplayer/stories/the-checkpoint.html).

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

We'll be expanding this section in the next couple of weeks.