<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor("Storyplayer")
         ->inGroup(["Modules", "Host"])
         ->called("Can run command on remote host as a named user");

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
        $result = usingHost($hostId)->runCommandAsUser("ls", $user);
        assertsInteger($result->returnCode)->equals(0);
        assertsString($result->output)->isNotEmpty();
    }

    // all done
    $log->endAction();
});
