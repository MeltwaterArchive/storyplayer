<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Stories\BuildStory;
use StoryplayerInternals\SPv3\Modules\RuntimeTable;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// this format suits a functional test
$story->setScenario([
    "using the RuntimeTable module",
    "I can check that a runtime table exists",
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
    $log = Log::usingLog()->startAction("check that our test table does not exist");

    RuntimeTable::expectsRuntimeTable('functional-tests')->doesNotExist();

    // all done
    $log->endAction();
});
