<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Exceptions;
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
