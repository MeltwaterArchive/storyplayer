---
layout: configuration
title: Adding App Settings To Your Config File
prev: '<a href="../configuration/storyplayer-json.html">Prev: The storyplayer.json File</a>'
next: '<a href="../configuration/logging.html">Next: Logging</a>'
---

# Adding App Settings To Your Config File

There's absolutely nothing stopping you hard-coding into your tests all of the settings about the apps that you are testing.  Your tests will execute fine.

But what happens when the config for one of your apps changes?  You might end up having to edit multiple tests, just to make sure all of your tests are using the new settings.  That takes time, and it's something that you can avoid by putting these app settings into your config file instead.

## Default App Settings

You can add default settings for your apps to your [storyplayer.json](storyplayer-json.html) file.  They go under the _environments->defaults_ section of your config file, [as shown in the example at the bottom of this page](#an_example_config).

__Storyplayer doesn't understand these settings at all__; because they are not settings that affect how Storyplayer executes.  As far as Storyplayer is concerned, this section of the config file is there only for your tests to [access and use](#accessing_the_app_settings).

The only thing Storyplayer can do with these settings is override them, using [per-user config files](user-config.html) and [per-environment config files](environment-config.html).

## Overriding The Defaults

You can (and should) override these settings from time to time:

* each environment that you test against will probably have unique URLs and hostnames that Storyplayer needs to talk to, and you'll want to override these using your [per-environment config](environment-config.html)
* each environment might also have unique test credit and debit card numbers to use, with your production environment having live test cards and your staging environment having dummy test cards that are recognised by your payment gateway
* if your tests create test users, you might override the default details for a user (their name, or their email address) using your [per-user config](user-config.html).

[The algorithm Storyplayer uses to merge the default settings and per-environment settings](environment-config.html#how_storyplayer_merges_the_environment_configurations) is designed to do the obvious thing, and leave your story with the set of merged settings that you expect.

## Accessing The App Settings

You can access your app settings using _[$st->fromEnvironment()->getAppSettings()](../modules/environment/fromEnvironment.html#getappsettings)_ in your tests:

{% highlight php %}
$settings = $st->fromEnvironment->getAppSettings('ogre');
{% endhighlight %}

This returns a plain old PHP object that you can then use inside your tests.

## An Example Config

Here's an example taken from one of our config files here at [DataSift](http://datasift.com).

{% highlight json %}
{
    "environments": {
        "defaults": {
            "acl": {
                "httpPort": 3009,
                "zmqInputPort": 5556,
                "zmqOutputPort": 5071
            },
            "connectionManager": {
                "httpPort": 8101,
                "zmqOutputPort": 5001,
                "zmqCommandPort": 5002,
                "zmqAckPort": 5003,
                "zmqRequestPort": 5004
            },
            "connectors": {
                "elasticsearch": {
                    "label": "QA Test Elastic Search",
                    "output_type": "elasticsearch",
                    "output_params": {
                        "delivery_frequency": 60,
                        "max_size": 1048576,
                        "host": "172.16.221.222",
                        "db_name": "push"
                    }
                },
            },
            "definitionManager": {
                "httpPort": 8105
            },
            "graphite": {
                "url": "http://172.16.221.223:8080"
            },
            "maskManager": {
                "httpPort": 8102,
                "zmqPort": 5561
            },
            "ogre": {
                "httpPort": 5102,
                "zmqWritePort": 5096,
                "zmqReadPort": 5097
            },
            "pickle-node": {
                "httpPort": 3002,
                "zmqInputPort": 5093,
                "zmqOutputPort": 5090
            },
            "prism": {
                "httpPort": 3055,
                "zmqWritePort": 5092,
                "zmqShard1Port": 5093,
                "zmqShard2Port": 5094,
                "zmqShard3Port": 5095
            },
            "savaged": {
                "httpPort": 8091
            },
            "statsd": {
                "host": "172.16.221.223"
            },
            "users": {
                "generator": "DataSift\\Tests\\UserLib\\SimpleUserManager"
            }
        }
    }
}
{% endhighlight %}