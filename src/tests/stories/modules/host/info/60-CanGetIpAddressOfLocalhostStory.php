<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Host;
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
    $log = Log::usingLog()->startAction("get the IP address of 'localhost' using the Shell module");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // what is our IP address, according to the shell module?
    $checkpoint->actualAddress = Host::fromLocalhost()->getIpAddress();

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
    $log = Log::usingLog()->startAction("make sure the Shell module returned the correct result");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // did we get what we expected?
    //
    // we *always* expect the loopback IP address here
    Asserts::assertsString($checkpoint->actualAddress)->equals("127.0.0.1");

    // all done
    $log->endAction();
});
