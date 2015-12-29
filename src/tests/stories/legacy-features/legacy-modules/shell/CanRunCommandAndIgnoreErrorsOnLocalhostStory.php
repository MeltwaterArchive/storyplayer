<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Shell"])
         ->called("Can run a command on localhost and ignore any errors that occur");

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = usingLog()->startAction("run a command");

    $result = usingShell()->runCommandAndIgnoreErrors("kasdksadkasbdka");
    assertsInteger($result->returnCode)->equals(127);
    assertsString($result->output)->isNotEmpty();

    // all done
    $log->endAction();
});
