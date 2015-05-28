---
layout: v2/using-configuration
title: Test Environment Configuration
prev: '<a href="../../using/configuration/system-under-test-config.html">Prev: System Under Test Configuration</a>'
next: '<a href="../../using/configuration/provisioning-parameters.html">Next: Provisioning Parameters</a>'
updated_for_v2: true
---

# Test Environment Configuration

The _test environment config file_ contains settings for a _specific test environment_ that you can run your stories against.

Create one _test environment config file_ for each test environment that you want to test against. As you add new test environments (e.g. your 'staging' or 'production' environments), create an additional _test environment config file_ for that test environment. That way, you can easily run the same tests against any of your test environments.

Use the `-t` command-line switch to tell Storyplayer which _test environment config file_ to load when you run your tests:

{% highlight bash %}
vendor/bin/storyplayer -t vagrant-vbox-centos6-ssl
{% endhighlight %}

## Location

Your _test environment config file_ goes inside the `storyplayer/test-environments` folder. (__Note__ that the folder is 'environments' plural, not 'environment'!)

There is one _test environment config file_ for each test environment that you are deploying your system under test into.

<pre>
project-root-folder/
|- storyplayer/
   |- test-environments/
      |- &lt;test-environment-name&gt;
         | main.php
</pre>

For example, Storyplayer ships with these files for its own test environments:

* `storyplayer/test-environments/vagrant-vbox-centos6-ssl/main.php` - for testing Storyplayer on Centos6 using a Virtualbox VM controlled by Vagrant. You select this test environment using the `-t vagrant-vbox-centos6-ssl` command-line switch.
* `storyplayer/test-environments/vagrant-vbox-ubuntu-14.04-server/main.php` - for testing Storyplayer on Ubuntu-14.04 (a Long-Term-Support release) using a Virtualbox VM controlled by Vagrant. You select this test environment using the `-t vagrant-vbox-ubuntu-14.04-server` command-line switch.

## Contents

Each _test environment config file_ is a PHP file:

{% highlight php %}
#<?php

use Storyplayer\TestEnvironments\Vagrant_GroupAdapter;
use Storyplayer\TestEnvironments\Vagrant_VirtualboxHostAdapter;
use Storyplayer\TestEnvironments\CentOS_6_HostAdapter;
use Storyplayer\TestEnvironments\Dsbuild_Adapter;

$testEnv = newTestEnvironment();

$group1 = $testEnv->newGroup('vagrant', new Vagrant_GroupAdapter);
$group1->newHost('default', new Vagrant_VirtualboxHostAdapter)
       ->setOperatingSystem(new CentOS_6_HostAdapter)
       ->setRoles([
            "host_target",
            "upload_target",
            "ssl_target",
            "zmq_target",
        ])
       ->setStorySettings((object)[
            "host" => (object)[
                "expected" => "successfully retrieved this storySetting :)",
            ],
            "http" => (object)[
                "homepage" => "https://storyplayer.test/",
            ],
            "user" => (object)[
                "username" => "vagrant",
                "group"    => "vagrant",
            ],
            "zmq" => (object)[
                "single" => (object)[
                    "inPort"  => 5000,
                    "outPort" => 5001,
                ],
                "multi"  => (object)[
                    "inPort"  => 5002,
                    "outPort" => 5003,
                ],
            ]
        ]);

$prov1 = new Dsbuild_Adapter();
$prov1->setExecutePath("dsbuild.sh");
$group1->addProvisioningAdapter($prov1);

$testEnv->setModuleSettings((object)[
    "http" => (object)[
        "validateSsl" => false,
    ],
]);

// all done
return $testEnv;
{% endhighlight %}

A test environment is a list of hosts that together make up the test environment. Hosts are grouped together by how they are controlled (e.g. a group of Vagrant VMs, a group of EC2 VMs, and so on). A test environment can also provide provisioning instructions, and configuration settings for Storyplayer modules.

## Step 1: Importing Adapters

A test environment config file needs to import the adapters that it's going to use. Together, these adapters tell Storyplayer how to interact with your test environment.

{% highlight php startinline %}
use Storyplayer\TestEnvironments\Vagrant_GroupAdapter;
use Storyplayer\TestEnvironments\Vagrant_VirtualboxHostAdapter;
use Storyplayer\TestEnvironments\CentOS_6_HostAdapter;
use Storyplayer\TestEnvironments\Dsbuild_Adapter;
{% endhighlight %}

In this example, we are importing four adapters:

* `Vagrant_GroupAdapter`: the adapter used to manage a group of Vagrant VMs
* `Vagrant_VirtualboxHostAdapter`: the adapter used to manage a Vagrant machine that's running under Virtualbox
* `CentOS_6Adapter`: the adapter used to talk to any test environment host that runs CentOS 6.x
* `Dsbuild_Adapter`: the adapter used to provision using the dsbuild system

All of the available adapters can be find in the `Storyplayer\TestEnvironments` namespace. We have full details about each of them in our detailed section on [test environments](../test-environments/index.html).

## Step 2: Creating A New Test Environment Definition

A test environment config file needs to call `newTestEnvironment()` to create an empty test environment definition:

{% highlight php startinline %}
$testEnv = newTestEnvironment();
{% endhighlight %}

`newTestEnvironment()` has no parameters. It returns a new `TestEnvironment_Definition` object.

## Step 3: Adding Groups To The Definition

Every test environment definition needs at least one group. Create a new group like this:

{% highlight php startinline %}
$group = $testEnv->newGroup($groupId, $groupAdapter);
{% endhighlight %}

where:

