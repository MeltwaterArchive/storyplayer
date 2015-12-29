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

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("run a command as a user");

    foreach(Host::getHostsWithRole("host_target") as $hostId) {
        $user = Host::fromHost($hostId)->getStorySetting("user.username");
        $result = Shell::onHost($hostId)->runCommandAsUser("ls", $user);
        Asserts::assertsInteger($result->returnCode)->equals(0);
        Asserts::assertsString($result->output)->isNotEmpty();
    }

    // all done
    $log->endAction();
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

/*
$story->addPostTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("describe what we are doing");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // gather new data, and make sure that your action actually changed
    // something. never assume that the action worked just because it
    // completed to the end with no errors or exceptions!

    // all done
    $log->endAction();
});
*/
