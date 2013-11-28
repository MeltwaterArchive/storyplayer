---
layout: modules-ec2
title: fromEc2()
prev: '<a href="../../modules/ec2/index.html">Prev: The Amazon EC2 Module</a>'
next: '<a href="../../modules/ec2/fromEc2Instance.html">Next: fromEc2Instance()</a>'
---

# fromEc2()

_fromEc2()_ allows you to get information about your EC2 account.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromEc2_.

## Behaviour And Return Codes

Every action returns either a value on success, or _NULL_ on failure.  These actions do not throw an exception if you attempt to work with an unknown host.

## getImage()

Use _$st->fromEc2()->getImage()_ to get the full information on a single EC2 image registered to your account.

{% highlight php %}
$imageData = $st->fromEc2()->getImage($amiId);
{% endhighlight %}

where:

* _$amiId_ is the AMI ID of the image to find
* _$imageData_ is an array of data about the image, or NULL on failure.

For information about the structure of _$imageData_, please see [the Amazon SDK docs](http://docs.aws.amazon.com/aws-sdk-php-2/latest/class-Aws.Ec2.Ec2Client.html#_describeImages).

This method was added primarily as a helper for other Prose modules.

## getInstance()

Use _$st->fromEc2()->getInstance()_ to get the full information on a single EC2 instance registered to your account.

{% highlight php %}
$instanceData = $st->fromEc2()->getInstance($instanceName);
{% endhighlight %}

where:

* _$instanceName_ is the name (not the ID) of the EC2 instance to find
* _$instanceData_ is an array of data about the instance, or NULL on failure.

For information about the structure of _$instanceData_, please see [the Amazon SDK docs](http://docs.aws.amazon.com/aws-sdk-php-2/latest/class-Aws.Ec2.Ec2Client.html#_describeInstances).

This method was added primarily as a helper for other Prose modules.
