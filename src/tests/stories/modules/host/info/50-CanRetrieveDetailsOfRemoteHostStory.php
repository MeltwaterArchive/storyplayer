<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Host;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("retrieve the details of our test environment");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->details = [];

    // get the details
    foreach(Host::getHostsWithRole('host_target') as $hostId) {
        $checkpoint->details[] = Host::fromHost($hostId)->getDetails();
    }

    // all done
    $log->endAction();
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure we got some details for our test environment");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // did we get the data we want?
    Asserts::assertsObject($checkpoint)->hasAttribute('details');
    foreach ($checkpoint->details as $details) {
        // make sure that this isn't totally empty
        Asserts::assertsObject($details)->hasAttribute('hostId');
        Asserts::assertsObject($details)->hasAttribute('type');
    }

    // all done
    $log->endAction();
});
