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
    "- a non-existing runtime table",
    "using the RuntimeTable module",
    "I can access that runtime table",
    "afterwards:",
    "- the runtime table will exist",
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
    $log = Log::usingLog()->startAction("use RuntimeTable to get a non-existing runtime table");

    // this is where you perform the steps of your user story
    $checkpoint = Checkpoint::getCheckpoint();

    $checkpoint->table = RuntimeTable::fromRuntimeTable('functional-tests')->getTable();

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
    $log = Log::usingLog()->startAction("did we get a runtime table?");

    $checkpoint = Checkpoint::getCheckpoint();
    Asserts::assertsObject($checkpoint->table)->isExpectedType();

    // all done
    $log->endAction();
});

$story->addPostTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("does our table now exist?");

    $tables = RuntimeTable::fromRuntimeTables()->getAllTables();
    Asserts::assertsObject($tables)->hasAttribute("functional-tests");

    // all done
    $log->endAction();
});
