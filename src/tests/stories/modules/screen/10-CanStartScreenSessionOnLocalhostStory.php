<?php

use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Screen;
use Storyplayer\SPv2\Stories\BuildStory;

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

$story->addTestSetup(function() {
    // use the checkpoint to share the name of our screen session
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->session = "storyplayer_test_session";

    // make sure the session isn't running on the host
    $details = Screen::fromLocalhost()->getScreenSessionDetails($checkpoint->session);
    if ($details) {
        Screen::onLocalhost()->stopScreen($checkpoint->session);
    }
});

$story->addTestTeardown(function() {
    $checkpoint = Checkpoint::getCheckpoint();

    // if we've left the session running, go and kill it off
    $details = Screen::fromLocalhost()->getScreenSessionDetails($checkpoint->session);
    if ($details) {
        Screen::onLocalhost()->stopScreen($checkpoint->session);
    }
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    $checkpoint = Checkpoint::getCheckpoint();

    Screen::onLocalhost()->startScreen($checkpoint->session, "top");
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    $checkpoint = Checkpoint::getCheckpoint();

    Screen::expectsLocalhost()->screenIsRunning($checkpoint->session);
});
