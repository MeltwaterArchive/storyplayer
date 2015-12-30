<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Failure;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("cannot assert 'true' is null");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story
    Failure::expectsFailure()->when("assertion", function() {
        Asserts::assertsNull(true)->isNull();
    });

    // all done
    $log->endAction();
});

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("cannot assert 'false' is null");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story
    Failure::expectsFailure()->when("assertion", function() {
        Asserts::assertsNull(false)->isNull();
    });

    // all done
    $log->endAction();
});
