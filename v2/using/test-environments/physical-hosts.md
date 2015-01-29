---
layout: v2/using-test-environments
title: Creating Test Environments On Physical Hosts
prev: '<a href="../../using/test-environments/ec2.html">Prev: Creating Test Environments On Amazon EC2</a>'
next: '<a href="../../using/test-environments/vagrant.html">Next: Creating Test Environments Using Vagrant</a>'
---

# Creating Test Environments On Physical Hosts

Virtualisation (such as [Amazon EC2](ec2.html) or [Vagrant and Virtualbox](vagrant.html)) is a great way to build useful test environments on demand.  But, if your production environment doesn't use virtualisation at all, then at some point you'll want to run your performance-related stories against software deployed on real hardware.

Storyplayer doesn't have a _PhysicalHost_ module, but you can use the [Hosts Table module](../modules/hoststable/index.html) to tell Storyplayer all about any physical host that you want to use in your tests.

## Planning Your Testing

Testing on real hardware brings its own unique challenges. There's no easy way to reprovision a physical box, and you need to beware of multiple people trying to use the same box at the same time.  You might also run into networking issues that you haven't seen before.  It's easy to overlook security, but doing so can be extremely costly.

* One way around the reprovisioning problem is to setup a [PXE boot environment](http://en.wikipedia.org/wiki/Preboot_Execution_Environment).  PXE boot allows you to boot your physical box from an operating system hosted elsewhere on your network - perfect for re-installing before your next planned testing activity.

  Alternatives include buying a server with [integrated Lights Out (iLO) management](http://en.wikipedia.org/wiki/Integrated_Lights-Out) or a networked [KVM switch](http://en.wikipedia.org/wiki/KVM_switch) with support for remote media.  Both of these allow you to access the server's BIOS / EFI boot screens from the comfort of your own desk, and to do a re-install of your operating system from [virtual media](http://en.wikipedia.org/wiki/Remote_virtual_media) (a DVD image that's mounted over the network).  They also allow you to power the server on and off remotely, which is very handy if your performance test causes the operating system to crash or lock up.

  It's common to combine PXE boot with either iLO or a KVM switch, to avoid having to travel to your data-center just to operate your server.

* If you have multiple people trying to use the same physical host at the same time, it can affect the results of your testing.  It's something that's best avoided.  A simple Google spreadsheet where people book out a server for testing is all that's needed to keep things clear for everyone.

* Any performance-related testing is going to put a strain on your network.  You're going to have Storyplayer trying to saturate the network connection on whatever machine it's running on, plus your app is going to be doing to same.  This isn't just going to stress the individual switch ports that your hardware is plugged into, but it is also going to stress your network switch's internal backplane, and quite possibly the switch's own uplink connection too.

  If your test hardware is sharing a network (say) with your office's desktop computers, not only are your performance figures going to be a little unreliable, but you're probably going to cause a lot of disruption to all the work that everyone else is trying to do.  The same goes if you're running Storyplayer on your desktop computer, as all of that traffic has to cross the office network to get to your dedicated test network.

* One last thing that's easily forgotten in all the planning is security.  Test networks, and the hosts running on them, are often insecure environments that haven't been hardened to the same extent that your production environment has been.  They're also sometimes thrown together in a hurry, in response to an urgent request for testing a new release or investigating a problem that has been discovered by customers.

  It's a really good idea to make sure that your test network is a private network, completely inaccessible from the Internet.  If it *has* to accept inbound traffic from the Internet, then you must put the appropriate firewalls in place.  Limit which IP addresses can connect from the Internet if you can.  If you can't, then you must harden it as if it is your production environment.

  Leave your test network public and insecure, and it's only a matter of time before someone hacks it - and it will be entirely your own fault when they do.

## Where To Run Storyplayer

It's not a good idea to run Storyplayer from your own computer when performance testing, as this could be very disruptive for everyone else in your office.  (See the discussion about networks above).  You wouldn't normally run Storyplayer on the same physical hardware as the software you're testing, as this will skew your final performance figures.  (You should, however, consider running monitoring software such as [SavageD](../modules/savaged/index.html) on your test hardware).

You'll get the most accurate results with the least disruption by running Storyplayer from another computer that is connected to your test network.

## Curate Your Test Hardware

It's incredibly tempting to just have a pile of servers that are all identical, especially if you use the same hardware in production, but that will limit what you can discover about the software you are testing.  Hardware that's different in exaggerated ways will help you stress software in ways that you can't otherwise do, and ultimately it'll help you produce much better software.

Choose your test hardware on the particular strengths and weaknesses of each server.

For example, I keep an old server around that was retired from production years ago, precisely because it has (by modern standards) a very slow disk subsystem.  It's perfect for running any tests where there'll be a lot of disk activity (databases, message queues and the like).  I've got another server that's as fast as I can get, because it will show up multi-threading race conditions that don't (yet) appear anywhere else.  It also shows up CPU cache line stalls better than anything else I have.  Other machines have extra-large drives for loading terabytes of test data, or extra RAM for supporting more concurrent testing.

You'll need to figure out what matters for your app, in order to start collecting the test hardware that will serve your testing the best.

## Preparing Your Physical Hardware

Whatever hardware you decide to run your tests against, the process for getting it ready to use is normally the same.

1. Assign a static IP address, or make sure your host is available via dynamic DNS

   Your tests are going to need a way to find your test hardware on the network.  One way is to allocate a static IP address, and pop that into your computer's `/etc/hosts` file.  Another way is to have a working dynamic DNS setup on your test network.

   Either way, your environment config files will normally contain URLs that use hostnames not IP addresses, so that any HTTP requests work via name-based virtual hosting.  Name-based virtual hosting is the norm for configuring websites, and it doesn't work if you have IP addresses in your URLs.

1. Create a user account for Storyplayer to login as, and assign it a passphrase-less SSH key

   Storyplayer's [Host module](../modules/host/index.html) can log into your test hardware via SSH.  This is handy for starting and stopping system services and monitoring daemons such as [SavageD](https://github.com/datasift/SavageD).  Like all automation tools, Storyplayer needs to use an SSH key that doesn't have a passphrase set.

   You'll add the public part of the key to the `authorized_keys` file on your test hardware, and you'll put the private key on the machine where Storyplayer will run.

## Preparing Your Software For Testing

There isn't much to do for websites - just make sure that everything is deployed, and that you can log in by hand successfully before you start your testing.

If you're testing software that runs as a UNIX daemon, then it's a good idea to make sure that your daemon's startup & shutdown scripts work properly.  That way, you can have Storyplayer restart your daemon before a test run.

The other thing to remember is to keep the copy of Storyplayer on your test network up to date.  It's very easy to forget about your Storyplayer install on your test network, and for it to end up with out-of-date stories, story templates and your own in-house Prose modules.  Get in the habit of making sure everything is updated before each planned testing cycle to avoid wasting time running tests that either fail or which generate results that are no longer useful to you.

## Telling Storyplayer About Your Test Hardware

You can use the [Hosts Table module](../modules/hoststable/index.html) to tell Storyplayer about your test hardware:

```php
$story->addTestEnvironmentSetup(function(Storyteller $st) {
	// set our params
	$st->setParams(array(
		'platform' => 'vagrant-centos6'
	));

	// get the final params
	$params = $st->getParams();

	// what are we working on?
	switch ($params['platform']) {
		case 'vagrant-centos6':
			// ... create a test VM
			break;

		case 'my-test-host':
            // we're going to run this on a physical host
            $hostDetails = new stdClass();
            $hostDetails->name = "my-test-host";
            $hostDetails->ipAddress = "fqdn.host";
            $hostDetails->type = "PhysicalHost";
            $hostDetails->osName = "centos6";
            $hostDetails->sshUsername = 'qa';
            $hostDetails->sshOptions  = array (
                "-i '" . getenv('HOME') . "/.ssh/QA_AWS.pem'"
            );

            $st->usingHostsTable()->removeHost($hostDetails->name);
            $st->usingHostsTable()->addHost($hostDetails->name, $hostDetails);

            break;
	}
});
```

You create the same `hostDetails` object that the [Amazon EC2](../modules/ec2/index.html) and [Vagrant](../modules/vagrant/index.html) modules do internally, only you populate the object with the details of your test hardware instead.

* `$hostDetails->name` is the name that you're going to use to refer to this host in your tests.  This is the same as the `$vmName` parameter when creating EC2 or Vagrant virtual machines.
* `$hostDetails->ipAddress` is the DNS name of your test hardware, or its IP address.
* `$hostDetails->type` should always be `PhysicalHost` when testing against real hardware.
* `$hostDetails->osName` is the name of the operating system that you're running on your test hardware. See [supported guest operating systems](modules/vagrant/supported-guests.html) for details.
* `$hostDetails->sshUsername` is the name of a user account on your test hardware that Storyplayer can log into.  Optional.
* `$hostDetails->sshOptions` is a list of the command-line options to pass to SSH whenever your story needs to SSH into your test hardware.  Required if you've set `$hostDetails->sshUsername`.

Once you have built your `hostDetails` object, you inject it into the [Hosts Table](../modules/hoststable/index.html) to tell Storyplayer that your host exists.  After that, you can use the [Host module](../modules/host/index.html) as normal.

## Running Your Tests

Do your tests use a web browser at all?  One that runs locally (rather than our [SauceLabs](../devices/saucelabs.html) support?)  If so, you'll want to SSH to your Storyplayer machine from a computer with working X11, and you'll want to use X11 Forwarding when you do:

<pre>
ssh -X user@host
</pre>

The web browser is going to run on your Storyplayer machine on your test network.  Chances are, that machine is a server and it doesn't have its own working X11 desktop.  So the web browser is going to need to use your X11 server to work.  (We've tried using Xnest to work around this, but in our testing browsers were prone to crashing).

This will have an impact on your testing:

* browsers will take longer to render pages, and you'll need to adjust any timeouts accordingly
* any pages that refresh themselves rapidly (for example, a built-in stats page in one of your UNIX daemons that you're testing) may be refreshing themselves too quickly for the browser to ever realise that the page has finished loading - causing your test to hang :(