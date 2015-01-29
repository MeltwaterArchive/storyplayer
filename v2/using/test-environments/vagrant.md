---
layout: v2/using-test-environments
title: Creating Test Environments Using Vagrant
prev: '<a href="../../using/test-environments/physical-hosts.html">Prev: Creating Test Environments On Physical Hosts</a>'
next: '<a href="../../using/test-environments/safeguarding.html">Next: Safeguarding Environments</a>'
---

# Creating Test Environments Using Vagrant

[Vagrant](http://vagrantup.com) is a very popular solution for spinning up virtual machines on demand on your desktop or laptop.  Combined with a provisioning solution such as [Ansible](http://www.ansibleworks.com), it gives you on-demand dev and test environments without having to waste valuable disk space keeping old virtual machine images lying around.  Perfect for modern SSD-based machines :)

Storyplayer can create, provision and destroy test environments for you using Vagrant.

## Planning Your Vagrant Test Environment

When you're planning your Vagrant test environment, you'll need to answer the following questions:

* How much RAM and CPU does my test VM need?

  Your computer will be running its normal operating system and apps, plus Storyplayer, plus virtualisation software such as Virtualbox, plus the app that you're testing inside your test VM.  You're running a computer inside another computer.

  If your desktop or laptop doesn't have enough RAM or CPU to cope with this, your tests won't work reliably, and your computer could end up crashing.

* How much disk space does my test VM need?

  Your virtual machine is going to create a virtual hard drive, and your computer needs enough free disk space to be able to accomodate that.

  If you can afford it, you should put your virtual machines onto an SSD of some kind.  This will really help with speeding up your tests, especially when it comes to creating your virtual machines.

* Which Vagrant box will my test VM boot from?

  Vagrant creates new virtual machines by basing them on _boxes_.  These are pre-built virtual machine images, and they can range from a bare-bones operating system install right through to a ready-to-use software stack.

  We use [VeeWee](https://github.com/jedi4ever/veewee) to build our own virtual machine images.  That way, we know exactly what is inside the image, and we can make whatever changes we need the moment we realise we need to.

* How will my software get deployed?

  Storyplayer will be creating new virtual machines when your tests run - and then destroying them afterwards.  Without automated provisioning, there's going to be no software inside your virtual machines for Storyplayer to test.  The [Provisioning module](../modules/provisioning/index.html) can help.

## Choosing A Hypervisor

Vagrant gives you [a choice of virtual machine software to use](http://docs.vagrantup.com/v2/providers/index.html).  At the time of writing, Vagrant only ships with support for Oracle's [Virtualbox](https://www.virtualbox.org/), which is a free, cross-platform solution.  If you would prefer to use something other than Virtualbox, you'll need to download and install the necessary Vagrant plugin first.

If you've never used virtualisation before, we recommend starting with Virtualbox (because it's free), and evaluating it first, before spending money on any of the alternatives.  Virtualbox does have an important advantage: when it's time to get additional people running your Storyplayer tests, they can be up and running very quickly indeed, because you don't have to find the money to pay for software licenses for the alternatives.

## Building Your Vagrant Test Environment

You'll find the following modules helpful when building your Vagrant test environment in your [TestEnvironmentSetup / Teardown functions](../stories/test-environment-setup-teardown.html):

* The [Vagrant module](../modules/vagrant/index.html) for creating and destroying your virtual machine
* The [Hosts module](../modules/host/index.html) for working with your Vagrant-based VMs once they have booted
* The [Provisioning module](../modules/provisioning/index.html) for deploying any test-specific packages to your VMs

## Destroying Your Vagrant Test Environment

When your story or tale has finished, remember to use _[$st->usingVagrant()->destroyVm()](../modules/vagrant/usingVagrant.html#destroyvm)_ to destroy your Vagrant virtual machines.  Otherwise, they'll keep running on your computer, taking up reclaimable disk space and potentially taking up vital RAM and CPU too.