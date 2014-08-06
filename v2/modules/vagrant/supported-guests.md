---
layout: modules-vagrant
title: Supported Guest Operating Systems
prev: '<a href="../../modules/vagrant/index.html">Prev: The Vagrant Module</a>'
next: '<a href="../../modules/vagrant/usingVagrant.html">Next: usingVagrant()</a>'
---

# Supported Guest Operating Systems

## Fully Supported

Storyplayer works with the following operating systems inside Vagrant virtual machines:

* CentOS 5.x / RedHat Enterprise Linux 5.x
* CentOS 6.x / RedHat Enterprise Linux 6.x
* Ubuntu 13.04 (coming soon)

Support for more guest operating systems will be added in future.

## How To Add Support For A New Guest

Support for guest operating systems can be found in the _DataSift\Storyplayer\OsLib_ namespace:

* There's a class for each supported operating system, e.g. _DataSift\Storyplayer\OsLib\Centos5_.
* Each class implements the _DataSift\Storyplayer\OsLib\SupportedOs_ interface.
* For UNIX-like operating systems (Linux distros, OS X), Storyplayer uses SSH to remotely log into the virtual machine to run commands.
* The class _DataSift\Storyplayer\OsLib\OsBase_ provides helper methods for running SSH commands inside the virtual machine.

If you want to add support for another guest operating system, follow these steps:

1. Create a new class called _DataSift\Storyplayer\OsLib\&lt;os-name&gt;_, where _&lt;os-name&gt;_ is the `$osName` parameter passed into _[usingVagrant()->createVm()](usingVagrant.html#startvm)_.  _&lt;os-name&gt;_ must start with a capital leter, and all other letters must be in lower case.
1. Your new class must implement the _DataSift\Storyplayer\OsLib\SupportedOs_ interface.
1. Your new class should extend the _DataSift\Storyplayer\OsLib\OsBase_ class, to re-use the SSH support that we already have.
1. The method _determineIpAddress()_ is used to find out the IP address assigned to the running guest.  You'll need to create a shell command to query the guest's network adapters.  You'll run the command using _SupportedHost::runCommandViaHostManager()_.
1. The method _getInstalledPackagedDetails()_ is used to learn whether a package is installed on the system or not.  Packages are normally installed by yum, apt-get, or equivalent.  You'll need to create a shell command to query the package manager for a given package.  You'll run the command via the built-in SSH support.
1. The method _getProcessIsRunning()_ is used to see whether or not a named process is currently running inside the guest operating system.  This is normally done by checking the output of `ps`.
1. The method _getPid()_ is used to get the process ID of a named process (for example, so that the test can monitor that process using [SavageD](../savaged/index.html)).  This is normally done by checking the output of `ps`.

You should then be able to start a Vagrant VM running your guest operating system, and interact with it using the [Hosts](../hosts/index.html) module.

Once you've got it working, if you'd like to send it over as a pull request, we'd be delighted to add it to Storyplayer's next release.