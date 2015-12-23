<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'HTTP'])
         ->called('Can POST to HTTP server');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    $checkpoint = getCheckpoint();

    // where are we going?
    $hostname = fromFirstHostWithRole('http_target')->getHostname();
    $url = "http://{$hostname}/";

    usingHttp()->post(
        $url
    );
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
});