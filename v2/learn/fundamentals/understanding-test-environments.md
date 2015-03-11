---
layout: v2/learn-fundamentals
title: Understanding Test Environments
prev: '<a href="../../learn/fundamentals/understanding-system-under-test.html">Prev: Understanding The System Under Test</a>'
next: '<a href="../../learn/fundamentals/belt-and-braces-testing.html">Next: Belt and Braces Testing</a>'
updated_for_v2: true
---
# Understanding Test Environments

Storyplayer runs [stories](understanding-stories.html) that test a specific [system under test](understanding-system-under-test.html) running inside a specific [test environment](understanding-test-environments.html).

Support for test environments has been completely overhauled in Storyplayer v2. You can now write stories that will run against different test environments - perfect for reusing the same tests throughout your develop-and-deploy pipeline.

## What Is A Test Environment?

A _test environment_ is the computer (or computers) where you've deployed the software that you are testing. It can be any of:

* the same computer Storyplayer is running on
* a virtual machine running on the same computer as Storyplayer (e.g. managed by [Vagrant](http://www.vagrantup.com))
* a virtual machine running somewhere else (e.g. Amazon EC2)
* an existing environment that you've already deployed (e.g. a testing, staging, or production environment)

Examples of test environments include:

* `vagrant-centos6` - a VM that runs on my local computer, managed by [Vagrant](http://www.vagrantup.com).
* `datasift-staging` - the DataSift staging platform, where we test all of our releases before they go on the production environment
* `datasift-production` - the public DataSift platform, the one that our customers use

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

The test environments you define will either be for [testing your code](../test-your-code/defining-a-test-environment.html) or for [testing your platform](../test-your-platform/defining-test-environments.html). Follow those links to see what each kind of test environment looks like.

## What Does Storyplayer Need To Know About A Test Environment?

A test environment config file describes all of the machines that Storyplayer will use for your tests.

Each test environment contains at least one group of machines. We group machines together by `type` - that's the adapter that Storyplayer will use to manage that group of machines. ([A full list of adapters can be found here](../../using/configuration/test-environment-config.html#group_adapters), and we also have [Test Environments section](../../using/test-environments/index.html) that looks at each type in detail.)

For each machine in each group, Storyplayer needs to know the machine's _name_, _operating system_, and _role(s)_.

* Storyplayer uses the name when talking to Vagrant, EC2 and other platforms that can create and destroy virtual machines for you.

  For example, in Storyplayer's `vagrant-centos6.json` config file, we've used the machine name `default`, because this is the name Vagrant uses for a single virtual machine.

* Command-line sysadmin tools work differently from operating system to operating system. They're even different on Linux distributions :(

  Storyplayer needs to know which operating system a machine is running, so that it knows how to interact with that machine's sysadmin tools (for example, discovering an IP address assigned via DHCP). ([A full list of operating systems can be found here.](../../using/configuration/test-environment-config.html#operating_system_adapters))

* Every machine needs at least one role. A role is just a text label that says what your machine does. Each machine can have multiple roles, and multiple machines can have the same role.

  In your stories, you never access machines by name - you access them by role. That allows your stories to run against multiple test environments, as long as the role names are consistent across your different environments.

Every machine can have an `appSettings` section. You can use this to store settings that are different from machine to machine:

{% highlight php startinline %}
$loginUrl = fromFirstHostWithRole('web-server')->getAppSetting('storyplanner.loginUrl');
usingBrowser()->gotoPage('http://{$loginUrl}');
{% endhighlight %}

If you don't want Storyplayer to create and destroy your test environment, each machine will need an `ipAddress` and `hostname` setting, so that Storyplayer knows where to find the machine on your network.

Or, if you want Storyplayer to create and destroy your test environment, each group will have a setting to say which [provisioning engine](../../using/configuration/test-environment-config.html#provisioning_engines) to use. When you run Storyplayer, it will create your test environment, use the provisioning engine you've chosen, and then log into each machine in turn to discover the `ipAddress` and `hostname` that has been assigned.

## That Sounds Like A Lot!

Test environments can seem daunting at first, especially if you've no sysadmin experience. However, help is at hand :)

Take a look at our [worked examples](../worked-examples/index.html). Each example walks you through the process we took to test a real system, including creating the test environments for each project that we tested. You can use these config files as templates for your own projects, adapting them to fit your own project.

## Further Reading

* [The Guide To Testing Your Code](../test-your-code/index.html) and [The Guide To Testing Your Platform](../test-your-platform/index.html) both include chapters explaining how to define test environments for each kind of test suite.
* [The Test Environments section](../../using/test-environments/index.html) of the manual looks at each of the different test environments in turn and in detail.
* [The Test Environment Configuration chapter](../../using/configuration/test-environment-config.html) is a complete reference to everything that you can put into a test environment configuration file.
* And we have several [worked examples](../worked-examples/index.html) that explain the process used to define the test environments for each project that we've tested.