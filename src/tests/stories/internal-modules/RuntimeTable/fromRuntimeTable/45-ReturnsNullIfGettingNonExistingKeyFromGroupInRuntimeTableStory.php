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

// this format suits a test that focuses on checking for a specific consequence
// of an action (e.g. testing robustness or correctness)
$story->setScenario([
    "given:",
    "- an existing group in a runtime table",
    "using the RuntimeTable module",
    "if I attempt to get a non-existing key from that group",
    "- I get NULL",
    "afterwards:",
    "- the key will not exist inside that table",
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("create our test table");

    $table = RuntimeTable::fromRuntimeTable('functional-tests')->getTable();
    $table->group1 = (object)[
        "hello" => "world",
    ];

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
    $log = Log::usingLog()->startAction("make sure NULL is returned when getting non-existing key from group in table");

    // this is where you perform the steps of your user story
    $data = RuntimeTable::fromRuntimeTable('functional-tests')->getItemFromGroup('group1', 'does-not-exist');
    Asserts::assertsNull($data)->isNull();

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
    $log = Log::usingLog()->startAction("does the group still not exist?");

    $tables = RuntimeTable::fromRuntimeTables()->getAllTables();
    Asserts::assertsObject($tables)->hasAttribute('functional-tests');
    Asserts::assertsObject($tables->{'functional-tests'})->hasAttribute('group1');
    Asserts::assertsObject($tables->{'functional-tests'}->group1)->doesNotHaveAttribute('does-not-exist');

    // all done
    $log->endAction();
});
