---
layout: environments
title: Testing Against Local Virtual Machines
prev: '<a href="../environments/your-machine.html">Prev: Testing On Your Machine</a>'
next: '<a href="../environments/dedicated.html">Next: Testing Against Dedicated Environments</a>'
---

# Testing Against Local Virtual Machines

Your code may run fine on your local computer, but differences between your local computer and your servers might mean that your code won't work when you ship it to production.  One way to catch these differences is to deploy your code into a virtual machine running on your computer, and then run your tests against the virtual machine.

Testing a virtual machine takes a bit of up-front effort, but the pay-off is well worth it.

## Setting Up For Testing

You are going to run Storyplayer and your virtual machines on your local computer, and use it to test the software installed inside the virtual machine(s).

You will need:

* Virtual machine software, such as [VirtualBox](https://www.virtualbox.org/)
* [Vagrant](http://www.vagrantup.com) to manage your virtual machines
* An operating system image (you can create one using [VeeWee](https://github.com/jedi4ever/veewee))
* Provisioning software, such as [Ansible](http://www.ansibleworks.com/)
* ... plus Storyplayer installed locally

You also need to make sure that your computer has enough RAM and CPU to run virtual machines.  Just about any modern machine with 8GB of RAM should be fine, but be sure to check.

## Automate Creating Your Virtual Machines

Before you do anything with Storyplayer, make sure that you can create your virtual machine locally, and that you can automatically deploy your software inside your virtual machine.  Yes, you can build them by hand to save a lot of time, but that means that you're stuck with a one-off working copy of your virtual machine.  VMs built by hand are almost impossible to share with other developers, and eventually you'll do something that will break them - and then you're back to square one.

So automate straight away.

Once you have automated your virtual machine, Storyplayer can create and destroy the virtual machine every time you run your tests.  This gives you a pristine environment to test your code in every single time, which really helps you make your tests repeatable and reliable.

Follow these steps for automating your virtual machine(s):

1. Build a base operating system image using VeeWee
2. Make sure your base image boots fine using the `vagrant up` command
3. Write your provisioning scripts, and make sure that they run just fine via your provisioning tool's command-line util

We recommend that you create system-installer packages for your software (e.g. RPMs for CentOS / Fedora / RedHat, DPKGs for Debian / Ununtu) whilst you're at it.  That way, your own software (with the version numbers!) shows up in the system inventory, and your software can be installed using your server operating system's standard commands.  This gives you a solid foundation which you can continue to use no matter what the future brings.

## A Note About Vagrant Plugins

You can get Vagrant plugins for all the popular provisioning tools.  With these plugins, when you run `vagrant up` to create your virtual machine, your provisioning scripts are run at the same time.

Don't use these plugins in your tests.  Your story is going to need to inject parameters into the provisioning scripts, such as port numbers and the like.  You can't do so if your provisioning scripts run when `vagrant up` is executed.

## Configuring Your Environment

You can do this in one of two ways:

1. If you do all your dev testing in a local VM, simply edit the config for your local environment to have the right settings for your VM.  That way, you can run Storyplayer without the `-e` switch to save a bit of effort every time.
1. If you [run tests against your local computer](your-machine.html) _and_ you run tests against a local VM too, you'll need to define separate environments for each.  For example, you might call the environment *local_vm*. You'll also need to remember to use the `-e` switch every time you run Storyplayer against a local VM.

If you're not sure, go with the second option for now.

_What should go into your environment's config?_  Anything that is unique to that environment - normally the URLs you use to access the software you're testing.

## Writing Your Stories

You should create and destroy your virtual machine in the [TestEnvironmentSetup / Teardown](../stories/testenvironmentsetup-teardown.html) phases of your story.  You'll probably want to use a [story template](../stories/story-templates.html) to avoid repeating the same setup / teardown code in all of your stories.

You'll find the following modules useful:

* To create your virtual machine, use the [Vagrant module](../modules/vagrant/index.html) to boot the virtual machine, and the [Provisioning module](../modules/provisioning/index.html) to deploy software inside your VM.
* Use the [Host module](../modules/host/index.html) to do useful things such as [finding the IP address of your VM](../modules/host/fromHost.html#getipaddress).
* When you've finished with your test, use the Vagrant module again to destroy your virtual machine.

You'll need to store your provisioning rules somewhere.  There's a few options:

* _store them in a folder alongside your stories_: keeps everything in one place
* _store them in their own Git repo_: makes it much easier to reuse provisioning rules across multiple projects
* _run a provisioning server on your network_: this is probably how your sysadmins already do their own provisioning

## Running Your Stories

Running your stories against a local VM is very similiar to running against code running on your own computer; you just have to remember to tell Storyplayer about any additional environment you've defined:

<pre>
vendor/bin/storyplayer -e local_vm &lt;storyfile&gt;
</pre>

You don't need the `-e local_vm` switch if you went with option 1 in _[Configuring Your Environment](#configuring_your_environment)_ above.