<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
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
// TEST SETUP / TEARDOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->expectedData = "new test data";
});

$story->addTestTeardown(function() {
    $test1 = Users::fromUsers()->getUser("test1");
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
    $checkpoint = Checkpoint::getCheckpoint();

	$test1 = Users::fromUsers()->getUser("test1");
    Asserts::assertsObject($test1)->doesNotHaveAttribute("extraData");

    $test1->extraData = $checkpoint->expectedData;
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
    $checkpoint = Checkpoint::getCheckpoint();

    $test1 = Users::fromUsers()->getUser("test1");

    Asserts::assertsObject($test1)->isObject();
    Asserts::assertsObject($test1)->hasAttribute("extraData");
    Asserts::assertsString($test1->extraData)->equals($checkpoint->expectedData);
});