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
    "I can access an existing group inside that table"
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
    $checkpoint->expectedGroup = [
        "hello",
        "this is",
        "an example",
        "runtime table group"
    ];

    $table = RuntimeTable::fromRuntimeTable('functional-tests')->getTable();
    $table->group1 = $checkpoint->expectedGroup;

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
    $log = Log::usingLog()->startAction("use RuntimeTable to get an existing group");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story
    $group = RuntimeTable::fromRuntimeTable('functional-tests')->getGroupFromTable('group1');
    Asserts::assertsArray($group)->isExpectedType();
    $checkpoint->actualGroup = $group;

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
    $log = Log::usingLog()->startAction("did we get the group we expected?");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    Asserts::assertsArray($checkpoint->actualGroup)->equals($checkpoint->expectedGroup);

    // all done
    $log->endAction();
});
