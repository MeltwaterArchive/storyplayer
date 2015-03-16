---
layout: v2/modules-ec2
title: fromEc2()
prev: '<a href="../../modules/ec2/index.html">Prev: The Amazon EC2 Module</a>'
next: '<a href="../../modules/ec2/fromEc2Instance.html">Next: fromEc2Instance()</a>'
---

# fromEc2()

_fromEc2()_ allows you to get information about your EC2 account.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromEc2_.

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  These actions do not throw an exception if you attempt to work with an unknown host.

## getImage()

Use `fromEc2()->getImage()` to get the full information on a single EC2 image registered to your account.

{% highlight php startinline %}
$imageData = fromEc2()->getImage($amiId);
{% endhighlight %}

where:

* `$amiId` is the AMI ID of the image to find
* `$imageData` is an array of data about the image, or NULL on failure.

For information about the structure of `$imageData`, please see [the Amazon SDK docs](http://docs.aws.amazon.com/aws-sdk-php-2/latest/class-Aws.Ec2.Ec2Client.html#_describeImages).

This method was added primarily as a helper for other modules.

## getInstance()

Use `fromEc2()->getInstance()` to get the full information on a single EC2 instance registered to your account.

{% highlight php startinline %}
$instanceData = fromEc2()->getInstance($instanceName);
{% endhighlight %}

where:

* `$instanceName` is the name (not the ID) of the EC2 instance to find
* `$instanceData` is an array of data about the instance, or NULL on failure.

For information about the structure of `$instanceData`, please see [the Amazon SDK docs](http://docs.aws.amazon.com/aws-sdk-php-2/latest/class-Aws.Ec2.Ec2Client.html#_describeInstances).

This method was added primarily as a helper for other modules.
