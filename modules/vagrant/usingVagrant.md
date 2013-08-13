---
layout: modules-vagrant
title: usingVagrant()
prev: '<a href="../../modules/vagrant/supported-guests.html">Prev: Supported Guest Operating Systems</a>'
next: '<a href="../../modules/vagrant/fromVagrant.html">Next: fromVagrant()</a>'
---

# usingVagrant()

_usingVagrant()_ allows you to start and stop virtual machines using the popular [Vagrant](http://www.vagrantup.com) command-line tool.  Once the virtual machine has started, you can then use _[$st->usingHost()](../host/usingHost.html)_ to perform actions inside the virtual machine.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\VagrantActions_.

## Behaviour And Return Codes

Every action makes changes to the virtual machine.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, an exception is throw. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action will be successful.

## createVm()

Use _$st->usingVagrant()->createVm()_ to start a new virtual machine using Vagrant.

{% highlight php %}
$st->usingVagrant()->createVm($vmName, $osName, $homeFolder);
{% endhighlight %}

where:

* _$vmName_ is the alias you want Storyplayer to remember this VM as.  It's used as a parameter to practically all of the Vagrant and [Host](../host/index.html) module actions.
* _$osName_ is the name of the guest operating system that runs inside this VM.  See _[supported guest operating systems](supported-guests.html)_ for the latest list of valid values.
* _$homeFolder_ is the path to the folder where the _Vagrantfile_ for this VM lives.

This action runs a `vagrant up` using the Vagrantfile in _$homeFolder_.  If your Vagrantfile includes a provisioning plugin, that will get executed too.

If the virtual machine was already running, it will be destroyed and re-created when you call _createVm()_.

If the virtual machine starts successfully, we create an entry in Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html), which then allows the [Host](../host/index.html) and [Provisioning](../provisioning/index.html) modules to work with your virtual machine.

## destroyVm()

Use _$st->usingVagrant()->destroyVm()_ to shutdown and delete a virtual machine that was previously started using _[createVm()](#createvm)_.

{% highlight php %}
$st->usingVagrant->destroyVm($vmName);
{% endhighlight %}

where:

* _$vmName_ is the name of the virtual machine that you created earlier

This action runs a `vagrant destroy --force` against the virtual machine.  Vagrant shuts down the virtual machine, and then erases the VM image from disk.

Once this is done, we remove the virtual machine's entry from Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html).  You can no longer use the [Host](../host/index.html) module with this virtual machine, unless you call _[createVm()](#createvm)_ once more first.

## startVm()

Use _$st->usingVagrant()->startVm()_ to start a virtual machine that was previous stopped using _[stopVm()](#stopvm)_ or _[powerOffVm()](#poweroffvm)_.

{% highlight php %}
$st->usingVagrant()->startVm($vmName);
{% endhighlight %}

where:

* _$vmName_ is the name of the virtual machine that you created earlier

This action runs a `vagrant up` to start the virtual machine.

If there's an existing virtual machine image on disk, Vagrant will re-use that image, but will still run any provisioning plugin that you have listed in your Vagrantfile.

Please remember to use _[destroyVm()](#destroyvm)_ at the end of your test to delete the virtual machine from disk.


## stopVm()

Use _$st->usingVagrant()->stopVm()_ to stop a virtual machine that was previously started using _[createVm()](#createvm)_.

{% highlight php %}
$st->usingVagrant()->stopVm($vmName);
{% endhighlight %}

where:

* _$vmName_ is the name of the virtual machine that you created earlier

This action runs a `vagrant halt` to shutdown the virtual machine.  The virtual machine image isn't deleted from disk, and the virtual machine still exists in Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html), even after Storyplayer finishes running.

Please remember to use _[destroyVm()](#destroyvm)_ at the end of your test to delete the virtual machine from disk.

## restartVm()

Use _$st->usingVagrant()->restartVm()_ to reboot a virtual machine that was previously started using _[createVm()](#createvm)_.

{% highlight php %}
$st->usingVagrant()->restartVm($vmName);
{% endhighlight %}

where:

* _$vmName_ is the name of the virtual machine that you created earlier

This action runs `vagrant halt && vagrant up` to gracefully shutdown the virtual machine, and then to boot the virtual machine back up.  If the virtual machine doesn't shutdown, or doesn't boot back up as expected, an exception is thrown.

Please note that Vagrant will re-run any provisioning plugin that you have listed in your Vagrantfile when the virtual machine boots back up.

## powerOffVm()

Use _$st->usingVagrant()->powerOffVm()_ to stop a virtual machine that was previously started using _[createVm()](#createvm)_.

{% highlight php %}
$st->usingVagrant()->powerOffVm($vmName);
{% endhighlight %}

where:

* _$vmName_ is the name of the virtual machine that you created earlier

This action runs a `vagrant halt --force` to shutdown the virtual machine immediately.  The virtual machine image isn't deleted from disk, and the virtual machine still exists in Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html), even after Storyplayer finishes running.

Please remember to use _[destroyVm()](#destroyvm)_ at the end of your test to delete the virtual machine from disk.

## runVagrantCommand()

Use _$st->usingVagrant()->runVagrantCommand()_ to call the `vagrant` command line tool directly from your stories.

{% highlight php %}
$result = $st->usingVagrant()->runVagrantCommand($vmName, $command);
{% endhighlight %}

where:

* _$vmName_ is the name of the virtual machine that you created earlier
* _$command_ is the command that you want to run
* _$result_ is a _DataSift\Storyplayer\CommandLib\CommandResult_ object containing the return code and any output

This action temporarily changes the current working directory to be the folder where the virtual machine's Vagrantfile is stored, and then executes _$command_.  You'll need to include the `vagrant` command at the front of _$command_; it isn't prepended for you.

If you want to run commands inside the virtual machine, you'd normally use _[$st->usingHost()->runCommand()](../host/usingHost.html#runcommand)_.