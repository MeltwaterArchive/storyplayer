<?php

use Storyplayer\SPv2\Modules\Asserts;
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
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // this is the session name that we're going to check for
    // it is deliberately garbage, to ensure that there is no such screen
    // running on our localhost
    $sessionName = 'lasdhasigdq823e2bkaadadadalj';

    // what are we doing?
    $log = Log::usingLog()->startAction("make sure our test screen session is not running");

    // is it running?
    Screen::expectsLocalhost()->screenIsNotRunning($sessionName);

    // all done
    $log->endAction();
});
