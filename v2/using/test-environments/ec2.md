---
layout: v2/using-test-environments
title: Creating Test Environments On Amazon EC2
prev: '<a href="../environments/multiple-environments.html">Prev: Testing Against Multiple Environments</a>'
next: '<a href="../environments/physical-hosts.html">Next: Creating Test Environments On Physical Hosts</a>'
---

# Creating Test Environments On Amazon EC2

Amazon's EC2 is a popular hosting solution for anything from small startups to the largest of companies.  You only pay for what you're using, for as long as you use it, which makes it very suitable for hosting on-demand test environments.

Storyplayer can create and destroy test environment for you on Amazon EC2.

## Planning Your EC2 Test Environment

When you're planning your EC2 test environment, you'll need to answer these questions:

1. Why am I using EC2 for hosting?

   Your virtual machines on EC2 are sharing physical hardware and networks with other people's VMs.  As a result, the performance of your EC2 VMs is going to be variable (and a little unpredictable).  This isn't a problem when you're testing if your app works, but it is something you need to take into account when you're testing if your app performs well enough.

   If your production environment doesn't run on EC2, you may be better off doing some or most of your performance testing on a scaled-down copy of your production environment, hosted in the same place.

1. How many EC2 instances (VMs) do I need for my test environment?

   Any story can create as many EC2 instances as it needs - just give each VM a different `$vmName` [when you create it](../modules/ec2/usingEc2.html#createvm).

   The more instances you use in your test, the longer it will take your test to run.  You might find it useful to [run your stories as a batch](../stories/tales.html), where you can reuse your EC2 test environment between individual stories in the batch to save time.

1. How large does each EC2 instance need to be?

   EC2 instances come in [different shapes and sizes](http://aws.amazon.com/ec2/instance-types/#instance-details), with different strengths and weaknesses - and costs.  If your production environment also runs on EC2, then your test environment should use the same EC2 instance type that your production environment uses, to ensure that the testing is representative.

1. Which AMIs (Amazon Machine Images - virtual machine templates) will I use in my test environment?

   EC2 instances are cloned from AMIs.  AMIs are template virtual machines, which define the operating system and the installed set of packages that your instance starts from.

   You can find a large collection of pre-build AMIs in the [AWS Marketplace](https://aws.amazon.com/marketplace/).  Many of these images are royalty-free (you only pay the normal EC2 prices), but there are images that come with per-hour or per-month additional charges.

## Preparing Your EC2 Test Environment

Once you've found an AMI in the Marketplace that you want to use, you can spin up an instance, customise it, and then [save it to Elastic Block Storage as a new AMI](http://docs.aws.amazon.com/AWSEC2/latest/UserGuide/creating-an-ami-ebs.html).  This can save you a lot of time, as your own AMI can already have important packages installed, to reduce the amount of repetitive work done every time you spin up a test environment.

You can automate this using Storyplayer so that you can refresh your AMI every week or month to keep it up to date:

1. _[$st->usingEc2()->createVm()](../modules/ec2/usingEc2.html#createvm)_ to boot the AMI you've picked from the AWS Marketplace
1. _[$st->usingEc2Instance()->markAllVolumesAsDeleteOnTermination()](../modules/ec2/usingEc2Instance.html#markallvolesasdeleteontermination)_ make sure that your EC2 instance is always deleted when you're finished with it (Elastic Block Storage volumes can persist after your EC2 instance has been deleted, and you can be charged for this)
1. Use Storyplayer's [Provisioning module](../modules/provisioning/index.html) to upgrade to the latest operating system packages, and to install common packages that you always want in your test environment
1. _[$st->usingEc2Instance()->createImage()](../modules/ec2/usingEc2Instance.html#createimage)_ to create your own AMI from your running EC2 instance
1. _[$st->usingEc2()->destroyVm()](../modules/ec2/usingEc2.html#destroyvm)_ to shutdown and delete the EC2 instance that you've just customised (your AMI will be safe!)

You can then login to your AWS account via the Amazon AWS website to see your new AMI image and get its _AMI ID_ to use in launching your tests.

## Building Your EC2 Test Environment

You'll find the following modules helpful in building your EC2 test environment in your [TestEnvironmentSetup / Teardown functions](../stories/test-environment-setup-teardown.html):

* The [Amazon EC2 module](../modules/ec2/index.html) for creating and destroying EC2 instances
* The [Hosts module](../modules/host/index.html) for working with your EC2 instances once they have booted
* The [Provisioning module](../modules/provisioning/index.html) for deploying any test-specific packages to your EC2 instances once they have booted

## Destroying Your EC2 Test Environment

When your story or tale has finished, remember to use _[$st->usingEc2()->destroyVm()](../modules/ec2/usingEc2.html#destroyvm)_ to destroy your EC2 instances.  Otherwise, they'll keep running at EC2 and Amazon will continue to charge you for them!