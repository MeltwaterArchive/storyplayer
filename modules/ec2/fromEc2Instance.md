---
layout: modules-ec2
title: fromEc2Instance()
prev: '<a href="../../modules/ec2/fromEc2.html">Prev: fromEc2()</a>'
next: '<a href="../../modules/ec2/expectsEc2Image.html">Next: expectsEc2Image()</a>'
---

# fromEc2Instance()

_fromEc2Instance()_ allows you to get information about a specific EC2 instance.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromEc2Instance_.

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  These actions throw an exception if you attempt to work with an unknown host.

## getInstanceIsRunning()

Use `$st->fromEc2Instance()->getInstanceIsRunning()` to determine if the named instance is up and running or not.

{% highlight php %}
$isRunning = $st->fromEc2Instance($vmName)->getInstanceIsRunning();
{% endhighlight php %}

where:

* `$vmName` is the name of the virtual machine that you created earlier using _[$st->usingEc2()->createVm()](usingEc2.html#createvm)_.
* `$isRunning` is _TRUE_ if the EC2 instance is in the running state, or _FALSE_ otherwise.

## getInstanceVolumes()

Use `$st->fromEc2Instance()->getInstanceVolumes()` to get a list of the storage volumes attached to the named instance.

{% highlight php %}
$volumes = $st->fromEc2Instance($vmName)->getInstanceVolumes();
{% endhighlight php %}

where:

* `$vmName` is the name of the virtual machine that you created earlier using _[$st->usingEc2()->createVm()](usingEc2.html#createvm)_.
* `$volumes` is an array of block devices that are attached to the EC2 instance.

## getPublicDnsName()

Use `$st->fromEc2Instance()->getPublicDnsName()` to get the fully-qualified domain name to use to access an EC2 instance.

{% highlight php %}
$fqdn = $st->fromEc2Instance($vmName)->getPublicDnsName();
{% endhighlight php %}

where:

* `$vmName` is the name of the virtual machine that you created earlier using _[$st->usingEc2()->createVm()](usingEc2.html#createvm)_.
* `$fqdn` is the public hostname of the EC2 instance