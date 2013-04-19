<?php

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer Service Stories')
         ->inGroup('Provisioning')
         ->called('Can deploy to CentOS 6.3');

// ========================================================================
//
// TEST ENVIRONMENT SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// we do not need an initial setup phase

$story->setTestEnvironmentTeardown(function(StoryTeller $st) {
    // stop the VM
    tryTo(function() use($st) {
        $st->usingVagrant()->stopBox('storyplayer');
    });
});

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// there is no story-specific setup / tear-down

// story doesn't need the web browser for every phase
$story->setDoesntUseTheWebBrowser();

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
    // create the parameters to inject into our test VM
    $vmParams = array();

    // we need a real Ogre
    $st->usingVagrant()->createBox('storyplayer', 'qa/storyplayer-centos-6.3', $vmParams);

    // at this point, Ogre should be ready to work with
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->setPostflightCheck(function(StoryTeller $st) {
});