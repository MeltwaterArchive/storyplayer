<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Hosts')
         ->called('Can upload a file');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestTeardown(function(StoryTeller $st) {
    // cleanup after ourselves
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        $st->usingHost($hostname)->runCommand("if [[ -e testfile.txt ]] ; then rm -f testfile.txt ; done");
    }
});

// ========================================================================
//
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

// there is no preflight check, as this story should always succeed

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

// there is no preflight inspection

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function(StoryTeller $st) {
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        $st->usingHost($hostname)->uploadFile(__DIR__ . '/testfile.txt', "testfile.txt");
    }
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function(StoryTeller $st) {
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        $result = $st->usingHost($hostname)->runCommand('ls testfile.txt');
        $fileFound = false;
        $lines = explode("\n", $result->output);
        foreach ($lines as $line) {
            if ($line == "testfile.txt") {
                $fileFound = true;
            }
        }

        if (!$fileFound) {
            $st->usingLog()->writeToLog("file not found on host '$hostname'");
            throw new E5xx_ActionFailed(__METHOD__);
        }
    }
});