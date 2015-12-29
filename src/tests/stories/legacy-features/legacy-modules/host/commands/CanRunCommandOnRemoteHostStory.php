<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Host"])
         ->called("Can run a command on a remote host");

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = usingLog()->startAction("run a command");

    foreach(hostWithRole("host_target") as $hostId) {
        $result = usingHost($hostId)->runCommand("ls");
        assertsInteger($result->returnCode)->equals(0);
        assertsString($result->output)->isNotEmpty();
    }

    // all done
    $log->endAction();
});
