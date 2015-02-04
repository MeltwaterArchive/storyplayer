<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;

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

$story->addAction(function(StoryTeller $st) {
    // nothing to do
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function(StoryTeller $st) {
    $appSettings = $st->fromSystemUnderTest()->getAppSettings('testData');

    $st->assertsObject($appSettings)->isNotNull();
    $st->assertsObject($appSettings)->hasAttribute('name');
    $st->assertsObject($appSettings)->hasAttribute('version');
});