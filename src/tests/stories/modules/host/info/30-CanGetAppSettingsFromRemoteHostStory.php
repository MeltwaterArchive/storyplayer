<?php

use DataSift\Stone\ObjectLib\BaseObject;
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
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("add some appSettings to our test environment");

    // setup the conditions for this specific test
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->expectedSettings = [];

    // this is something that you should never copy and use in your own
    // stories
    //
    // I'm using it as a workaround because there are no appSettings defined
    // for 'localhost', because (today) 'localhost' is auto-generated and
    // is not defined in a config file

    foreach(Host::getHostsWithRole('host_target') as $hostId) {
        $hostDetails = Host::fromHost($hostId)->getDetails();
        $hostDetails->appSettings = new BaseObject;
        $hostDetails->appSettings->mergeFrom((object)[
            "host" => (object)[
                "expected" => "successfully retrieved this storySetting :)",
            ],
            "http" => (object)[
                "homepage" => "https://storyplayer.test/",
            ],
            "user" => (object)[
                "username" => "vagrant",
                "group"    => "vagrant",
            ],
            "zmq" => (object)[
                "single" => (object)[
                    "inPort"  => 5000,
                    "outPort" => 5001,
                ],
                "multi"  => (object)[
                    "inPort"  => 5002,
                    "outPort" => 5003,
                ],
            ]
        ]);

        // the appSettings that we expect
        $checkpoint->expectedSettings[] = $hostDetails->appSettings->zmq;
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
    $log = Log::usingLog()->startAction("get the appSetting 'zmq.single' from our test environment");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->actualSettings = [];

    // get the app settings
    foreach(Host::getHostsWithRole('host_target') as $hostId) {
        $checkpoint->actualSettings[] = Host::fromHost($hostId)->getAppSettings("zmq");
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
    $log = Log::usingLog()->startAction("did we get the appSetting from our test environment?");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // what did we get?
    Asserts::assertsArray($checkpoint->actualSettings)->equals($checkpoint->expectedSettings);

    // all done
    $log->endAction();
});
