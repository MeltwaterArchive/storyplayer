---
layout: v2/modules-ec2
title: usingEc2Instance()
prev: '<a href="../../modules/ec2/usingEc2.html">Prev: usingEc2()</a>'
next: '<a href="../../modules/assertions/index.html">Next: The Assertions Module</a>'
---

# usingEc2Instance()

_usingEc2Instance()_ allows you to manipulate EC2 virtual machines (known as instances) that you have previously created using _[usingEc2()->createVm()](usingEc2.html#createvm)_.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingEc2Instance_.

## Behaviour And Return Codes

Every action makes changes to the virtual machine.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, an exception is throw. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action will be successful.

## createImage()

Use `$st->usingEc2Instance()->createImage()` to create an new Amazon Machine Image (AMI) from a running EC2 instance.

{% highlight php %}
$st->usingEc2Instance($vmName)->createImage($imageName);
{% endhighlight %}

where:

* `$vmName` is the name of the virtual machine that you created earlier
* `$imageName` is the name for your new AMI (must be unique)

## markAllVolumesAsDeleteOnTermination

Use `$st->usingEc2Instance()->markAllVolumesAsDeleteOnTermination()` tell EC2 to delete all of the block devices that are attached to the instance whenever the instance is destroyed using _[usingEc2()->destroyVm()](usingEc2.html#destroyvm)_.

{% highlight php %}
$st->usingEc2Instance($vmName)->markAllVolumesAsDeleteOnTermination();
{% endhighlight %}

where:

* `$vmName` is the name of the virtual machine that you created earlier
