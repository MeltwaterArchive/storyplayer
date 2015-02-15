<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Modules')
         ->called('Users: Has loaded test users file');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	// no op
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
    $test1 = fromUsers()->getUser("test1");
    $test2 = fromUsers()->getUser("test2");

    assertsObject($test1)->isObject();
    assertsObject($test1)->hasAttribute("username");
    assertsString($test1->username)->equals("test 1");

    assertsObject($test2)->isObject();
    assertsObject($test2)->hasAttribute("username");
    assertsString($test2->username)->equals("test 2");
});