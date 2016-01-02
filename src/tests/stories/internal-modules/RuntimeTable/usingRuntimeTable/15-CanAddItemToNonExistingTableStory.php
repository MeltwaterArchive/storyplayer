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
    "using the RuntimeTable module",
    "I can add data to a runtime table",
    "even when that table doesn't yet exist",
    "afterwards:",
    "- the runtime table will exist",
    "- the data will exist inside the runtime table",
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure our test table does not exist");

    // setup the conditions for this specific test
    $checkpoint = Checkpoint::getCheckpoint();

    // create the table
    $table = RuntimeTable::usingRuntimeTable('functional-tests')->removeTable();
    RuntimeTable::expectsRuntimeTable('functional-tests')->doesNotExist();

    // all done
    $log->endAction();
});

$story->addTestTeardown(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("remove our test tables");

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
    $log = Log::usingLog()->startAction("add an item to our test table");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->expectedData = "world";

    // this is where you perform the steps of your user story
    RuntimeTable::usingRuntimeTable('functional-tests')->addItem("hello", "world");

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
    $log = Log::usingLog()->startAction("did the table get created?");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    RuntimeTable::expectsRuntimeTable('functional-tests')->exists();

    // all done
    $log->endAction();
});

$story->addPostTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("did the item get added to the table?");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    RuntimeTable::expectsRuntimeTable('functional-tests')->hasItem('hello');
    $actualData = RuntimeTable::fromRuntimeTable('functional-tests')->getItem('hello');
    Asserts::assertsString($actualData)->equals($checkpoint->expectedData);

    // all done
    $log->endAction();
});
