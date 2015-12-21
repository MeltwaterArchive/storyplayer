<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Modules')
         ->called('HTTP: Can connect to self-signed SSL server');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    foreach (hostWithRole('ssl_target') as $hostname) {
        $url = "https://" . fromHost($hostname)->getHostname();
        fromHttp()->get($url);
    }
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
    // do nothing
});