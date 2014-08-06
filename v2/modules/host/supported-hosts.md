---
layout: modules-host
title: Supported Hosts
prev: '<a href="../../modules/host/index.html">Prev: The Host Module</a>'
next: '<a href="../../modules/host/fromHost.html">Next: fromHost()</a>'
---

# Supported Hosts

## What Hosts Do We Support?

At the moment, Storyplayer works with the following kinds of host:

* [Vagrant](http://www.vagrantup.com) virtual machines

Support for the following is planned soon:

* Amazon EC2 instances
* Real hardware

## How To Add Support For A New Host

Support for different types of host can be found in the _DataSift\Storyplayer\HostLib_ namespace:

* There's a class for each supported type of host, e.g. _DataSift\Storyplayer\HostLib\VagrantVm_.
* Each class implements the _DataSift\Storyplayer\HostLib\SupportedHost_ interface.

If you want to add support for another type of host, follow these steps:

1. Create a new class called _DataSift\Storyplayer\HostLib\&lt;host-type&gt;_, where _&lt;host-type&gt;_ is the name that you want to give to this type of host.
1. Your new class must implement the _DataSift\Storyplayer\HostLib\SupportedHost_ interface.
1. The method _createHost()_ will create the new host (e.g. start a new virtual machine), and add the host's details to Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html) if the new host starts up successfully.
1. The method _destroyHost()_ will shutdown the host, delete any associated virtual machine image or other storage, and then remove the host's details from Storyplayer's hosts table.
1. The method _startHost()_ will start up a host. The host must already have an entry in the hosts table.  After the host has started, _startHost()_ must query the host for its current IP address, in case it has changed.
1. The method _stopHost()_ will gracefully shutdown a host; i.e. allow the host to shutdown cleanly.  It must not remove the host from Storyplayer's hosts table.
1. The method _restartHost()_ will gracefully shutdown a host and then start it back up.  Most of the time, _restartHost()_ will be implemented to call _stopHost()_ followed by _startHost()_.
1. The method _powerOffHost()_ will simulate pulling the power chord out of the host.  It must not remove the host from Storyplayer's hosts table.
1. The method _runCommandAgainstHostManager()_ makes it possible for other Prose modules to use whatever CLI tool or API is used to control this host.  For example, in the _VagrantVm_ class, _runCommandAgainstHostManager()_ makes it possible for the [Vagrant](../vagrant/index.html) module to use the `vagrant` CLI tool.
1. The method _runCommandViaHostManager()_ makes it possible for other Prose modules to run commands on the host, with the command being executed via whatever CLI tool or API is used to control this host.  This is used to find out what the host's IP address is.  After that, Prose modules use SSH to run commands on the host.
1. The method _isRunning()_ works out whether or not the host is running.  It's perfectly valid for Storyplayer to have an entry in its hosts table for a host that is currently shutdown; for example, as part of a test to make sure that the right services automatically restart when a host is rebooted.
1. The method _determineIpAddress()_ works out the IP address of the host, so that Prose modules can then run commands on the host via SSH.

After you've created your new &lt;host-type&gt; class, you will probably also want to create a Prose module (similar to the [Vagrant](../vagrant/index.html) module) for starting and stopping the host.