<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Host;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("check that our test environment up and running");

    foreach(Host::getHostsWithRole('host_target') as $hostId) {
        $isRunning = Host::fromHost($hostId)->getHostIsRunning();
        Asserts::assertsBoolean($isRunning)->isTrue();
    }

    // all done
    $log->endAction();
});
