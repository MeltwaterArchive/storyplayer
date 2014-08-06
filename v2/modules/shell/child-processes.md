---
layout: v2/modules-shell
title: The Role Of Child Processes In Testing
prev: '<a href="../../modules/shell/index.html">Prev: The UNIX Shell Module</a>'
next: '<a href="../../modules/shell/fromShell.html">Next: fromShell()</a>'
---

# The Role Of Child Processes In Testing

## Why Do We Need Child Processes At All?

There are several different reasons why Storyplayer supports the use of child processes in your tests.

### Setting Up Sophisticated Test Structures

A service story may involve the following moving parts:

* The service that is being tested
* One or more mocked services that the main service relies on
* High-volume data providers (pumping data into the main service)
* High-volume data retrievers (pulling data out of the main service)
* Additional monitoring of the service that is being tested

Some (if not all of these) can be automatically deployed into a virtual machine using [the Vagrant module](../vagrant/index.html), but the order that we start and stop all of these moving parts normally is important to the test - which means that it is often best for Storyplayer to control when they are started and stopped.

### Using Specialist Test Tools

Production software often seeks to maximise how much code it reuses, but unfortunately when it comes to test software, adapting code to be reused for many unrelated tests can make your tests very fragile, with tests breaking because of changes introduced to support other (unrelated) tests.

One great way around this is to use a generic layer (such as Storyplayer) to control the tests, whilst developing and using specialist test tools for small groups of tests.  You can [implement these tools as Storyplayer modules](../../stories/local-dialect.html) or you can implement them as stand-alone command-line programs which you start and stop from Storyplayer.  Your choice.

### Limitations Of PHP

PHP is a single-threaded programming language, with no support for creating multiple threads a la Java or C/C++.  That limits us to one linear execution pipeline, plus being creative with signals.  When you need to do things in parallel, having those run in child processes, leaving Storyplayer responsible for creating and monitoring those processes, makes the most sense.

## How Do We Use Child Processes?

In our own tests here at DataSift, we use child processes in the following way:

* During [the test setup phase](../../stories/test-setup-teardown.html), we start any monitoring tools that we want to use (such as [SavageD](../savaged/index.html)).  We also start any mock services that we want to use.
* During [the action phase](../../stories/action.html), we start any data-providing tools that we want to use.  We build those tools to terminate by themselves (either after generating a specified amount of data, or after a certain time period), and we use _[usingTimer()->waitWhile()](../timer/usingTimer.html#waitwhile)_ to wait until the test tool has finished.
* During [the test teardown phase](../../stories/test-setup-teardown.html), we stop all processes that the _UNIX Shell_ module knows about, using _[usingShell()->stopAllScreens()](usingShell.html#stopallscreens)_.

There's nothing stopping you using the _UNIX Shell_ module to start and stop child processes in any of the other phases, if you have a need to do so.

## An Example

The following test is one of our internal tests for our _Ogre_ ZeroMQ-based queueing system.  It uses a mixture of the open source Storyplayer modules, our own local dialect, and our own custom test tools:

{% highlight php %}
use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Ogre Service Stories')
         ->inGroup('Queueing')
         ->called('Can process production loads');

// ========================================================================
//
// TEST ENVIRONMENT SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestEnvironmentSetup(function(StoryTeller $st) {
    // create the parameters to inject into our test VM
    $vmParams = array();

    // start a real Ogre
    $st->usingVagrant()->createBox('ogre', 'qa/ogre-centos-6.3', $vmParams);

    // make sure that ogre is installed, and running
    $st->expectsVagrant()->packageIsInstalled('ogre', 'ms-service-ogre');
    $st->expectsVagrant()->processIsRunning('ogre', 'ogre');
});

$story->addTestEnvironmentTeardown(function(StoryTeller $st) {
    // stop the VM, and ignore any errors
    tryTo(function() use($st) {
        $st->usingVagrant()->stopBox('ogre');
    });
});

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function(StoryTeller $st) {
    // what is the VM's ipAddress?
    $ipAddress = $st->fromVagrant()->getIpAddress('ogre');

    // start monitoring the daemon's reported stats
    $httpPort = $st->fromEnvironment()->getAppSetting('ogre', 'httpPort');
    $st->usingDatasiftCppDaemon()->startMonitoringStats('ogre', "http://{$ipAddress}:{$httpPort}/stats");
});

