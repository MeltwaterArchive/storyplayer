<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Failure;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("cannot assert a closure is NULL");

    // this is where you perform the steps of your user story
    Failure::expectsFailure()->when("assertion", function() {
        $closure = function() { echo PHP_EOL; };
        Asserts::assertsNull($closure)->isNull();
    });

    // all done
    $log->endAction();
});
