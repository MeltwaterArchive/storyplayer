---
layout: v2/modules-aws
title: fromAws()
prev: '<a href="../../modules/aws/index.html">Prev: The Amazon AWS Module</a>'
next: '<a href="../../modules/ec2/index.html">Next: The Amazon EC2 Module</a>'
---

# fromAws()

_fromAws()_ allows you to obtain PHP clients for Amazon Web Services.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromAws_.

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  These actions do not throw an exception if you attempt to work with an unknown host.

## getEc2Client()

Use `$st->fromAws()->getEc2Client()` to obtain an EC2 client object from the official Amazon AWS SDK.

{% highlight php %}
$ec2Client = $st->fromAws()->getEc2Client();
{% endhighlight %}

where:

* `$ec2Client` is the AWS SDK client for working with the Amazon EC2 API

When working with the EC2 client, you'll want to refer to [the PHP SDK documentation](http://docs.aws.amazon.com/aws-sdk-php-2/guide/latest/service-ec2.html) and the [Amazon EC2 API documentation](http://docs.aws.amazon.com/AWSEC2/latest/APIReference/OperationList-query.html) together.