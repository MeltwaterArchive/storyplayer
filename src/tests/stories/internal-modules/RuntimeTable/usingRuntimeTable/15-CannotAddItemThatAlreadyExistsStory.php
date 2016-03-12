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
    "- an existing item in a runtime table",
    "using the RuntimeTable module",
    "if I try to add another item with the same name",
    "- I get an error",
    "afterwards:",
    "- the runtime table will not have changed"
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("add an item to our test table");

    // setup the conditions for this specific test
    $checkpoint = Checkpoint::getCheckpoint();
    $checkpoint->itemName = 'hello';
    $checkpoint->itemValue = 'world';
    $checkpoint->notItemValue = 'trout';

    RuntimeTable::usingRuntimeTable('functional-tests')->addItem($checkpoint->itemName, $checkpoint->itemValue);

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
    $log = Log::usingLog()->startAction("make sure we get an error when we try to add a duplicate item to a runtime table");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story
    Failure::expectsFailure()->when("adding duplicate item", function() use ($checkpoint){
        RuntimeTable::usingRuntimeTable('functional-tests')->addItem($checkpoint->itemName, $checkpoint->notItemValue);
    });

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
    $log = Log::usingLog()->startAction("make sure the runtime table has not changed");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    $actualData = RuntimeTable::fromRuntimeTable('functional-tests')->getItem($checkpoint->itemName);
    Asserts::assertsString($actualData)->equals($checkpoint->itemValue);
    Asserts::assertsString($actualData)->doesNotEqual($checkpoint->notItemValue);

    // all done
    $log->endAction();
});
