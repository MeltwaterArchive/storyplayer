<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Stories\BuildStory;
use StoryplayerInternals\SPv2\Modules\RuntimeTable;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();
$story->setScenario([
    "given:",
    "- an existing runtime table",
    "using the RuntimeTable module",
    "I can access that runtime table"
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("create a test table to operate on");

    // we are going to need this
    $checkpoint = Checkpoint::getCheckpoint();

    // create our table
    $table = RuntimeTable::fromRuntimeTable('functional-tests')->getTable();
    $table->group1 = [
        "hello",
        "this is",
        "an example",
        "runtime table group"
    ];

    // remember the table for later on
    $checkpoint->expectedTable = $table;

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
    $log = Log::usingLog()->startAction("use RuntimeTable to get an existing runtime table");

    // this is where you perform the steps of your user story
    $checkpoint = Checkpoint::getCheckpoint();

    $checkpoint->actualTable = RuntimeTable::fromRuntimeTable('functional-tests')->getTable();

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
    $log = Log::usingLog()->startAction("did we get the expected runtime table?");

    $checkpoint = Checkpoint::getCheckpoint();
    Asserts::assertsObject($checkpoint->actualTable)->equals($checkpoint->expectedTable);

    // all done
    $log->endAction();
});
