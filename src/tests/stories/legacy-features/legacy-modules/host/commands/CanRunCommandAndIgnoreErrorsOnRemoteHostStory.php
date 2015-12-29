<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Host"])
         ->called("Can run a command on a remote host and ignore any errors");

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
        $result = usingHost($hostId)->runCommandAndIgnoreErrors("kasdksadkasbdka");
        assertsInteger($result->returnCode)->equals(127);
        assertsString($result->output)->isNotEmpty();
    }

    // all done
    $log->endAction();
});
