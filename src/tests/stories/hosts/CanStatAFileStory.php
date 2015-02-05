<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Hosts')
         ->called('Can stat a file');

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
    }
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function($st) {
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        // get the default user details for this test environment
        $hostUser = $st->fromHost($hostname)->getAppSetting("user", "username");
        $hostGroup = $st->fromHost($hostname)->getAppSetting("user", "group");

        // get the details for this file
        $details = $st->fromHost($hostname)->getFileDetails('testfile.txt');

        // make sure we have the details that we expect
        $st->assertsObject($details)->isNotNull();
        $st->assertsObject($details)->hasAttribute("user");
        $st->assertsString($details->user)->equals($hostUser);
        $st->assertsObject($details)->hasAttribute("group");
        $st->assertsString($details->group)->equals($hostGroup);
    }
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
    foreach (hostWithRole($st, 'upload_target') as $hostname) {
        // get the default user details for this test environment
        $hostUser = $st->fromHost($hostname)->getAppSetting("user", "username");
        $hostGroup = $st->fromHost($hostname)->getAppSetting("user", "group");

        // get the details for this file
        $details = $st->expectsHost($hostname)->hasFileWithPermissions('testfile.txt', $hostUser, $hostGroup, 0644);
    }
});