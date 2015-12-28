<?php

use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Log;
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
    // what are we doing?
    $log = Log::usingLog()->startAction("set the name of the screen session for this test");

    // use the checkpoint to share the name of our screen session
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->session = "storyplayer_test_session";

    // all done
    $log->endAction();
});

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure our test session is not running in our test environment");

    $checkpoint = Checkpoint::getCheckpoint();

    // make sure the session isn't running on the host
    $details = Screen::fromLocalhost()->getScreenSessionDetails($checkpoint->session);
    if ($details) {
        Screen::onLocalhost()->stopScreen($checkpoint->session);
    }

    // all done
    $log->endAction();
});

$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("stop our test session if it has been left running");

    $checkpoint = Checkpoint::getCheckpoint();

    // if we've left the session running, go and kill it off
    $details = Screen::fromLocalhost()->getScreenSessionDetails($checkpoint->session);
    if ($details) {
        Screen::onLocalhost()->stopScreen($checkpoint->session);
    }

    // all done
    $log->endAction();
});

$story->addPreTestPrediction(function(){
    throw Exceptions::newStoryShouldFailException();
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("run a short screen session on 'localhost'");

    $checkpoint = Checkpoint::getCheckpoint();

    // this will cause the screen session to terminate straight away
    // this story relies on startInScreen() failing and throwing an exception
    //
    // if you are on OSX, and using Apple's supplied `screen` build, then
    // startInScreen() will not throw an exception. this is because Apple's
    // version of `screen` does not terminate until Storyplayer itself
    // terminates.
    //
    // to work around this, you must install `screen` either from Homebrew
    // or Macports, and make sure that version of `screen` is in your PATH
    Screen::onLocalhost()->startScreen($checkpoint->session, "ls");

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
    $log = Log::usingLog()->startAction("check localhost to see if the screen session is still running");

    $checkpoint = Checkpoint::getCheckpoint();

    Screen::expectsLocalhost()->screenIsRunning($checkpoint->session);

    // all done
    $log->endAction();
});
