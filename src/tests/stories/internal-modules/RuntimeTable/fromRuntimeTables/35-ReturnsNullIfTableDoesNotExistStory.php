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
    "if I attempt to get a table that does not exist",
    "- I get NULL"
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("make sure our test table does not exist");

    // remove the table if it exists
    RuntimeTable::usingRuntimeTables()->removeTable('functional-tests');
    RuntimeTable::expectsRuntimeTable('functional-tests')->doesNotExist();

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

    // this is where you perform the steps of your user story
    $actualTable = RuntimeTable::fromRuntimeTables()->getTable('functional-tests');
    Asserts::assertsNull($actualTable)->isNull();

    // all done
    $log->endAction();
});
