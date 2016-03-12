<?php

use Storyplayer\SPv3\Modules\Host;
use Storyplayer\SPv3\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// TEST SETUP / TEARDOWN
//
// ------------------------------------------------------------------------

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    foreach(Host::getHostsWithRole('host_target') as $hostId) {
        // do we have 'screen' installed?
        Host::expectsHost($hostId)->packageIsInstalled('screen');

        // add in a negative test too, in case the other test is returning
        // false positives
        Host::expectsHost($hostId)->packageIsNotInstalled('storyplayer');
    }
});
