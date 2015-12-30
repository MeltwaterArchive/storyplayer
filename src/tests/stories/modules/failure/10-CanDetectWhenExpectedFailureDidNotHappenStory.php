<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Exceptions;
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
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

$story->addPreTestPrediction(function() {
    throw Exceptions::newStoryShouldFailException();
});

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("can detect when expected failure did not happen");

    // we will use this variable to make sure that our callback
    // has been executed
    $callbackRan = false;

    // this is where you perform the steps of your user story
    Failure::expectsFailure()->when("never fails", function() use (&$callbackRan) {
        $callbackRan = true;
    });

    // all done
    $log->endAction();
});
