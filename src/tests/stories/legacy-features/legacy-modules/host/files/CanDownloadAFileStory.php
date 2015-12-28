<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'Host'])
         ->called('Can download a file');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // cleanup after ourselves
    foreach (hostWithRole('upload_target') as $hostname) {
        usingHost($hostname)->uploadFile(__DIR__ . '/testfile.txt', "testfile.txt");
    }
});

$story->addTestTeardown(function() {
    // cleanup after ourselves
    foreach (hostWithRole('upload_target') as $hostname) {
        // remove the file from the test environment
        usingHost($hostname)->runCommand("if [[ -e testfile.txt ]] ; then rm -f testfile.txt ; fi");

        // remove the file from our computer too
        $filename = '/tmp/testfile-' . $hostname . '.txt';
        if (file_exists($filename)) {
            // tidy up
            unlink($filename);
        }
    }
});
// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    foreach (hostWithRole('upload_target') as $hostname) {
        fromHost($hostname)->downloadFile('testfile.txt', "/tmp/testfile-{$hostname}.txt");
    }
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // we should have a file for each host in the configuration
    foreach (hostWithRole('upload_target') as $hostname) {
        $filename = '/tmp/testfile-' . $hostname . '.txt';
        if (!file_exists($filename)) {
            usingLog()->writeToLog("file not downloaded from host '$hostname'");
            usingErrors()->throwException("file '{$filename}' not downloaded");
        }
    }
});