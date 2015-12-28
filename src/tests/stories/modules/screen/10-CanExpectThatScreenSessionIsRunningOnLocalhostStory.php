<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Modules\Screen;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("start the screen session that we will check for");

    // setup the conditions for this specific test
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->sessionName = "storyplayer_test_session";

    Screen::onLocalhost()->startScreen($checkpoint->sessionName, "top");

    // all done
    $log->endAction();
});


$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("stop any screen sessions that we have left behind");

    // undo anything that you did in addTestSetup()
    $checkpoint = Checkpoint::getCheckpoint();
    Screen::onLocalhost()->stopScreen($checkpoint->sessionName);

    // all done
    $log->endAction();
});


// ========================================================================
//
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

/*
$story->addPreTestPrediction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("describe what we are doing");

    // if it is okay for your story to fail, detect that here

    // all done
    $log->endAction();
});
*/

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

/*
$story->addPreTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("describe what we are doing");

    // get the checkpoint - we're going to store data in here
    $checkpoint = Checkpoint::getCheckpoint();

    // store any data that your story is about to change, so that you
    // can do a before and after comparison

    // all done
    $log->endAction();
});
*/

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

/*
$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("describe what we are doing");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story

    // all done
    $log->endAction();
});
*/

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure our test screen session is running");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // is it running?
    Screen::expectsLocalhost()->screenIsRunning($checkpoint->sessionName);

    // all done
    $log->endAction();
});
