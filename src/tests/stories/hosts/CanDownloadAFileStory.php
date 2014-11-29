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
         ->called('Can download a file');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function(StoryTeller $st) {
    // cleanup after ourselves
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        $st->usingHost($hostname)->uploadFile(__DIR__ . '/testfile.txt', "testfile.txt");
    }
});

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
        $st->fromHost($hostname)->downloadFile('testfile.txt', "/tmp/testfile-{$hostname}.txt");
    }
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function(StoryTeller $st) {
    // we should have a file for each host in the configuration
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        $filename = '/tmp/testfile-' . $hostname . '.txt';
        if (file_exists($filename)) {
            // tidy up
            unlink($filename);
        }
        else
        {
            $st->usingLog()->writeToLog("file not downloaded from host '$hostname'");
            throw new E5xx_ActionFailed(__METHOD__);
        }
    }
});