---
layout: v2/modules-ec2
title: The Amazon EC2 Module
prev: '<a href="../../modules/aws/fromAws.html">Prev: fromAws()</a>'
next: '<a href="../../modules/ec2/fromEc2.html">Next: fromEc2()</a>'
---

# The Amazon EC2 Module

The __EC2__ module allows you to create and destroy virtual machines using the popular [Amazon Elastic Compute Cloud](http://aws.amazon.com/ec2/).  Once a virtual machine has been created, it's then available to be used by the [Host](../host/index.html) and [Provisioning](../provisioning/index.html) modules.

Additionally, the __EC2__ module allows you to convert an EC2 virtual machine into an EC2 image.

The source code for this module can be found in these PHP classes:

* `Prose\Ec2ImageBase`
* `Prose\Ec2InstanceBase`
* `Prose\FromEc2`
* `Prose\FromEc2Image`
* `Prose\FromEc2Instance`
* `Prose\UsingEc2`
* `Prose\UsingEc2Instance`

## Does Your Code Actually Work?

Unit tests are a vitally important part of your approach to quality, and they go a long way to making sure that each of your classes and functions function as intended.  You should be writing lots of high-quality unit tests, and every time you find a bug, you should also write a test for that bug too.

The thing is, _you can't rely just on unit tests_.  It's perfectly possible to have so many unit tests that every single line of code you ship is covered by at least one unit test ... and for that code to still have bugs.  Why?  Unit testing is focused on testing your smallest building blocks (especially if you use a lot of mocks in your tests); it doesn't test whether all of these blocks work when they're wired up together.  This risk is amplified the more mocks you have in your tests.

That's where Storyplayer and this EC2 module come in.

Using the EC2 module, you can create a brand new virtual machine for every single test that you run, deploy your app into that virtual machine, and use it to catch the following types of problems:

* _Can't deploy because of broken deployment scripts_ - these might not show up on a server where you're upgrading software that's already installed, but they always show up when you're forced to install software on a machine for the first time
* _Can't deploy because of obsolete dependencies_ - such as a dependency that's available in one version of Ubuntu, but isn't available any more in the latest version (a problem which happened with Chrome 26 on fresh installs of Ubuntu 13.04)
* _Can't start because of missing dependencies_ - such as you've forgotten something, but you haven't spotted it because it either comes by default on your dev desktop or you've installed it by hand on your desktop and forgotten to also add it to your deployment steps
* _Can't start because of broken permissions_ - such as a missing user on your server, or failing to make sure that the code's files have the right permissions and ownership
* _Can't start because of missing / broken init.d scripts_ - you might have written some by hand for your production server, but forgotten to add them to your next deployment
* _Can't start and/or stop because of bugs in your code_ - if you've written a daemon, they're unusable if they don't start up; and they have to be kill -9'd if they won't shutdown
* _Can't accept requests over HTTP / ZeroMQ et al_ - bugs in your socket initialisation won't show up if you use mocks in your unit tests
* _Can't correctly save / retrieve data from a real database_ - especially if dealing with transactions,  rollbacks, and foreign keys - three areas where mocked database calls can let bugs slip through

... plus all of the tests needed to make sure your app does the things your users need it to!

## What Is The Cost Of Your Working Code?

You can also start to catch a whole heap of _[non-functional requirements](https://en.wikipedia.org/wiki/Non-functional_requirement)_ too, such as:

* _Can't process requests as quickly as required_ - check for latency
* _Runs substantially slower than what's currently running in production_ - check for performance regression (don't forget to check for functionality regressions too)
* _Can't support the required number of simultaneous requests_ - check for scalability and resource contention issues
* _Slows down or pauses when database backups are running_ - check for how your sysadmin tasks interact with the app
* _Crashes when placed under load_ - check for race conditions
* _Doesn't fail gracefully when database servers are down_ - check for resilience
* _Doesn't fail gracefully when internal services it depends upon are down_ - check for resilience
* _plus so much more ..._

These kind of tests help you prove not only that your code works, but that your code performs well too.  DataSift's [SavageD](https://github.com/datasift/SavageD) realtime server/process monitoring daemon can help to keep an eye on these important metrics.

You're limited only by your imagination, and the performance limitations that come from testing inside a virtual machine.

## Why Amazon EC2?

We support EC2 because it is a popular hosting solution for creating temporary virtual machines 'in the cloud'.  Many firms use EC2 for their production environments, but even if you don't, it can often be a great place to spin up temporary machines for use in your tests.

Full details of all of our supported hosting solutions are available in [the test environments section of this manual](../../test-environments/index.html).

## Dependencies

We use the official Amazon SDK, which is automatically installed into your `vendor/` folder when you install Storyplayer.

## Before You Use The EC2 Module

Before you write any tests for your stories, test your EC2 instance by hand. Make sure you know the AMI ID of the EC2 image that you're going to base your EC2 instances on.  This step can save you a lot of time when you design a new virtual machine for a series of tests.

## Configuring The EC2 Module

Add the following to `storyplayer.json[.dist]` config file:

{% highlight json %}
{
    "moduleSettings": {
        "aws": {
            "ec2": {
                "keyPairName": "QA_AWS",
                "sshUsername": "qa",
                "sshKeyFile": "./QA_AWS.pem"
            }
        }
    }
}
{% endhighlight %}

where:

* _keyPairName_ is the name of the key pair you've defined at EC2 for your virtual machines
* _sshUsername_ is the name of default user on your EC2 image
* _sshKeyFile_ is the path to your copy of the private key to use when

## Using The EC2 Module

The basic format of an action is:

{% highlight php startinline %}
MODULE()->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromEc2()](fromEc2.html)_ - get data about your EC2 account
* _[usingEc2()](usingEc2.html)_ - create and destroy EC2 virtual machines
* _[expectsEc2Image()](expectsEc2Image.html)_ - test the state of an EC2 image
* _[fromEc2Instance()](fromEc2Instance.html)_ - get data about an EC2 virtual machine
* _[usingEc2Instance()](usingEc2Instance.html)_ - change a running EC2 virtual machine

