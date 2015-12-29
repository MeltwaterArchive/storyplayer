<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Shell"])
         ->called("Can run a command on localhost");

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = usingLog()->startAction("run a command");

    $result = usingShell()->runCommand("ls");
    assertsInteger($result->returnCode)->equals(0);
    assertsString($result->output)->isNotEmpty();

    // all done
    $log->endAction();
});
