<?php

use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Modules\Screen;
use Storyplayer\SPv2\Modules\Shell;
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
    // what are we doing?
    $log = Log::usingLog()->startAction("start up a bunch of screen sessions on localhost");

    // use the checkpoint to share the name of our screen session
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->sessions = [
        "storyplayer_test_session_1",
        "storyplayer_test_session_2",
        "storyplayer_test_session_3",
        "storyplayer_test_session_4",
        "storyplayer_test_session_5",
    ];

    // make sure the session is running on each host
    foreach ($checkpoint->sessions as $session) {
        $details = Screen::fromLocalhost()->getScreenSessionDetails($session);
        if (!$details) {
            Screen::onLocalhost()->startScreen($session, 'top');
        }
    }

    // all done
    $log->endAction();
});

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure our screen sessions started up");

    $checkpoint = Checkpoint::getCheckpoint();
    foreach ($checkpoint->sessions as $session) {
        Screen::expectsLocalhost()->screenIsRunning($session);
    }

    // all done
    $log->endAction();
});

$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("stop any screen sessions that we left behind");

    $checkpoint = Checkpoint::getCheckpoint();
    foreach ($checkpoint->sessions as $session) {
        $details = Screen::fromLocalhost()->getScreenSessionDetails($session);
        if ($details) {
            Screen::onLocalhost()->stopScreen($session);
        }
    }

    // all done
    $log->endAction();
});

$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("restart Selenium Server");

    Shell::onLocalhost()->runCommand("vendor/bin/selenium-server.sh start");

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
    $log = Log::usingLog()->startAction("stop all screens on 'localhost'");

    Screen::onLocalhost()->stopAllScreens();

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
    $log = Log::usingLog()->startAction("did we stop all the screens that we started on 'localhost'?");

    $checkpoint = Checkpoint::getCheckpoint();
    foreach ($checkpoint->sessions as $session) {
        Screen::expectsLocalhost()->screenIsNotRunning($session);
    }

    // all done
    $log->endAction();
});
