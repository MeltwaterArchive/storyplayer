---
layout: v2/learn-test-your-code
title: Designing Your Test Environment
prev: '<a href="../../learn/test-your-code/sample-layout-for-source-code-repo.html">Prev: Sample Layout For Source Code Repo</a>'
next: '<a href="../../learn/test-your-code/defining-your-system-under-test.html">Next: Defining Your System Under Test</a>'
---
# Designing Your Test Environment

The test environment for your component is an important foundation. Any problems or errors with your test environment will have a substantial impact on the tests that you can write and on how well their results can be trusted.

## What Does A Deployed Component Look Like?

A component is a single app or service that you will deploy into a test environment that you can recreate on demand. Start off by learning more about the component that you will deploy:

* what software does the component install?
* what are the component's dependencies?
* what configuration files does the component read?
* what log files does the component write to?
* how is the component started and stopped?

and by learning more about how the component gets deployed:

* is it packaged for a particular package manager (e.g. the operating system's package manager, or a popular package for the programming language?)
* are there automation scripts already written? (e.g. in Ansible, Chef, Puppet et al)
* or will you be scripting the automation yourself?

and by learning more about where the component gets deployed:

* does the component live on a single server, or is it designed to run on multiple servers?
* which operating system(s) does the component run on?
* how much memory does the component need?
* how much disk space does the component need?

You're going to need the answers to all of these questions to help you design your test environment.

## Popular Test Environment Designs

The chances are that your component will fit into one of these test environments:

* everything can be deployed into a single virtual machine

  This is the most common test environment design. It's very straight forward, and it will be sufficient for nearly all of your functional testing needs. This is the environment we'll use in this guide.

  If the component relies on backend systems (such as a database), then this environment doesn't prove that the component talks to backend systems correctly over the network all the time. This is normally not an issue when the component uses a well-established backend (such as MySQL).

  It can be an issue when the backend is another component that has been created for this project. In that case, you've got a judgment call to make. Either use one of the designs below, or decide that you'll test this integration in some other way (e.g. by load testing in a dedicated environment).

* you need two virtual machines - one for the component itself, and one for backend dependencies

  This is the design to consider if the component relies on backend dependencies that are also components created for this project, especially if the backend dependencies aren't using off-the-shelf network protocols such as HTTP. The communication between component and backend can hide a multitude of sins that aren't apparent when you test everything in a single virtual machine.

  It's rare to come across test environments that use this design. Most of the time, you're better off using a single VM for your functional testing, and catching comms issues between front-end and backend components in a load test environment of some description. Load testing will show up the issues much more clearly that functional testing will.

* you need four virtual machines - three for the component itself, and one for any backend dependencies

  This is the design to consider if you want to prove that you can run multiple copies of the component in parallel together. It's useful in a _share-nothing_ architecture (where multiple copies of the component aren't aware of each other), and it's almost essential when testing components that actively synchronise data between each other in some way.

  In this environment, a story would normally write to just one instance of the server, and then in the `PostTestInspection` phase read back from all deployed instances to make sure that the data has become consistent.

  Why not use two VMs for the component? Well, the problem with two VMs is that it's unlikely to show up anything but the most basic of problems. If you test against at least three copies of the component, you'll have a much higher degree of confidence that the component works as intended.

For the rest of this guide, we're going to focus on the simplest of test environments: a single virtual machine. All of the example stories that you'll see in this guide will work against this test environment and any of the other environments mentioned above too.

## Using A Single Vagrant Virtual Machine

