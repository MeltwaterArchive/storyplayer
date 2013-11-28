---
layout: modules-ec2
title: expectsEc2Image()
prev: '<a href="../../modules/ec2/fromEc2Instance.html">Prev: fromEc2Instance()</a>'
next: '<a href="../../modules/ec2/usingEc2.html">Next: usingEc2()</a>'
---

# expectsEc2Image()

_expectsEc2Image()_ allows you to test the condition of an AMI registered to your Amazon account.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\ExpectsEc2Image_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## hasFailed()

Use _$st->expectsEc2Image()->hasFailed()_ to make sure that an AMI you previously registered failed to build.

{% highlight php %}
$st->expectsEc2Image($amiID)->hasFailed();
{% endhighlight %}

where:

* _$amiId_ is the AMI ID of the image to find

## isAvailable()

Use _$st->expectsEc2Image()->isAvailable()_ to make sure that an AMI you previously registered is ready for use.

{% highlight php %}
$st->expectsEc2Image($amiID)->isAvailable();
{% endhighlight %}

where:

* _$amiId_ is the AMI ID of the image to find

## isPending()

Use _$st->expectsEc2Image()->isPending()_ to make sure that an AMI you previously registered is currently being built.

{% highlight php %}
$st->expectsEc2Image($amiID)->isPending();
{% endhighlight %}

where:

* _$amiId_ is the AMI ID of the image to find