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
    $log = Log::usingLog()->startAction("can expect failure");

    // we will use this variable to make sure that our callback
    // has been executed
    $callbackRan = false;

    // this is where you perform the steps of your user story
    Failure::expectsFailure()->when("throwing RuntimeException", function() use (&$callbackRan) {
        $callbackRan = true;
        throw new RuntimeException;
    });

    // did the callback run?
    Asserts::assertsBoolean($callbackRan)->isTrue();

    // all done
    $log->endAction();
});
