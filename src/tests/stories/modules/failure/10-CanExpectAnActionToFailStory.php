<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Failure;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Stories\BuildStory;

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
