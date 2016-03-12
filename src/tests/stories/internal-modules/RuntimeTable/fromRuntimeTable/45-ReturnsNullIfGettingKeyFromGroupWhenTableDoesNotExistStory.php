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

// this format suits a test that focuses on checking for a specific consequence
// of an action (e.g. testing robustness or correctness)
$story->setScenario([
    "using the RuntimeTable module",
    "if I attempt to get a key from a group when the runtime table does not exist",
    "- I get NULL",
    "afterwards:",
    "- the runtime table will not exist",
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
    RuntimeTable::expectsRuntimeTable('functional-tests')->doesNotExist();

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
    $log = Log::usingLog()->startAction("make sure NULL is returned when getting key from group in non-existing table");

    // this is where you perform the steps of your user story
    $data = RuntimeTable::fromRuntimeTable('functional-tests')->getItemFromGroup('does-not-exist', 'cannot-exist');
    Asserts::assertsNull($data)->isNull();

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
    $log = Log::usingLog()->startAction("does the table still not exist?");

    $tables = RuntimeTable::fromRuntimeTables()->getAllTables();
    Asserts::assertsObject($tables)->doesNotHaveAttribute('functional-tests');

    // all done
    $log->endAction();
});
