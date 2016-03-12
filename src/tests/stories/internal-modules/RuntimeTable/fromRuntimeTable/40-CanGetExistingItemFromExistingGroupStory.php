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

$story->setScenario([
    "given:",
    "- an existing group inside a runtime table",
    "using the RuntimeTable module",
    "I can retrieve an existing key inside that group",
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
    $table->group1 = (object)[
        "key1" => "this test has been successful!"
    ];

    // remember the table for later on
    $checkpoint->expectedData = $table->group1->key1;

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
    $log = Log::usingLog()->startAction("use the RuntimeTable module to get the existing key");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story
    $checkpoint->actualData = RuntimeTable::fromRuntimeTable('functional-tests')->getItemFromGroup("group1", "key1");

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
    $log = Log::usingLog()->startAction("did we get the data from the key?");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // did we get what we wanted?
    Asserts::assertsString($checkpoint->actualData)->equals($checkpoint->expectedData);

    // all done
    $log->endAction();
});