* __$testEnv__ is your _test environment definition_ object from Step 2
* __$groupId__ is a string - the name you want to give to your group
* __$groupAdapter__ is an object - the adapter that Storyplayer will use to manage this group of hosts
* __$group__ gets set to a new `TestEnvironment_GroupDefinition` object

For example:

{% highlight php startinline %}
$group1 = $testEnv->newGroup('vagrant', new Vagrant_GroupAdapter);
{% endhighlight %}

<div class="callout info" markdown="1">
#### Why Do We Need Groups?

Storyplayer is designed to test anything from a small website that fits inside a single virtual machine all the way up to a world-leading platform that uses hundreds of servers, such as the DataSift platform.  Some of these larger test environments may contain a mix of machine types (e.g. some EC2 servers, some Docker servers, some physical machines).

Groups give us an easy way of having a mix of machines types in a single test environment.
</div>

<div class="callout info" markdown="1">
#### What Is A Group ID?

It's a name that you choose. Storyplayer will include 'groupId.hostId' in every message it prints or logs about your test environment. This helps you understand exactly which host Storyplayer is interacting with at all times.

Group IDs are especially helpful if your test environment contains multiple groups.
</div>

<div class="callout info" markdown="1">
#### What Are Group Adapters?

These adapters tell Storyplayer how to start and stop all of the hosts defined in the group.  You can find [a full list of available group adapters](../test-environments/group-adapters.html) in our detailed section on [test environments](../test-environments/index.html).

Each group needs a group adapter.
</div>

<div class="callout info" markdown="1">
#### Creating Multiple Groups

Every time you call `$testEnv->newGroup()`, it returns another `TestEnvironment_GroupDefinition` object for you. You can add as many different groups as you need, allowing you to model even the most complex test environment.
</div>

## Step 4: Adding Hosts To Groups

Each group in your test environment needs at least one host. A host is a machine in your test environment. It might be a Vagrant virtual machine, an Amazon EC2 virtual machine, a physical host, or something else.

{% highlight php startinline %}
$hostDef = $group->newHost($hostId, $hostAdapter);
{% endhighlight %}

where:

* __$group__ is your `TestEnvironment_GroupDefinition` from Step 3
* __$hostId__ is a string - it's the alias you want to use for this host
* __$hostAdapter__ is a host adapter object
* __$hostDef__ gets set to a new `TestEnvironment_HostDefinition` object

You would then use the `TestEnvironment_HostDefinition` object to tell Storyplayer all about this host.

Each host definition supports a fluent interface, allowing you to define a host like this:

{% highlight php startinline %}
$group1->newHost('default', new Vagrant_VirtualboxHostAdapter)
       ->setOperatingSystem(new CentOS_6_HostAdapter)
       ->setRoles([
            "host_target",
            "upload_target",
            "ssl_target",
            "zmq_target",
        ])
       ->setStorySettings((object)[
            "host" => (object)[
                "expected" => "successfully retrieved this storySetting :)",
            ],
            "http" => (object)[
                "homepage" => "https://storyplayer.test/",
            ],
            "user" => (object)[
                "username" => "vagrant",
                "group"    => "vagrant",
            ],
            "zmq" => (object)[
                "single" => (object)[
                    "inPort"  => 5000,
                    "outPort" => 5001,
                ],
                "multi"  => (object)[
                    "inPort"  => 5002,
                    "outPort" => 5003,
                ],
            ]
        ]);
{% endhighlight %}

The list of `setXXX()` methods to call depends on which host adapter you are using. You can find full details in our detailed section on [test environments](../test-environments/index.html).

<div class="callout info" markdown="1">
#### What Is A Host ID?

The host ID is the name that your group adapter uses for the test environment machine. Storyplayer uses the host ID when talking to the software that manages your machine.

For example, when you use Vagrant, if you use the command `vagrant status`, it will show you a list of virtual machines:

<pre>
Current machine states:

default                   running (virtualbox)
</pre>

`default` is the name that Vagrant uses for your virtual machine. That is the name you need to use for your host ID.

In our [test environments](../test-environments/index.html) section, there's a page for each type of supported machine, and each page includes full details of where to find the host ID for that type of machine.
</div>

<div class="callout warning" markdown="1">
#### Create A New Host Adapter For Each Host

Storyplayer assumes that each _test environment host definition_ has its own host adapter object.
</div>

## Step 5: Adding Provisioning To A Group

If you are working with an on-demand test environment (e.g. a Vagrant virtual machine), add a provisioning engine to each _test environment group_.

{% highlight php startinline %}
$prov1 = new Dsbuild_Adapter();
$prov1->setExecutePath("dsbuild.sh");
$group1->addProvisioningAdapter($prov1);
{% endhighlight %}

There's a full list of [supported provisioning engines](../test-environments/provisioning.html), with details of how they work and how to configure them, in our detailed section on [test environments](../test-environments/index.html).

## Step 6: Adding Module Settings

[Module settings](module-settings.html) can be added. They apply to the entire test environment. You cannot set them on a per-group or a per-host basis.

{% highlight php startinline %}
$testEnv->setModuleSettings((object)[
    "http" => (object)[
        "validateSsl" => false,
    ],
]);
{% endhighlight %}

* `setModuleSettings()` takes one parameter: a PHP object that contains all of the module settings

## Final Step: Return The Definition

At the very end of your config file, return your _test environment definition_ object back to Storyplayer:

{% highlight php startinline %}
return $testEnv;
{% endhighlight %}

Storyplayer will throw an error if you forget to return it.

## Further Reading

This page has (hopefully) given you an overview of how to configure a test environment. It's a big topic, and the details change depending on what kind of machines you want to configure. Head on over to our detailed [test environments](../test-environments/index.html) section for the specifics.