<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Config')
         ->called('Can get system-under-test storySettings');

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
    $storySettings = fromSystemUnderTest()->getStorySetting('testData');

    assertsObject($storySettings)->isNotNull();
    assertsObject($storySettings)->hasAttribute('name');
    assertsObject($storySettings)->hasAttribute('version');
    assertsObject($storySettings)->hasAttribute('isStorySettings');
});