<?php

use DataSift\Stone\ObjectLib\BaseObject;
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
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("work out what data we expect");

    // store our expected data
    $checkpoint = Checkpoint::getCheckpoint();

    // these are the story settings that our test hosts should have
    $checkpoint->expectedSettings = [];

    $expectedSettings = new BaseObject;
    $expectedSettings->mergeFrom((object)[
        "inPort"  => 5000,
        "outPort" => 5001,
    ]);

    foreach (Host::getHostsWithRole('host_target') as $hostId) {
        $checkpoint->expectedSettings[$hostId] = $expectedSettings;
    }

    // all done
    $log->endAction();
});

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("get the 'zmq.single' story setting for our test environment host(s)");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->actualSettings = [];

    // get the app settings
    foreach (Host::getHostsWithRole("host_target") as $hostId) {
        $checkpoint->actualSettings[$hostId] = Host::fromHost($hostId)->getStorySetting("zmq.single");
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
    $log = Log::usingLog()->startAction("did we get the story setting for 'localhost'?");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // what did we get?
    Asserts::assertsArray($checkpoint->actualSettings)->equals($checkpoint->expectedSettings);

    // all done
    $log->endAction();
});
