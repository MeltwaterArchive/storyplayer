<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'Host'])
         ->called('Can check that an operating system package is installed');

$story->requiresStoryplayerVersion(2);

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
    foreach(hostWithRole('host_target') as $hostId) {
        // do we have 'screen' installed?
        expectsHost($hostId)->packageIsInstalled('screen');

        // add in a negative test too, in case the other test is returning
        // false positives
        expectsHost($hostId)->packageIsNotInstalled('storyplayer');
    }
});