---
layout: environments
title: Creating Test Environments On Physical Hosts
prev: '<a href="../environments/ec2.html">Prev: Creating Test Environments On Amazon EC2</a>'
next: '<a href="../environments/vagrant.html">Next: Creating Test Environments Using Vagrant</a>'
---

# Creating Test Environments On Physical Hosts

Virtualisation (such as [Amazon EC2](ec2.html) or [Vagrant and Virtualbox](vagrant.html)) is a great way to build useful test environments on demand.  But, if your production environment doesn't use virtualisation at all, then at some point you'll want to run your performance-related stories against software deployed on real hardware.

Storyplayer doesn't have a _PhysicalHost_ module, but you can use the [Hosts Table module](../modules/hoststable/index.html) to tell Storyplayer all about any physical host that you want to use in your tests.

## Planning Your Testing

Testing on real hardware brings its own unique challenges - mainly that there's no easy way to reprovision a physical box, and you need to beware of multiple people trying to use the same box at the same time.

One way around the reprovisioning problem is to setup a [PXE boot environment](http://en.wikipedia.org/wiki/Preboot_Execution_Environment).  PXE boot allows you to boot your physical box from an operating system hosted elsewhere on your network - perfect for re-installing before your next testing period.

Alternatives include buying a server with [integrated Lights Out (iLO) management](http://en.wikipedia.org/wiki/Integrated_Lights-Out) or a networked [KVM switch](http://en.wikipedia.org/wiki/KVM_switch) with support for remote media.  Both of these allow you to access the server's BIOS / EFI boot screens from the comfort of your own desk, and to do a re-install of your operating system from virtual media - a DVD image that's mounted over the network.  They also allow you to power the server on and off remotely, which is very handy if your performance test causes the operating system to crash or lock up.

It's common to combine PXE boot with either iLO or a KVM switch, to avoid having to travel to your datacenter just to operate your server.

If you have multiple people trying to use the same physical host at the same time, it can affect the results of your testing.  It's something that's best avoided.  A simple Google spreadsheet where people 'book out' a server for testing is all that's needed to keep things clear for everyone.

## Curate Your Test Hardware

Beware homogenous hardware.  It's incredibly tempting to just have a pile of servers that are all identical, especially if you use the same hardware in production, but that will limit what you can discover about the software you are testing.  Hardware that's different in exagerated ways will help you stress software in ways that you can't otherwise do, and ultimately it'll help you produce much better software.

Consider the particular strengths and weaknesses of each server you use for performance testing.  For example, I keep an old server around that was retired from production years ago, precisely because it has (by modern standards) a very slow disk subsystem.  It's perfect for running any tests where there'll be a lot of disk activity (databases, message queues and the like).  I've got another server that's as fast as I can get, because it will show up multi-threading race conditions that don't appear anywhere else.  It also shows up CPU cache line stalls better than anything else I have.

## Preparing Your Physical Hardware

1. Install an operating system
1. Assign a fixed IP address, or make sure your host is available via dynamic DNS
1. Install your preferred set of packages
1. Create a user account for Storyplayer to login as, and assign it a passphrase-less SSH key