Once you've used the EC2 module to start your virtual machine, you'll then use the _[Hosts module](../hosts/index.html)_ to work with the VM until your test destroys the VM.

## An Example

Here's one of our internal stories, which we use to create the EC2 images we use in our tests:

{% highlight php startinline %}
$story = newStoryFor('EC2')
         ->inGroup('Image Preparation')
         ->called('Create CentOS 6 image');

$story->addTestTeardown(function() {
    // get the checkpoint
    $checkpoint = getCheckpoint();

    // destroy the instance we created
    if (isset($checkpoint->instanceName)) {
        // do we have a test VM to destroy?
        $hostDetails = fromHostsTable()->getDetailsForHost($checkpoint->instanceName);
        if ($hostDetails !== null) {
            // destroy this host
            usingEc2()->destroyVm($checkpoint->instanceName);
        }
    }

    // destroy the image that we booted to test
    if (isset($checkpoint->imageName)) {
        // do we have a test VM to destroy?
        $hostDetails = fromHostsTable()->getDetailsForHost($checkpoint->imageName);
        if ($hostDetails !== null) {
            // destroy this host
            usingEc2()->destroyVm($checkpoint->imageName);
        }
    }
});

$story->addAction(function() {
    // we're going to store some information in here
    $checkpoint = getCheckpoint();

    // what are we calling this host?
    $checkpoint->instanceName = 'centos-6-box';

    // create the VM, based on the official CentOS AMI
    usingEc2()->createVm($checkpoint->instanceName, "centos6", "ami-75190b01", 't1.micro', "default");

    // we need to make sure the root filesystem is destroyed on termination
    usingEc2Instance($checkpoint->instanceName)->markAllVolumesAsDeleteOnTermination();

    // we need to wait for a bit to allow EC2 to catch up :(
    usingTimer()->waitFor(function($st) use($checkpoint) {
        // we need to run a command (any command) on the host, to get it added
        // to SSH's known_hosts file
        usingHost($checkpoint->instanceName)->runCommandAsUser("ls", "root");
    }, 'PT5M');

    // run our bootstrap script against the host
    //
    // this script creates the default user we are going to use, and then
    // runs our standard 'prep' Ansible playbook to bring the image up
    // to our base line
    $ipAddress  = fromHost($checkpoint->instanceName)->getIpAddress();
    $anSettings = fromEnvironment()->getAppSettings('ansible');
    $command    = "cd '{$anSettings->dir}' && scripts/provision-box.sh '{$ipAddress}' 'root' ./prep-centos-6.3.yml";
    usingShell()->runCommand($command);

    // turn the image into an AMI
    $checkpoint->imageName = 'centos-6-template-' . date('YMD-His');
    $checkpoint->amiId = usingEc2Instance($checkpoint->instanceName)->createImage($checkpoint->imageName);

    // wait for the AMI to be available
    usingTimer()->waitFor(function($st) use($checkpoint) {
        expectsEc2Image($checkpoint->amiId)->isAvailable();
    }, 'PT5M');
});

$story->addPostTestInspection(function() {
    // the information to guide our checks is in the checkpoint
    $checkpoint = getCheckpoint();

    // this is the ID of the AMI we just created
    $amiId = $checkpoint->amiId;

    // let's create a VM using our new image
    usingEc2()->createVm($checkpoint->imageName, 'centos6', $amiId, 't1.micro', "default");

    // let's run a command against the VM
    //
    // this proves that our preferred user has been created
    usingHost($checkpoint->imageName)->runCommand("ls");
});
{% endhighlight %}

## Instance Naming On EC2

An EC2 account is often a shared resource used by multiple developers in a company.  This can cause problems when two or more people try to create EC2 instances with the same name.  Amazon doesn't currently force each instance to have a unique name, but it's almost impossible for Storyplayer to correctly work out which instance you're trying to work with unless the names are unique.

Storyplayer's solution is to create EC2 instances with the following names:

* $environmentName.$vmName

where:

* `$environmentName` is the name of the test environment you are testing against
* `$vmName` is the name of the VM that you are using in your stories

For example, if I run Storyplayer like this:

<pre>
storyplayer -e stu-office stories/ec2/CanCreateEc2InstanceStory.php
</pre>

and that story includes:

{% highlight php startinline %}
usingEc2()->createVm('instance-test');
{% endhighlight %}

then the EC2 instance will be called __stu-office.instance-test__.