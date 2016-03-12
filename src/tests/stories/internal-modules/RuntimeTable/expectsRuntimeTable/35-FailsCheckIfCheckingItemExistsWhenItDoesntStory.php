<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Failure;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Stories\BuildStory;
use StoryplayerInternals\SPv3\Modules\RuntimeTable;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// this format suits a test that focuses on checking for a specific consequence
// of an action (e.g. testing robustness or correctness)
$story->setScenario([
    "given:",
    "- an existing runtime table",
    "using the RuntimeTable module",
    "if I attempt to make sure that an item exists when it doesn't",
    "- I get an error",
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure our test table exist");

    $table = RuntimeTable::fromRuntimeTable('functional-tests')->getTable();
    Asserts::assertsObject($table)->doesNotHaveAttribute('does-not-exist');

    // all done
    $log->endAction();
});

$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("remove our test table");

    // undo anything that you did in addTestSetup()
    RuntimeTable::usingRuntimeTable('functional-tests')->removeTable();

    // all done
    $log->endAction();
});

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure we get an error when checking for an ite, that does not exist");

    // this is where you perform the steps of your user story
    Failure::expectsFailure()->when('checking details', function() {
        RuntimeTable::expectsRuntimeTable('functional-tests')->hasItem('does-not-exist');
    });

    // all done
    $log->endAction();
});
