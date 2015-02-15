<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Config')
         ->called('Can get system-under-test appSettings');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // nothing to do
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    $appSettings = fromSystemUnderTest()->getAppSettings('testData');

    assertsObject($appSettings)->isNotNull();
    assertsObject($appSettings)->hasAttribute('name');
    assertsObject($appSettings)->hasAttribute('version');
});