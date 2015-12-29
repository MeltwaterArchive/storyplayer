<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Host"])
         ->called("Can run a command on a remote host as a named user and ignore any errors");

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = usingLog()->startAction("run a command as a user");

    foreach(hostWithRole("host_target") as $hostId) {
        $user = fromHost($hostId)->getStorySetting("user.username");
        $result = usingHost($hostId)->runCommandAsUserAndIgnoreErrors("kasdksadkasbdka", $user);
        assertsInteger($result->returnCode)->equals(127);
        assertsString($result->output)->isNotEmpty();
    }

    // all done
    $log->endAction();
});
