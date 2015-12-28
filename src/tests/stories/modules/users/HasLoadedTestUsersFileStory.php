<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Users;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

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
    $test1 = Users::fromUsers()->getUser("test1");
    $test2 = Users::fromUsers()->getUser("test2");

    Asserts::assertsObject($test1)->isObject();
    Asserts::assertsObject($test1)->hasAttribute("username");
    Asserts::assertsString($test1->username)->equals("test 1");

    Asserts::assertsObject($test2)->isObject();
    Asserts::assertsObject($test2)->hasAttribute("username");
    Asserts::assertsString($test2->username)->equals("test 2");
});