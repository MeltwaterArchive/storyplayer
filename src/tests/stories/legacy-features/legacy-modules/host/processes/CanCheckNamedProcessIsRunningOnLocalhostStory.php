<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'Host'])
         ->called('Can check named process is running on localhost');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = usingLog()->startAction("can the Hosts module see that Storyplayer is running on 'localhost'?");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = getCheckpoint();

    // what does the Shell module think?
    $isRunning = fromHost('localhost')->getProcessIsRunning("php");
    assertsBoolean($isRunning)->isTrue();

    // all done
    $log->endAction();
});