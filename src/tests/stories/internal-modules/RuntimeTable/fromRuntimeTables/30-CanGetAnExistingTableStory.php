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
    "given:",
    "- an existing runtime table",
    "using the RuntimeTable module",
    "I can get the runtime table",
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure our test table exists");

    // we're going to track our test table here
    $checkpoint = Checkpoint::getCheckpoint();

    // build the table
    $table = RuntimeTable::usingRuntimeTables()->createTable('functional-tests');
    RuntimeTable::expectsRuntimeTable('functional-tests')->exists();

    $table->group1 = [
        "hello" => "world"
    ];

    // remember the table, for later comparisons
    $checkpoint->expectedTable = $table;

    // all done
    $log->endAction();
});

$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("remove out test table");

    // undo anything that you did in addTestSetup()
    RuntimeTable::usingRuntimeTables()->removeTable('functional-tests');
    RuntimeTable::expectsRuntimeTable('functional-tests')->doesNotExist();

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
    $log = Log::usingLog()->startAction("get our test table");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story
    $checkpoint->actualTable = RuntimeTable::fromRuntimeTables()->getTable('functional-tests');

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
    $log = Log::usingLog()->startAction("did we get the table we expect?");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();
    Asserts::assertsObject($checkpoint)->hasAttribute('expectedTable');
    Asserts::assertsObject($checkpoint)->hasAttribute('actualTable');

    // what did we get?
    $expectedTable = $checkpoint->expectedTable;
    $actualTable = $checkpoint->actualTable;
    Asserts::assertsObject($actualTable)->isSameAs($expectedTable);

    // all done
    $log->endAction();
});
