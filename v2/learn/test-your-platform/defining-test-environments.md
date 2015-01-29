---
layout: v2/learn-fundamentals
title: Understanding Test Environments
prev: '<a href="../../learn/fundamentals/understanding-system-under-test.html">Prev: Understanding The System Under Test</a>'
next: '<a href="../../learn/fundamentals/user-stories.html">Next: User Stories</a>'
---
# Understanding Test Environments

Storyplayer runs [stories](understanding-stories.html) that test a specific [system under test](understanding-system-under-test.html) running inside a specific [test environment](understanding-test-environments.html).

Support for test environments has been completely overhauled in Storyplayer v2.

## What Is A Test Environment?

A _test environment_ is the computer (or computers) where you've deployed the software that you are testing. It can be any of:

* the same computer Storyplayer is running on
* a virtual machine running on the same computer as Storyplayer (e.g. managed by [Vagrant](http://www.vagrantup.com))
* a virtual machine running somewhere else (e.g. Amazon EC2)
* an existing environment that you've already deployed (e.g. a testing, staging, or production environment)

For each test environment, you create a simple JSON config file, and then pass the name of the config file to Storyplayer using the `-t` switch. The JSON file goes in your project's `.storyplayer/test-environments/` folder:

<pre>
&lt;project-folder&gt;
|- .storyplayer/
|  |- systems-under-test/
|  |  \- storyplayer-2.0.json
|  \- test-environments/
|     |- vagrant-centos6.json
|     |- datasift-staging.json
|     \- datasift-production.json
|- storyplayer.json
</pre>

Storyplayer uses the JSON file's filename as the name of the test environment.

The test environments you define will either be for [testing your code](../test-your-code/index.html) or for [testing your platform](../test-your-platform/index.html).

## Test Environments For Component Tests

Test environments that test your code (aka component tests) are normally virtual machines that run on the same machine as Storyplayer.

{% highlight json linenos %}
[
    {
        "type": "LocalVagrantVms",
        "details": {
            "machines": {
                "default": {
                    "osName": "centos6",
                    "roles": [
                        "web-server",
                        "upload-target"
                    ]
                }
            }
        },
        "provisioning": {
            "engine": "dsbuild"
        }
    }
]
{% endhighlight %}

This example config file:

* defines a group of machines that:
  * use Storyplayer's `LocalVagrantVms` adapter
  * use Storyplayer's `dsbuild` adapter for provisioning / orchestration
* creates a single machine called `default` (this matches Vagrant's name for a single virtual machine)
  * the machine is running the CentOS 6.x Linux distribution
  * the machine has two roles - `web-server` and `upload-target`
  * Storyplayer will use the `LocalVagrantVms` adapter to manage this machine

Let's break this down:

### Test Environment Configs Are Arrays

The outermost part of a test environment config file is always a JSON array:

{% highlight php %}
[
]
{% endhighlight %}

This allows us to define any mix of machine types that we need for a test environment.

### Group Machines By Type

Inside the outer array, we put a JSON object for each group of machines in the test environment. We create one group for each type of machine in the test environment.

{% highlight json %}
[
    {
        "type": "LocalVagrantVms",
        ...
    }
]
{% endhighlight %}

In our example, we've defined a group that use Storyplayer's `LocalVagrantVms` adapter.

## Test Environments For Platform Tests


## Further Reading