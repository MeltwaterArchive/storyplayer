<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Config')
         ->called('Can merge system-under-test params into test environment config');

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
    $expected = fromConfig()->get("systemundertest.roles.0.params.filename");
    $actual   = fromConfig()->get("target.groups.0.details.machines.default.params.filename");

    assertsString($actual)->equals($expected);
});