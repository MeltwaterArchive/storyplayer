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
    "given",
    "- an existing runtime table",
    "using RuntimeTable",
    "if I try to get a non-existing key from that runtime table",
    "- I get NULL",
    "afterwards:",
    "- the key will not exist in the runtime table"
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("create a test table to operate on");

    $table = RuntimeTable::fromRuntimeTable('functional-tests')->getTable();
    RuntimeTable::expectsRuntimeTable('functional-tests')->exists();

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
    $log = Log::usingLog()->startAction("make sure NULL is returned when getting a non-existing key from the table");

    // this is where you perform the steps of your user story
    $details = RuntimeTable::fromRuntimeTable('functional-tests')->getItem('does-not-exist');
    Asserts::assertsNull($details)->isNull();

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
    $log = Log::usingLog()->startAction("does the key still not exist inside the table?");

    $tables = RuntimeTable::fromRuntimeTables()->getAllTables();
    Asserts::assertsObject($tables)->hasAttribute('functional-tests');
    Asserts::assertsObject($tables->{'functional-tests'})->doesNotHaveAttribute('does-not-exist');

    // all done
    $log->endAction();
});
