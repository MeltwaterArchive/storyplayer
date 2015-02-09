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

$story->addAction(function($st) {
    foreach (hostWithRole($st, 'ssl_target') as $hostname) {
        $url = $st->fromHost($hostname)->getAppSetting("http", "homepage");
        $st->fromHttp()->get($url);
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