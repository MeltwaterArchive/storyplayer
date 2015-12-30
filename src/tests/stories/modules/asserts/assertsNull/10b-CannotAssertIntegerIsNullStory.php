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
    $log = Log::usingLog()->startAction("cannot assert 0 is NULL");

    // this is where you perform the steps of your user story
    Failure::expectsFailure()->when("assertion", function() {
        Asserts::assertsNull(0)->isNull();
    });

    // all done
    $log->endAction();
});

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("cannot assert 100 is NULL");

    // this is where you perform the steps of your user story
    Failure::expectsFailure()->when("assertion", function() {
        Asserts::assertsNull(100)->isNull();
    });

    // all done
    $log->endAction();
});
