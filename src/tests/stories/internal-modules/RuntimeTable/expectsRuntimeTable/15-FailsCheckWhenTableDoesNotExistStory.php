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
    "if I attempt to check that a runtime table exists",
    "when it does not",
    "- I get an error",
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure our test table does not exist");

    RuntimeTable::usingRuntimeTable('functional-tests')->removeTable();
    $tables = RuntimeTable::fromRuntimeTables()->getAllTables();
    Asserts::assertsObject($tables)->doesNotHaveAttribute('functional-tests');

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
    $log = Log::usingLog()->startAction("describe what we are doing");

    // this is where you perform the steps of your user story
    Failure::expectsFailure()->when("checking table exists", function() {
        RuntimeTable::expectsRuntimeTable('functional-tests')->exists();
    });

    // all done
    $log->endAction();
});
