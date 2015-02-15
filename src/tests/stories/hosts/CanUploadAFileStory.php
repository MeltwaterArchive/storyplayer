<?php

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

$story->addTestTeardown(function() {
    // cleanup after ourselves
    foreach (hostWithRole('upload_target') as $hostname) {
        usingHost($hostname)->runCommand("if [[ -e testfile.txt ]] ; then rm -f testfile.txt ; fi");
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

$story->addAction(function() {
    foreach (hostWithRole('upload_target') as $hostname) {
        usingHost($hostname)->uploadFile(__DIR__ . '/testfile.txt', "testfile.txt");
    }
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    foreach (hostWithRole('upload_target') as $hostname) {
        $result = usingHost($hostname)->runCommand('ls testfile.txt');
        $fileFound = false;
        $lines = explode("\n", $result->output);
        foreach ($lines as $line) {
            if ($line == "testfile.txt") {
                $fileFound = true;
            }
        }

        if (!$fileFound) {
            $msg = "file not found on host '$hostname'";
            usingLog()->writeToLog($msg);
            usingErrors()->throwException($msg);
        }
    }
});