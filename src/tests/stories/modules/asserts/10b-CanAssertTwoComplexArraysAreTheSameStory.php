<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Modules')
         ->called('AssertsArray: Can check that two complex arrays are the same');

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
	$testData1 = [
		"alpha"   => "a",
		"bravo"   => "b",
		"charlie" => "c",
		"delta"   => "d",
		"echo"    => [ 1, 2, 3, 4, 5 ],
	];
	assertsArray($testData1)->isSameAs($testData1);
	$testData2 =& $testData1;
	assertsArray($testData2)->isSameAs($testData1);

	// and this should fail
	$testData3 = [
		"alpha"   => "a",
		"bravo"   => "b",
		"charlie" => "c",
		"delta"   => "d",
		"echo"    => [ 1, 2, 3, 4, 5 ],
	];

	$checkpoint->test2Exception = false;
	try {
		assertsArray($testData3)->isSameAs($testData1);
	}
	catch (Exception $e) {
		$checkpoint->test2Exception = true;
	}
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	assertsObject($checkpoint)->hasAttribute("test2Exception");
	assertsBoolean($checkpoint->test2Exception)->isTrue();
});