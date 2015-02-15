<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Modules')
         ->called('Users: User data changes persist across phases');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// TEST SETUP / TEARDOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    $checkpoint = getCheckpoint();
    $checkpoint->expectedData = "new test data";
});

$story->addTestTeardown(function() {
    $test1 = fromUsers()->getUser("test1");
    if (isset($test1->extraData)) {
        unset($test1->extraData);
    }
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    $checkpoint = getCheckpoint();

	$test1 = fromUsers()->getUser("test1");
    assertsObject($test1)->doesNotHaveAttribute("extraData");

    $test1->extraData = $checkpoint->expectedData;
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
    $checkpoint = getCheckpoint();

    $test1 = fromUsers()->getUser("test1");

    assertsObject($test1)->isObject();
    assertsObject($test1)->hasAttribute("extraData");
    assertsString($test1->extraData)->equals($checkpoint->expectedData);
});