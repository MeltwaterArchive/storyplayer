<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Shell"])
         ->called("Can expect that a named process is running on localhost");

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = usingLog()->startAction("make sure that the Shell module sees that Storyplayer is running on 'localhost'");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = getCheckpoint();

    // what does the Shell module think?
    $isRunning = expectsShell()->processIsRunning("php");

    // all done
    $log->endAction();
});