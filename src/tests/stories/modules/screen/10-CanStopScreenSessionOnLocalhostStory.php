<?php

use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Modules\Screen;
use Storyplayer\SPv3\Stories\BuildStory;

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
    // what are we doing?
    $log = Log::usingLog()->startAction("start a screen session");

    // use the checkpoint to share the name of our screen session
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->session = "storyplayer_test_session";

    // make sure the session is running on each host
    $details = Screen::fromLocalhost()->getScreenSessionDetails($checkpoint->session);
    if (!$details) {
        Screen::onLocalhost()->startScreen($checkpoint->session, 'top');
    }

    // all done
    $log->endAction();
});

$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("stop any screen sessions that we have left behind");

    $checkpoint = Checkpoint::getCheckpoint();

    // if we've left the session running, go and kill it off
    $details = Screen::fromLocalhost()->getScreenSessionDetails($checkpoint->session);
    if ($details) {
        Screen::onLocalhost()->stopScreen($checkpoint->session);
    }

    // all done
    $log->endAction();
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("stop our screen session");

    $checkpoint = Checkpoint::getCheckpoint();
    Screen::onLocalhost()->stopScreen($checkpoint->session);

    // all done
    $log->endAction();
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("did we stop our screen session?");

    $checkpoint = Checkpoint::getCheckpoint();
    Screen::expectsLocalhost()->screenIsNotRunning($checkpoint->session);

    // all done
    $log->endAction();
});
