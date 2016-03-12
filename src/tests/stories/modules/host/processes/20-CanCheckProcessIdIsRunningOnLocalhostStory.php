<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Modules\Host;
use Storyplayer\SPv3\Modules\Shell;
use Storyplayer\SPv3\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPreTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("get Storyplayer's process ID using raw PHP");

    // get the checkpoint - we're going to store data in here
    $checkpoint = Checkpoint::getCheckpoint();

    // what is our process ID?
    $checkpoint->pid = getmypid();

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
    $log = Log::usingLog()->startAction("can the Host module check that our process ID exists on 'localhost'?");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // let's see what the Host module thinks
    $isRunning = Host::fromLocalhost()->getPidIsRunning($checkpoint->pid);
    Asserts::assertsBoolean($isRunning)->isTrue();

    // all done
    $log->endAction();
});
