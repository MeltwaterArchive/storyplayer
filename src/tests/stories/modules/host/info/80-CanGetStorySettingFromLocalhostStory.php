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
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("add some story settings to 'localhost'");

    // setup the conditions for this specific test
    $checkpoint = Checkpoint::getCheckpoint();

    // this is something that you should never copy and use in your own
    // stories
    //
    // I'm using it as a workaround because there are no appSettings defined
    // for 'localhost', because (today) 'localhost' is auto-generated and
    // is not defined in a config file

    $hostDetails = Host::fromLocalhost()->getDetails();
    $hostDetails->storySettings = new BaseObject;
    $hostDetails->storySettings->mergeFrom((object)[
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
    $checkpoint->expectedSettings = $hostDetails->storySettings->zmq->single;

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
    $log = Log::usingLog()->startAction("get the 'zmq.single' story setting for 'localhost'");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // get the app settings
    $checkpoint->actualSettings = Host::fromLocalhost()->getStorySetting("zmq.single");

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
    Asserts::assertsObject($checkpoint->actualSettings)->equals($checkpoint->expectedSettings);

    // all done
    $log->endAction();
});
