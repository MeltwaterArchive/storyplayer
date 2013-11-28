---
layout: modules-ec2
title: usingEc2()
prev: '<a href="../../modules/ec2/expectsEc2Image.html">Prev: expectsEc2Image()</a>'
next: '<a href="../../modules/ec2/usingEc2Instance.html">Next: usingEc2Instance()</a>'
---

# usingEc2()

_usingEc2()_ allows you to start and stop virtual machines (known as _images_) on [Amazon's Elastic Compute Cloud](http://aws.amazon.com/ec2/).  Once the virtual machine has started, you can then use _[$st->usingHost()](../host/usingHost.html)_ to perform actions inside the virtual machine.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingEc2_.

## Behaviour And Return Codes

Every action makes changes to the virtual machine.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, an exception is throw. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action will be successful.

## createVm()

Use _$st->usingEc2()->createVm()_ to start a new virtual machine running on EC2.

{% highlight php %}
$st->usingEc2()->createVm($vmName, $osName, $amiId, $instanceType, $securityGroup);
{% endhighlight %}

where:

* _$vmName_ is the alias you want Storyplayer to remember this VM as.  It's used as a parameter to practically all of the EC2 and [Host](../host/index.html) module actions.
* _$osName_ is the name of the guest operating system that runs inside this VM.  See _[supported guest operating systems](supported-guests.html)_ for the latest list of valid values.
* _$amiId_ is the ID of an Amazon Machine Image (AMI) to use as the template for this virtual machine.
* _$instanceType_ tells EC2 what size of virtual machine to create. See [Amazon's list of Instance Types](http://aws.amazon.com/ec2/instance-types/#instance-details) for a complete list of valid values.
* _$securityGroup_ is the name of one of the security groups that you've previously defined on EC2.  Use 'default' if you haven't defined any of your own.

This action makes a _runInstances_ API call to EC2.

If the virtual machine was already running, it will be destroyed and re-created when you call _createVm()_.

If the virtual machine starts successfully, we create an entry in Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html), which then allows the [Host](../host/index.html) and [Provisioning](../provisioning/index.html) modules to work with your virtual machine.

## destroyVm()

Use _$st->usingEc2()->destroyVm()_ to shutdown and delete a virtual machine that was previously started using _[createVm()](#createvm)_.

{% highlight php %}
$st->usingEc2->destroyVm($vmName);
{% endhighlight %}

where:

* _$vmName_ is the name of the virtual machine that you created earlier

This action makes a _terminateInstances_ API call to EC2. The instance is shut down and deregistered from EC2.  Any block volumes that are marked as 'DeleteOnTermination' will be deleted automatically.

Once this is done, we remove the virtual machine's entry from Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html).  You can no longer use the [Host](../host/index.html) module with this virtual machine, unless you call _[createVm()](#createvm)_ once more first.

__NOTE__

* Any block volumes that are __not__ marked as 'DeleteOnTermination' will survive a call to _destroyVm()_, and Amazon will continue to charge you for the storage of these block volumes.

## startVm()

Use _$st->usingEc2()->startVm()_ to start a virtual machine that was previous stopped using _[stopVm()](#stopvm)_.

{% highlight php %}
$st->usingEc2()->startVm($vmName);
{% endhighlight %}

where:

* _$vmName_ is the name of the virtual machine that you created earlier

This action makes a _startInstances_ API call to EC2.

Please remember to use _[destroyVm()](#destroyvm)_ at the end of your test to delete the virtual machine from disk.

## stopVm()

Use _$st->usingEc2()->stopVm()_ to stop a virtual machine that was previously started using _[createVm()](#createvm)_.

{% highlight php %}
$st->usingEc2()->stopVm($vmName);
{% endhighlight %}

where:

* _$vmName_ is the name of the virtual machine that you created earlier

This action makes a _stopInstances_ API call to EC2.  The virtual machine image isn't deleted from disk, and the virtual machine still exists in Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html), even after Storyplayer finishes running.

Please remember to use _[destroyVm()](#destroyvm)_ at the end of your test to delete the virtual machine from disk.

## restartVm()

Use _$st->usingEc2()->restartVm()_ to reboot a virtual machine that was previously started using _[createVm()](#createvm)_.

{% highlight php %}
$st->usingEc2()->restartVm($vmName);
{% endhighlight %}

where:

* _$vmName_ is the name of the virtual machine that you created earlier

This action makes a _stopInstances_ API call to EC2, followed by a _startInstances_ API call.  (We currently don't use an explicit _rebootInstances_ API call).  If the virtual machine doesn't shutdown, or doesn't boot back up as expected, an exception is thrown.

## powerOffVm()

Amazon EC2 currently doesn't support this operation.  For now, we've made this an alias for _[usingEc2()->stopVm()](#stopvm)_, but this might become an explicit no-op in the future.