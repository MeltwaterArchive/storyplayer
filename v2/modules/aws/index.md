---
layout: v2/modules-aws
title: The Amazon AWS Module
prev: '<a href="../../modules/index.html">Prev: Storyplayer Modules</a>'
next: '<a href="../../modules/aws/fromAws.html">Next: fromAws()</a>'
---

# The Amazon AWS Module

The __AWS__ module allows you to obtain Amazon Web Service client objects from the official Amazon AWS SDK.

The source code for this module can be found in this PHP class:

* `Prose\FromAws`

## Using The Official Amazon SDK

The [AWS SDK for PHP](http://docs.aws.amazon.com/aws-sdk-php-2/guide/latest/index.html) provides a set of PHP clients for Amazon's Web Services, including EC2 and S3.  Storyplayer wraps these up in Prose modules (such as the [Amazon EC2 module](../ec2/index.html)), but if you need to use AWS functionality that Storyplayer doesn't yet provide, the __AWS__ module gives you access to the official AWS SDK.

The SDK's documentation works best when you also refer to [the latest API documentation](http://docs.aws.amazon.com/AWSEC2/latest/APIReference/OperationList-query.html).

## Dependencies

We use the official Amazon SDK, which should be automatically installed when you install Storyplayer.

## Configuring The AWS Module

Add the following to your storyplayer.json configuration file:

{% highlight json %}
{
    "moduleSettings": {
        "aws": {
            "key": "your-key",
            "secret": "your-secret",
            "region": "eu-west-1"
        }
    }
}
{% endhighlight %}

where:

* _key_ is your Amazon AWS API key
* _secret_ is your Amazon AWS API secret token
* _region_ is the AWS region you are going to operate on

## Using The AWS Module

The basic format of an action is:

{% highlight php startinline %}
$client = fromAws()->ACTION();
{% endhighlight %}

where __action__ is one of the methods available in the _[fromAws()](fromAws.html)_ module.