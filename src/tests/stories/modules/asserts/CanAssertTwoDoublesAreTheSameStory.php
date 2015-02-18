<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsDouble'])
         ->called('Can check that two doubles are the same');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	$checkpoint = getCheckpoint();

	// this should pass
	$actualData = 1.1;
	$expectedData1 = &$actualData;
	assertsDouble($actualData)->isSameAs($expectedData1);

	// and these should fail
	try {
		$expectedData2 = $actualData;
		assertsDouble($actualData)->isSameAs($expectedData2);
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}

	// and these should fail
	try {
		$expectedData3 = 1.1;
		assertsDouble($actualData)->equals($expectedData3);
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}

});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	assertsObject($checkpoint)->hasAttribute("test2Passed");
	assertsBoolean($checkpoint->test2Passed)->isTrue();

	assertsObject($checkpoint)->hasAttribute("test3Passed");
	assertsBoolean($checkpoint->test3Passed)->isTrue();
});