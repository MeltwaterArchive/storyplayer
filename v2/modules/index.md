---
layout: v2/modules
title: Storyplayer Modules
prev: '<a href="../tips/vagrant/speed-up-virtualbox.html">Prev: How To Speed Up Vagrant VMs</a>'
next: '<a href="../modules/aws/index.html">Next: The Amazon AWS Module</a>'
---

# Storyplayer Modules

## Modules For Stories

Storyplayer ships with over 20 modules that are ready to use straight away.

Module | Use To ...
-------|------------
[Amazon AWS](aws/index.html) |
[Amazon EC2](ec2/index.html) |
[Assertions](asserts/index.html) | test data that you've obtained from other modules.
[Browser](browser/index.html) | control a web browser.
[Checkpoint](checkpoint/index.html) |
[Config](config/index.html) |
[cURL](curl/index.html) |
[DeviceManager](devicemanager/index.html) | start and stop the test device.
[Environment](environment/index.html) |
[Failure](failure/index.html) |
[File](file/index.html) |
[Form](form/index.html) |
[Graphite](graphite/index.html) |
[Host](host/index.html) |
[HTTP](http/index.html) |
[Log](log/index.html) |
[Provisioning](provisioning/index.html) |
[SavageD](savaged/index.html) |
[Supervisor](supervisor/index.html) |
[System Under Test](systemundertest/index.html) |
[Test Environment](testenvironment/index.html) |
[Timer](timer/index.html) |
[UNIX Shell](shell/index.html) |
[UUID](uuid/index.html) |
[Vagrant](vagrant/index.html) |
[ZeroMQ](zeromq/index.html) |

## Iterators

New for Storyplayer v2, we've added support for modules that provide iterators for your story:

* [hostWithRole](iterators/hostWithRole.html)
* [expectsFirstHostWithRole](iterators/expectsFirstHostWithRole.html)
* [fromFirstHostWithRole](iterators/fromFirstHostWithRole.html)
* [usingFirstHostWithRole](iterators/usingFirstHostWithRole.html)

## Internal Modules

These modules are intended for internal use by Storyplayer, and you shouldn't call them from your stories. You might find them useful if you're writing new modules for Storyplayer, or adding new features.

* [Hosts Table](hoststable/index.html)
* [Processes Table](processestable/index.html)
* [Roles Table](rolestable/index.html)
* [Runtime Table](runtimetable/index.html)
* [Targets Table](targetstable/index.html)