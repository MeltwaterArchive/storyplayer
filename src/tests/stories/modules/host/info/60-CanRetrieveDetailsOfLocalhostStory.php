<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Host;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Stories\BuildStory;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    // what are we doing?
    $log = Log::usingLog()->startAction("retrieve the details of 'localhost' using the Shell module");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    $checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story
    $checkpoint->details = Host::fromLocalhost()->getDetails();

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
    $log = Log::usingLog()->startAction("make sure we got some details for 'localhost'");

    // the information to guide our checks is in the checkpoint
    $checkpoint = Checkpoint::getCheckpoint();

    // did we get the data we want?
    Asserts::assertsObject($checkpoint)->hasAttribute('details');
    $details = $checkpoint->details;

    // make sure that this isn't totally empty
    Asserts::assertsObject($checkpoint->details)->hasAttribute('hostId');
    Asserts::assertsObject($checkpoint->details)->hasAttribute('type');

    // all done
    $log->endAction();
});
