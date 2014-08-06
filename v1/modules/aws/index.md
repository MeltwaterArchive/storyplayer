---
layout: v1/modules-aws
title: The Amazon AWS Module
prev: '<a href="../../modules/index.html">Prev: Storyplayer Modules</a>'
next: '<a href="../../modules/aws/fromAws.html">Next: fromAws()</a>'
---

# The Amazon AWS Module

The __AWS__ module allows you to obtain Amazon Web Service client objects from the official Amazon AWS SDK.

The source code for this Prose module can be found in this PHP class:

* DataSift\Storyplayer\Prose\FromAws

## Using The Official Amazon SDK

The [AWS SDK for PHP](http://docs.aws.amazon.com/aws-sdk-php-2/guide/latest/index.html) provides a set of PHP clients for Amazon's Web Services, including EC2 and S3.  Storyplayer wraps these up in Prose modules (such as the [EC2 module](../ec2/index.html)), but if you need to use AWS functionality that Storyplayer doesn't yet provide, the __AWS__ module gives you access to the official AWS SDK.

The SDK's documentation works best when you also refer to [the latest API documentation](http://docs.aws.amazon.com/AWSEC2/latest/APIReference/OperationList-query.html).

## Dependencies

We use the official Amazon SDK, which should be automatically installed when you install Storyplayer.

You'll probably want to use one of our [supported provisioning engines](../provisioning/index.html) too.

## Configuring The AWS Module

Add the following to your storyplayer.json or per-environment configuration file:

{% highlight json %}
{
    "environments": {
        "env-name": {
            "aws": {
                "key": "your-key",
                "secret": "your-secret",
                "region": "eu-west-1"
            }
        }
    }
}
{% endhighlight %}

where:

* _env-name_ is the name of your test environment (or 'defaults' to apply to all test environments)
* _key_ is your Amazon AWS API key
* _secret_ is your Amazon AWS API secret token
* _region_ is the AWS region you are going to operate on

## Using The AWS Module

The basic format of an action is:

{% highlight php %}
$client = $st->fromAws()->ACTION();
{% endhighlight %}