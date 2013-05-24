---
layout: modules-vagrant
title: The Vagrant Module
prev: '<a href="../../modules/uuid/expectsUuid.html">Prev: expectsUuid()</a>'
next: '<a href="../../modules/vagrant/supported-guests.html">Next: Supported Guest Operating Systems</a>'
---

# The Vagrant Module

The __Vagrant__ module allows you to create and destroy virtual machines using the popular [Vagrant](http://www.vagrantup.com/) tool.  Once a virtual machine has been created, it's then available to be used by the [Host](../host/index.html) module.

The source code for this Prose module can be found in this PHP class:

* DataSift\Storyplayer\Prose\VagrantActions

## Does Your Code Actually Work?

Unit tests are a vitally important part of your approach to quality, and they go a long way to making sure that each of your classes and functions function as intended.  You should be writing lots of high-quality unit tests, and every time you find a bug, you should also write a test for that bug too.

The thing is, _you can't rely just on unit tests_.  It's perfectly possible to have so many unit tests that every single line of code you ship is covered by at least one unit test ... and for that code to still have bugs.  Why?  Unit testing is focused on testing your smallest building blocks (especially if you use a lot of mocks in your tests); it doesn't test whether all of these blocks work when they're wired up together.  This risk is amplified the more mocks you have in your tests.

That's where Storyplayer and this Vagrant module come in.

Using the Vagrant module, you can create a brand new virtual machine for every single test that you run, deploy your app into that virtual machine, and use it to catch the following types of problems:

* _Can't deploy because of broken deployment scripts_ - these might not show up on a server where you're upgrading software that's already installed, but they always show up when you're forced to install software on a machine for the first time
* _Can't deploy because of obsolete dependencies_ - such as a dependency that's available in one version of Ubuntu, but isn't available any more in the latest version (a problem which happened with Chrome 26 on fresh installs of Ubuntu 13.04)
* _Can't start because of missing dependencies_ - such as you've forgotten something, but you haven't spotted it because it either comes by default on your dev desktop or you've installed it by hand on your desktop and forgotten to also add it to your deployment steps
* _Can't start because of broken permissions_ - such as a missing user on your server, or failing to make sure that the code's files have the right permissions and ownership
* _Can't start because of missing / broken init.d scripts_ - you might have written some by hand for your production server, but forgotten to add them to your next deployment
* _Can't start and/or stop because of bugs in your code_ - if you've written a daemon, they're unusable if they don't start up; and they have to be kill -9'd if they won't shutdown
* _Can't accept requests over HTTP / ZeroMQ et al_ - bugs in your socket initialisation won't show up if you use mocks in your unit tests
* _Can't correctly save / retrieve data from a real database_ - especially if dealing with transactions,  rollbacks, and foreign keys - three areas where mocked database calls can let bugs slip through

... plus all of the tests needed to make sure your app does the things your users need it to!


## What Is The Cost Of Your Working Code?

You can also start to catch a whole heap of _[non-functional requirements](https://en.wikipedia.org/wiki/Non-functional_requirement)_ too, such as:

* _Can't process requests as quickly as required_ - check for latency
* _Runs substantially slower than what's currently running in production_ - check for performance regression (don't forget to check for functionality regressions too)
* _Can't support the required number of simultaneous requests_ - check for scalability and resource contention issues
* _Slows down or pauses when database backups are running_ - check for how your sysadmin tasks interact with the app
* _Crashes when placed under load_ - check for race conditions
* _Doesn't fail gracefully when database servers are down_ - check for resilience
* _Doesn't fail gracefully when internal services it depends upon are down_ - check for resilience
* _plus so much more ..._

These kind of tests help you prove not only that your code works, but that your code performs well too.  DataSift's [SavageD](https://github.com/datasift/SavageD) realtime server/process monitoring daemon can help to keep an eye on these important metrics.

You're limited only by your imagination, and the performance limitations that come from testing inside a virtual machine.

## Why Vagrant?

We picked Vagrant because it's the best tool that you can install on your desktop or server, and it's easily combined with a provisioning plugin which together will create your virtual machine and install software into it all in one easy step.

Longer term, we plan on supporting other virtual machine systems too, especially Amazon EC2 and OpenStack (both of which we use here at DataSift).  We also plan on supporting provisioning onto arbitrary remote machines (there'll always be some tests that need to run on real hardware).  When that happens, we'll implement the functionality into the _Server_ module, and continue to maintain this module for backwards compatibility.

## Dependencies

You need to install:

* [Vagrant](http://www.vagrantup.com) - CLI tool for controlling virtual machines
* [VirtualBox](https://www.virtualbox.org/) - virtual machine software, runs on Windows, OS X and Linux

You'll also want to install a provisioning plugin for Vagrant, and the provisioning engine.  Your main choices are [Ansible](http://ansible.cc/), [Chef](http://www.opscode.com/chef/), or [Puppet](https://puppetlabs.com/).

At the moment, we support Ansible the best (because it's what we use internally for our test environments), but we're keen to support Chef and Puppet equally - pull requests are most welcome if code changes are needed.

## Before You Use The Vagrant Module

Before you write any tests for your stories, test your Vagrant virtual machine from the command line.  Start it up, and make sure that it boots and provisions exactly how you want it.  This step can save you a lot of time when you design a new virtual machine for a series of tests.

## Using The Vagrant Module

The basic format of an action is:

{% highlight php %}
$st->MODULE()->ACTION();
{% endhighlight %}

where __module__ is:

* _[usingVagrant()](usingVagrant.html)_ - start, stop and perform actions on virtual machines

Once you've used the Vagrant module to start your virtual machine, you'll then use the _[Hosts module](../hosts/index.html)_ to work with the VM until your test destroys the VM.

## An Example

Here's one of our internal tests, which proves that we can deploy and start our in-house queueing solution called Ogre:

{% highlight php %}
use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Ogre Service Stories')
         ->inGroup('On Startup')
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
        $st->usingVagrant()->destroyVm('ogre');
    });
});

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// there is no story-specific setup / tear-down

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

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function(StoryTeller $st) {
    // create the parameters to inject into our test VM
    $vmParams = array();

    // we need a real Ogre
    $st->usingVagrant()->createVm('ogre', 'centos6', 'qa/ogre-centos-6.3', $vmParams);

    // at this point, Ogre should be ready to work with
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->setPostTestInspection(function(StoryTeller $st) {
    // make sure the VM is running
    $st->expectsHost('ogre')->hostIsRunning();

    // make sure that ogre is installed, and running
    $st->expectsHost('ogre')->packageIsInstalled('ms-service-ogre');
    $st->expectsHost('ogre')->processIsRunning('ogre');
});
{% endhighlight %}