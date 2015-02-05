<?php

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

$story->addTestSetup(function($st) {
    // cleanup after ourselves
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        $st->usingHost($hostname)->uploadFile(__DIR__ . '/testfile.txt', "testfile.txt");
    }
});

$story->addTestTeardown(function($st) {
    // cleanup after ourselves
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        // remove the file from the test environment
        $st->usingHost($hostname)->runCommand("if [[ -e testfile.txt ]] ; then rm -f testfile.txt ; fi");

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

$story->addAction(function($st) {
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        $st->fromHost($hostname)->downloadFile('testfile.txt', "/tmp/testfile-{$hostname}.txt");
    }
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
    // we should have a file for each host in the configuration
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        $filename = '/tmp/testfile-' . $hostname . '.txt';
        if (!file_exists($filename)) {
            $st->usingLog()->writeToLog("file not downloaded from host '$hostname'");
            $st->usingErrors()->throwException("file '{$filename}' not downloaded");
        }
    }
});