$story->addTestTeardown(function(StoryTeller $st) {
    // stop any screen services that might be running
    tryTo(function() use($st) {
        $st->usingShell()->stopAllScreens();
    });

    // stop monitoring the stats
    tryTo(function() use ($st) {
        $st->usingDatasiftCppDaemon()->stopMonitoringStats('ogre');
    });
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

// we need to remember the value of the counters before our test
$story->addPreTestInspection(function(StoryTeller $st) {
    // get our checkpoint ... we're going to store values in here
    $checkpoint = $st->getCheckpoint();

    // get the stats from Ogre's HTTP page
    $ipAddress = $st->fromVagrant()->getIpAddress('ogre');
    $httpPort  = $st->fromEnvironment()->getAppSetting('ogre', 'httpPort');
    $stats     = $st->fromDaemonStatsPage()->getCurrentStats("http://{$ipAddress}:{$httpPort}/stats");
    $checkpoint->stats = $stats;

    // make sure we have some valid stats
    $st->assertsObject($checkpoint->stats)->hasAttribute('counters');
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function(StoryTeller $st) {
    // we're going to store some information in here
    $checkpoint = $st->getCheckpoint();

    // we need to remember when we started testing
    $checkpoint->startTime = time();

    // we need a temporary file to store the messages in
    $checkpoint->tmpFile = $st->fromFile()->getTmpFileName();

    // how many messages are we going to send?
    $checkpoint->sendCount = 5000000;

    // what is the VM's ipAddress?
    $ipAddress = $st->fromVagrant()->getIpAddress('ogre');

    // what is our stats server's host details?
    $statsdHost = $st->fromEnvironment()->getStatsdHost();

    // start retrieving messages from the queue
    $zmqReadPort = $st->fromEnvironment()->getAppSetting('ogre', 'zmqReadPort');
    $st->usingShell()->startInScreen("pull", "./bin/zmq-pull 'tcp://{$ipAddress}:{$zmqReadPort}' {$checkpoint->sendCount} '{$statsdHost}' 'qa.ogre' > {$checkpoint->tmpFile}");

    // then, start writing messages to Ogre
    $zmqWritePort = $st->fromEnvironment()->getAppSetting('ogre', 'zmqWritePort');
    $st->usingHornet()->startHornetDrone("push", array(
        "integration",
        "AclTestClient",
        "tcp://{$ipAddress}:{$zmqWritePort}",
        "15000u/sec",
        $checkpoint->sendCount,
        $statsdHost,
        "qa.ogre"
    ));

    // how long should it take to send all of the messages?
    // the formula is: (messages sent / target throughput) + fudge factor
    $timeout = ($checkpoint->sendCount / 15000) + 10;

    // wait for all the messages to pass through
    $st->usingTimer()->waitWhile(function() use($st) {
        $st->expectsShell()->isRunningInScreen("push");
    }, $timeout);

    // we're done writing messages
    $st->usingShell()->stopInScreen("push");

    // wait for all the messages to be retrieved
    $st->usingTimer()->waitWhile(function() use($st) {
        $st->expectsShell()->isRunningInScreen("pull");
    }, $timeout);

    // all done
    $st->usingShell()->stopInScreen("pull");
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function(StoryTeller $st) {
    // the information to guide our checks is in the checkpoint
    $checkpoint = $st->getCheckpoint();

    // get the old stats from the checkpoint
    $oldStats = $checkpoint->stats;

    // get the latest stats from the VM
    $ipAddress = $st->fromVagrant()->getIpAddress('ogre');
    $httpPort  = $st->fromEnvironment()->getAppSetting('ogre', 'httpPort');
    $newStats  = $st->fromDaemonStatsPage()->getCurrentStats("http://{$ipAddress}:{$httpPort}/stats");

    // make sure *something* has been received
    $st->assertsObject($newStats)->hasAttribute('counters');
    $st->assertsObject($newStats->counters)->hasAttribute('id_ogre_items_received');
    $st->assertsInteger($newStats->counters->id_ogre_items_received)->isInteger();

    // the number of items ogre thinks it delivered should have increased by $checkpoint->sendCount
    if (!isset($oldStats->counters->id_ogre_items_received)) {
        $st->assertsInteger($newStats->counters->id_ogre_items_received)->equals($checkpoint->sendCount);
    }
    else {
        $st->assertsInteger($newStats->counters->id_ogre_items_received)->equals($oldStats->counters->id_ogre_items_received + $checkpoint->sendCount);
    }

    // the number of items zmqPublisher sent should be $checkpoint->sendCount
    $st->expectsGraphite()->metricSumIs("stats.qa.ogre.zmqPublisher.sent", $checkpoint->sendCount, $checkpoint->startTime, time());

    // the number of items zmq-pull received should be $checkpoint->sendCount
    $st->expectsGraphite()->metricSumIs("stats.qa.ogre.zmq-pull.received", $checkpoint->sendCount, $checkpoint->startTime, time());

    // did we get all of the messages back from ogre?
    // if we did not, there will be a sequence error logged in graphite
    $st->expectsGraphite()->metricIsAlwaysZero("stats.qa.ogre.zmq-pull.sequence-errors", $checkpoint->startTime, time());
});
{% endhighlight %}
