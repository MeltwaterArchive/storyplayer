<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Host;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Modules\Shell;
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

/*
$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("describe what we are doing");

    // setup the conditions for this specific test
    $checkpoint = Checkpoint::getCheckpoint();

    // all done
    $log->endAction();
});
*/

/*
$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("describe what we are doing");

    // undo anything that you did in addTestSetup()

    // all done
    $log->endAction();
});
*/

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

$story->addPreTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("use raw PHP to discover our process ID");

    // get the checkpoint - we're going to store data in here
    $checkpoint = Checkpoint::getCheckpoint();

    // store our process ID for final testing
    $checkpoint->expectedPid = getmypid();

    // all done
    $log->endAction();
});

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("use the Shell module to get our process ID");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // what does the Shell module say?
    $checkpoint->actualPid = Host::fromLocalhost()->getPid('php');

    // all done
    $log->endAction();
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure the Shell module returned the expected PID");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // what did we get?
    Asserts::assertsInteger($checkpoint->actualPid)->equals($checkpoint->expectedPid);

    // all done
    $log->endAction();
});
