---
layout: stories
title: "Example: Testing A Service"
prev: '<a href="../stories/web-example.html">Prev: Example: Testing A Website</a>'
next: '<a href="../stories/story-writing-tips.html">Next: Story-Writing Tips</a>'
---

# Example: Testing A Service

The story below is one of DataSift's internal tests for _Pickle_, our CSDL filtering language server.  It uses modules (such as _Doppeld_) that aren't part of the open source version of Storyplayer, so you can't run it yourself, but it's a good example of what can be done with Storyplayer.

{% highlight php %}
<?php

use DataSift\Storyteller\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

// create a new object for our story
$story = newStoryFor('Pickle Service Stories')
         ->inGroup('Rule Processing')
         ->called('Can load CSDL rules sequentially');

// ========================================================================
//
// TEST ENVIRONMENT SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// we need to create the environment for running this test
$story->setTestEnvironmentSetup(function(StoryTeller $st) {
    // what is our public IP address?
    $ourIpAddress = $st->getEnvironment()->host->ipAddress;

    // get the port numbers we need
    $pnSettings = $st->fromEnvironment()->getAppSettings('pickle-node');
    $cmSettings = $st->fromEnvironment()->getAppSettings('connectionManager');
    $dmSettings = $st->fromEnvironment()->getAppSettings('definitionManager');

    // create the parameters to inject into our ACL test box
    $vmParams = array (
        // mocktroll parameters
        "mocktroll_rate" => "500",
        "mocktroll_http_host" => "0.0.0.0",
        "mocktroll_http_port" => "3010",
        "mocktroll_zmq_endpoint" => "tcp://*:5093",

        // picklenode parameters
        "pickle_node_prism_endpoint" => "tcp://{$ourIpAddress}:{$pnSettings->zmqInputPort}",
        "pickle_node_acl_endpoint" => "tcp://{$ourIpAddress}:{$pnSettings->zmqOutputPort}",
        "pickle_node_connection_manager_command_endpoint" => "tcp://{$ourIpAddress}:{$cmSettings->zmqCommandPort}",
        "pickle_node_connection_manager_acknowledgement_endpoint" => "tcp://{$ourIpAddress}:{$cmSettings->zmqAckPort}",
        "pickle_node_connection_manager_request_endpoint" => "tcp://{$ourIpAddress}:{$cmSettings->zmqRequestPort}",
        "pickle_node_connection_manager_http_host" => "{$ourIpAddress}",
        "pickle_node_connection_manager_http_port" => $cmSettings->httpPort,
        // "pickle_node_definition_manager_http_host" => "{$ourIpAddress}",
        // "pickle_node_definition_manager_http_port" => $dmSettings->httpPort
        "pickle_node_definition_manager_http_host" => "pndebug.reh.favsys.net",
        "pickle_node_definition_manager_http_port" => "88",
    );

    // create a VM called 'pickle-dev', using the Vagrant file found
    // in the folder 'qa/pickle-centos-6.3' folder
    // with the injected parameters in $vmParams
    $st->usingVagrant()->createBox('pickle-dev', 'qa/pickle-centos-6.3', $vmParams);

    // make sure the mocktroll is installed and running
    $st->expectsVagrant()->packageIsInstalled('pickle-dev', 'ms-tool-mocktroll');
    $st->expectsVagrant()->processIsRunning('pickle-dev', 'mocktroll');

    // make sure the pickle node is installed and running
    $st->expectsVagrant()->packageIsInstalled('pickle-dev', 'ms-service-picklenode');
    $st->expectsVagrant()->processIsRunning('pickle-dev', 'pickle-node');

    // make sure the SavageD is running
    $st->expectsVagrant()->processIsRunning('pickle-dev', 'SavageD');
});

// clean up after ourselves
$story->setTestEnvironmentTeardown(function(StoryTeller $st) {
    // stop the pickle node
    tryTo(function() use($st) {
        $st->usingVagrant()->stopBox('pickle-dev');
    });
});

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->setTestSetup(function(StoryTeller $st) {
    // what is the process ID of pickle?
    $pid = $st->fromVagrant()->getPid('pickle-dev', 'pickle-node');

    // what is the IP address of the VM?
    $vmAddress = $st->fromVagrant()->getIpAddress('pickle-dev');

    // tell SavageD to start monitoring pickle-node
    $st->usingSavageD($vmAddress)->setStatsPrefix("qa");
    $st->usingSavageD($vmAddress)->watchProcess("pickle-node", $pid);
    $st->usingSavageD($vmAddress)->watchProcessMemory("pickle-node");

    // tell SavageD to also monitor the server
    $st->usingSavageD($vmAddress)->watchServerLoadavg("pickle-node");

    // start the monitoring, in case it isn't already started
    $st->usingSavageD($vmAddress)->startMonitoring();

    // create the fake service(s)
    $st->usingDoppeld()->start('doppeld', 'stories/pickle-node/rules/SequentialRuleLoading');

    // wait for mocktroll and pickle-node to start up (this will take a while)
    $st->usingTimer()->wait('PT1M', "Wait for connection manager sync message");

    // at this point, the pickle node should be ready to query
});

$story->setTestTeardown(function(StoryTeller $st) {
    // stop the fake service(s)
    tryTo(function() use($st) {
        $st->usingDoppeld()->stop('doppeld');
    });

    // is the VM running?
    if ($st->fromVagrant()->getBoxIsRunning("pickle-node")) {
        // what is the IP address of the VM?
        $vmAddress = $st->fromVagrant()->getIpAddress("pickle-node");

        // tell SavageD to stop monitoring
        $st->usingSavageD($vmAddress)->stopWatchingProcessMemory("pickle-node");
        $st->usingSavageD($vmAddress)->stopWatchingServerLoadavg("pickle-node");
        $st->usingSavageD($vmAddress)->stopMonitoring();
    }
});

// ========================================================================
//
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->setPreTestInspection(function(StoryTeller $st) {
    // get the checkpoint - we're going to store data in here
    $checkpoint = $st->getCheckpoint();

    // get the current stats for pickle-node, as our baseline
    $ipAddress  = $st->fromVagrant()->getIpAddress('pickle-dev');
    $pnSettings = $st->fromEnvironment()->getAppSettings('pickle-node');
    $stats      = $st->fromDaemonStatsPage()->getCurrentStats("http://{$ipAddress}:{$pnSettings->httpPort}/stats");

    // make sure we like the stats
    $st->assertsObject($stats)->hasAttribute("counters");

    // remember the stats for the post-test inspection phase
    $checkpoint->stats = $stats;
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function(StoryTeller $st) {
    // the entire test is executed by the doppelganger

    // $st->usingDoppeld()->startPlayback();

    $st->usingTimer()->wait('PT30M', "waiting whilst data is played back");
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->setPostTestInspection(function(StoryTeller $st) {
    $checkpoint = $st->getCheckpoint();

    // check that we can read the stats
    $ipAddress  = $st->fromVagrant()->getIpAddress('pickle-dev');
    $pnSettings = $st->fromEnvironment()->getAppSettings('pickle-node');
    $newStats   = $st->fromDaemonStatsPage()->getCurrentStats("http://{$ipAddress}:{$pnSettings->httpPort}/stats");
    $st->assertsObject($newStats)->hasAttribute("counters");

    // check that we processed some interactions
    $st->assertsCppDaemonStats($newStats)->counter('id_pipeline_interactions_received')->hasIncreased()->since($oldStats);

    // check that the memory usage is within some acceptable range
    //
    // we will do this using the data available within graphite
});
{% endhighlight %}