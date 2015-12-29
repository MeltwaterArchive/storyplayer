<?php

use DataSift\Stone\ObjectLib\BaseObject;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'Host'])
         ->called('Host: Can get appSetting using legacy params');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// TEST SETUP / TEARDOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // this is something that you should never copy and use in your own
    // stories
    foreach(hostWithRole('host_target') as $hostId) {
        $hostDetails = fromHost($hostId)->getDetails();
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
    }

    // this is what we expect to retrieve from the test environment config
    $checkpoint = getCheckpoint();
    $checkpoint->expected = $hostDetails->appSettings->host->expected;
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	// do nothing
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
	// what value do we expect to retrieve?
	$expected = fromCheckpoint()->get('expected');

	// make sure we have it
    foreach(hostWithRole('host_target') as $hostId) {
    	$actual = fromHost($hostId)->getAppSetting('host', 'expected');
    	assertsString($actual)->equals($expected);
    }
});