---
layout: v2/modules
title: Storyplayer Modules
prev: '<a href="../tips/vagrant/speed-up-virtualbox.html">Prev: How To Speed Up Vagrant VMs</a>'
next: '<a href="../modules/aws/index.html">Next: The Amazon AWS Module</a>'
updated_for_v2: true
---

# Storyplayer Modules

## Modules For Stories

Storyplayer ships with over 20 modules that are ready to use straight away.

Module | Use To ...
-------|------------
[Amazon AWS](aws/index.html) |
[Amazon EC2](ec2/index.html) |
[Assertions](asserts/index.html) | test data that you've obtained from other modules
[Browser](browser/index.html) | control a web browser
[Checkpoint](checkpoint/index.html) | get the Checkpoint object
[cURL](curl/index.html) | make cURL requests
[DeviceManager](devicemanager/index.html) | start and stop the test device
[Failure](failure/index.html) | deal with actions that are expected to fail
[File](file/index.html) | work with files locally
[Form](form/index.html) | fill out forms in a web browser
[Graphite](graphite/index.html) | get data from a Graphite server
[Host](host/index.html) | work with hosts in your test environment
[HTTP](http/index.html) | make HTTP/HTTPS requests
[Log](log/index.html) | write to Storyplayer's logfile
[Provisioning](provisioning/index.html) | provision hosts in your test environment
[SavageD](savaged/index.html) | monitor processes and servers in your test environment
[Storyplayer](storyplayer/index.html) | retrieve settings from your `storyplayer.json` config file
[Supervisor](supervisor/index.html) | work with Supervisor on hosts in your test environment
[System Under Test](systemundertest/index.html) | get settings from your system under test config file
[Test Environment](testenvironment/index.html) | get settings from your test environment config file
[Timer](timer/index.html) | wait for things to happen
[UNIX Shell](shell/index.html) | work with your local Linux / OSX computer
[UUID](uuid/index.html) | create Universally-Unique IDs
[Vagrant](vagrant/index.html) | work with Vagrant
[ZeroMQ](zeromq/index.html) | use the ZeroMQ messaging library
[ZMQ](zmq/index.html) | the original ZeroMQ module from SPv1

## Iterators

New for Storyplayer v2, we've added support for modules that provide iterators for your story:

* [hostWithRole](iterators/hostWithRole.html)
* [expectsFirstHostWithRole](iterators/expectsFirstHostWithRole.html)
* [fromFirstHostWithRole](iterators/fromFirstHostWithRole.html)
* [usingFirstHostWithRole](iterators/usingFirstHostWithRole.html)

## Internal Modules

These modules are intended for internal use by Storyplayer, and you shouldn't call them from your stories. You might find them useful if you're writing new modules for Storyplayer, or adding new features.

* [Config](config/index.html)
* [Hosts Table](hoststable/index.html)
* [Processes Table](processestable/index.html)
* [Roles Table](rolestable/index.html)
* [Runtime Table](runtimetable/index.html)
* [Targets Table](targetstable/index.html)

## Deprecated Modules

These modules will be removed in Storyplayer v3. Do not use them in new stories.

Module | Use To ...
-------------------
[Environment](environment/index.html) | get settings from Storyplayer's running config
