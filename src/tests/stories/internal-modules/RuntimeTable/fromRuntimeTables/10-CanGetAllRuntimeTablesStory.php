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

// this format suits a functional test
$story->setScenario([
    // what are the pre-conditions?
    "given:",
    "- two runtime tables",
    "using the RuntimeTable module",
    "I can get all the existing runtime tables",
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("create some runtime tables");

    // setup the conditions for this specific test
    $checkpoint = Checkpoint::getCheckpoint();

    // create the tables
    $table1 = RuntimeTable::fromRuntimeTable('test-table-1')->getTable();
    $table2 = RuntimeTable::fromRuntimeTable('test-table-2')->getTable();

    $table1->group1 = [
        "hello" => "world",
    ];
    $table2->group2 = [
        "goodbye" => "and goodnight"
    ];

    // remember them for later
    $checkpoint->table1 = $table1;
    $checkpoint->table2 = $table2;

    // all done
    $log->endAction();
});

$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("remove our test tables");

    // undo anything that you did in addTestSetup()
    RuntimeTable::usingRuntimeTable('test-table-1')->removeTable();
    RuntimeTable::usingRuntimeTable('test-table-2')->removeTable();

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
    $log = Log::usingLog()->startAction("get all the runtime tables");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story
    $checkpoint->tables = RuntimeTable::fromRuntimeTables()->getAllTables();

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
    $log = Log::usingLog()->startAction("did we get all the tables?");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    Asserts::assertsObject($checkpoint->tables)->hasAttribute('test-table-1');
    Asserts::assertsObject($checkpoint->tables->{'test-table-1'})->equals($checkpoint->table1);
    Asserts::assertsObject($checkpoint->tables)->hasAttribute('test-table-2');
    Asserts::assertsObject($checkpoint->tables->{'test-table-2'})->equals($checkpoint->table2);

    // all done
    $log->endAction();
});
