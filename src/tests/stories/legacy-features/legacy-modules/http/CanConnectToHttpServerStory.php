<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'HTTP'])
         ->called('Can connect to HTTP server');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    $checkpoint = getCheckpoint();
    $checkpoint->responses = [];

    foreach (hostWithRole('http_target') as $hostname) {
        $url = "http://" . fromHost($hostname)->getHostname();
        $checkpoint->responses[] = fromHttp()->get($url);
    }
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
    $checkpoint = getCheckpoint();
    assertsObject($checkpoint)->hasAttribute("responses");
    assertsArray($checkpoint->responses)->isExpectedType();

    foreach ($checkpoint->responses as $response) {
        expectsHttpResponse($response)->hasStatusCode(200);
    }
});