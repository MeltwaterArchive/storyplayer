<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'Hosts'])
         ->called('Can stat a file');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // cleanup after ourselves
    foreach (hostWithRole('upload_target') as $hostId) {
        usingHost($hostId)->uploadFile(__DIR__ . '/testfile.txt', "testfile.txt");
        usingHost($hostId)->runCommand("chmod 644 testfile.txt");
    }
});

$story->addTestTeardown(function() {
    // cleanup after ourselves
    foreach (hostWithRole('upload_target') as $hostId) {
        // remove the file from the test environment
        usingHost($hostId)->runCommand("if [[ -e testfile.txt ]] ; then rm -f testfile.txt ; fi");
    }
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    foreach (hostWithRole('upload_target') as $hostId) {
        // get the default user details for this test environment
        $hostUser  = fromHost($hostId)->getStorySetting("user.username");
        $hostGroup = fromHost($hostId)->getStorySetting("user.group");

        // get the details for this file
        $details = fromHost($hostId)->getFileDetails('testfile.txt');

        // make sure we have the details that we expect
        assertsObject($details)->isNotNull();
        assertsObject($details)->hasAttribute("user");
        assertsString($details->user)->equals($hostUser);
        assertsObject($details)->hasAttribute("group");
        assertsString($details->group)->equals($hostGroup);
    }
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    foreach (hostWithRole('upload_target') as $hostId) {
        // get the default user details for this test environment
        $hostUser  = fromHost($hostId)->getStorySetting("user.username");
        $hostGroup = fromHost($hostId)->getStorySetting("user.group");

        // check the details for this file
        expectsHost($hostId)->hasFileWithPermissions('testfile.txt', $hostUser, $hostGroup, 0644);
    }
});