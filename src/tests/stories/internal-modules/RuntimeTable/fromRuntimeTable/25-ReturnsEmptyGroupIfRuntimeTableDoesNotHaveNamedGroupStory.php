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
    "I can get a group",
    "even if:",
    "- the named group does not exist in the existing runtime table",
    "afterwards:",
    "- the named group will exist inside that runtime table"
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
    $log = Log::usingLog()->startAction("use RuntimeTable to get a group that does not exit from a table");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story
    $checkpoint->group = RuntimeTable::fromRuntimeTable('functional-tests')->getGroupFromTable("does-not-exist");

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
    $log = Log::usingLog()->startAction("did we get an empty group back?");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // check the group
    Asserts::assertsObject($checkpoint->group)->isEmpty();

    // all done
    $log->endAction();
});

$story->addPostTestInspection(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("does our group now exist?");

    $tables = RuntimeTable::fromRuntimeTables()->getAllTables();
    Asserts::assertsObject($tables)->hasAttribute('functional-tests');
    Asserts::assertsObject($tables->{'functional-tests'})->hasAttribute('does-not-exist');

    // all done
    $log->endAction();
});
