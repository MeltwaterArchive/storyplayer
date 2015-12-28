<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(['Modules', 'Host'])
         ->called("Can check a given process ID is running on localhost");

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPreTestInspection(function() {
    // what are we doing?
    $log = usingLog()->startAction("get Storyplayer's process ID using raw PHP");

    // get the checkpoint - we're going to store data in here
    $checkpoint = getCheckpoint();

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
    $log = usingLog()->startAction("can the Host module check that our process ID exists on 'localhost'?");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = getCheckpoint();

    // let's see what the Host module thinks
    $isRunning = fromHost('localhost')->getPidIsRunning($checkpoint->pid);
    assertsBoolean($isRunning)->isTrue();

    // all done
    $log->endAction();
});