[Vagrant](http://www.vagrantup.com) is the tool of choice for creating and destroying test environments on your desktop or laptop computer. It's normally used to control [VirtualBox](http://virtualbox.org) virtual machines. Follow [our setup instructions](../getting-setup/index.html) to get both of these installed onto your computer.

You'll need a virtual machine image to use Vagrant. (These are known as [Vagrant boxes](https://docs.vagrantup.com/v2/boxes.html)).  If you don't already have a Vagrant box, [you can search online](https://atlas.hashicorp.com/boxes/search) to find something suitable, and Linux distros such as [Ubuntu](https://ubuntu.com) also [publish their own official Vagrant boxes](https://cloud-images.ubuntu.com/vagrant/).

Once you've picked your Vagrant box, you'll want to plug the details into your `Vagrantfile`:

{% highlight ruby linenos %}
# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::Config.run do |config|
  config.vm.box = "<name>"
  config.vm.box_url = "<vagrantbox-url>"

  # enable bridged networking
  config.vm.network :bridged, :bridge=>ENV["VIRTUALBOX_BRIDGE_ADAPTER"]
end

# provider-specfic configuration
Vagrant.configure("2") do |config|
  config.vm.provider :virtualbox do |vb|
    # enable 4 CPUs by default
    vb.customize ["modifyvm", :id, "--cpus", "2", "--memory", "2048"]

    # change the network card hardware for better performance
    vb.customize ["modifyvm", :id, "--nictype1", "virtio" ]
    vb.customize ["modifyvm", :id, "--nictype2", "virtio" ]

    # suggested fix for slow network performance
    # see https://github.com/mitchellh/vagrant/issues/1807
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
  end
end
{% endhighlight %}

This file goes in the root folder of your project. (See our page on [where to put files for component testing](sample-layout-for-source-code-repo.html) if you're not sure.)

## Testing Your Vagrant VM

Open a terminal in the root folder of your project (where your `Vagrantfile` is), and run the command

    vagrant up

to prove that your `Vagrantfile` works as intended.

Once the virtual machine has successfully booted, you should be able to log into the machine by running the command:

    vagrant ssh

<div class="callout info" markdown="1">
#### Network Adapters Prompt

When you run `vagrant up`, Vagrant will print a list of network adapters on the screen, and prompt you to select one. This is because your `Vagrantfile` is configured to use [bridged networking](https://docs.vagrantup.com/v2/networking/public_network.html). In bridged networking, your virtual machine gets an IP address of its own via DHCP. This is handy if you ever need to log into the test environment from another computer.

When Storyplayer runs `vagrant up` for you, it will tell Vagrant which network adapter to use, by setting the `VIRTUALBOX_BRIDGE_ADAPTER` environment variable.
</div>

## Deploy Your Component

The next thing to do is to write the instructions needed to deploy your component into your new virtual machine. Storyplayer supports a few mechanisms for doing this.

* You can create a set of Ansible playbooks and use that to deploy into the new virtual machine [[more]](../../using/test-environments/provisioning-with-ansible.html),
* or you can write a simple shell script that runs inside the virtual machine [[more]](../../using/test-environments/provisioning-with-dsbuild.html)

If you're already using Ansible, then it makes sense to stick with that option. The downside is that Ansible is quite slow, and that can become very frustrating for people who want to run the tests several times a day.

The second option - the simple shell script - is about as fast as deployment can get. And, as it is a shell script, it can do anything you want - including running `chef-solo` and `puppet` if you use either tool. This is probably where you should start.

1. Create a file called `dsbuildfile-default` in your `tests/stories` folder.  This is the same folder where your `Vagrantfile` is, and this folder is also available inside your Vagrant VM as `/vagrant`.
1. Add all of the instructions needed to deploy your component and its dependencies to the `dsbuildfile-default`.
1. Use `vagrant ssh` to log into the VM, and then run `sudo bash /vagrant/dsbuildfile-default` to run the script.

Keep repeating steps 2 and 3 until you think you've got everything. Then, test your deployment by destroying (use `vagrant destroy --force`) and recreating your virtual machine (`vagrant up && vagrant ssh -c "sudo bash /vagrant/dsbuildfile-default"`).

## Writing Your Test Environment Config File

Once your test environment boots and deploys, you're ready to tell Storyplayer about it.

Create a file called `tests/stories/.storyplayer/test-environments/dsbuild-centos6` with the following contents:

{% highlight json linenos %}
[
    {
        "type": "LocalVagrantVms",
        "details": {
            "machines": {
                "default": {
                    "osName": "centos6",
                    "roles": []
                }
            }
        },
        "provisioning": {
            "engine": "dsbuild"
        }
    }
]
{% endhighlight %}

where:

* `LocalVagrantVms` is the adapter Storyplayer will use to talk to your test environment. [A full list of test environment adapters can be found here.](../../using/test-environments/adapters.html)
* `default` is the name that Vagrant assigns to a single VM. This shouldn't be confused with the VMs network hostname, which will probably be different.
* `centos6` is the name of the guest operating system (the operating system inside the virtual machine). Change `centos6` if you're using a different operating system. [A full list of supported guest operating systems can be found here.](../../using/test-environments/supported-guest-operating-systems.html)

## Putting It All Together

Use the `storyplayer build-test-environment` command to check your test environment:

    vendor/bin/storyplayer build-test-environment

This command creates your test environment and then destroys it again. If there are any problems with your test environment configuration or build script, they will appear in the `storyplayer.log` file.

Once you are happy that your test environment works, you can move on to [writing the config for your system under test](defining-your-system-under-test.html).