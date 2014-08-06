---
layout: v2/environments
title: Test Environments
prev: '<a href="../prose/using-classes.html">Prev: Using Classes</a>'
next: '<a href="../environments/your-machine.html">Next: Testing On Your Machine</a>'
---

# Test Environments

No matter where you need to test - on your local machine, against a virtual machine, against a dedicated test environment or against your production environment - you can do so with Storyplayer.

Testing against your dev box is the quickest way to work on your code, whilst testing against representative and reproducible test environments is the most reliable way to re-use your automated tests.  Finally, testing against production helps you prove that your customers really can use your new features once they've been shipped.

## Testing In Development

Most of us prefer to develop code on the desktop or laptop that we're sat in front of.  It allows us to use our full set of chosen development editors / IDEs and tools, and to make the _code -> test -> fix_ cycle of development as quick as it possibly can be.

It's always a good idea to make sure that the code you're working on actually works before you commit it to source control and push it to be shared with your colleagues.  You need to make sure both that the new code works, and that you haven't broken any existing functionality with your changes.  Experienced developers are used to doing this with unit tests, but their role isn't to prove that all your stories work.

Storyplayer is designed to [run tests against your local computer](your-machine.html) by default.

## Representative Environments

Your software should be tested in an environment that's as close to your production environment as possible:

* Same operating system
* Same supporting software - web server, database server, system libraries
* Same runtime engines - scripting engines, JDK version

"Close enough" is only good enough for the most trivial and simplistic of apps.  Why?  Your app interacts with, and relies on, this environment.  That's millions of lines of code, with bugs of its own, and its own unique behaviour.  Substitute any of it for something else, and you run the risk of your app working in test but not working in production.

If your app is deployed across multiple servers, then the right parts of your app must be co-located on the right test boxes, to take into account:

* network traffic between each box
* cpu usage on each box
* disk I/O bottlenecks on each box

If you test all of your app's parts on a single box, you might miss that your app seems to go fast enough because it doesn't have to communicate over the network (which is much slower).  If you split things up in a different way to production, you might miss that you've actually got two disk I/O-heavy parts on the same box in production.

Make your test environments look like the production environment.

Storyplayer comes with built-in support for testing against as few or as many servers as you need for your application.  You can run the same test against any dev/test environment [running on your own machine](local-vms.html), [in your datacentre](dedicated.html), or even [against your production environment](production.html).

## Reproducible Test Environments

Your software should be tested in an environment that you can destroy and re-create on demand.  By running your tests against an environment that is in an identical state each and every time, you're one step closer to making your tests _reproducible_.

There'll always be a temptation to make manual changes to your test environment to get things working, especially when there's a deadline looming.  The problem with this is that you'll probably forget about the change you made, and no-one else will ever know about it.  That is, until they try and run the tests for themselves in their own test environment - where the tests will fail.  The last thing anyone wants is to waste time tracking down a problem that has been caused by a manual change you've made to your test environment.

Automating your test environments does require an initial investment in time.  Even using the latest tools such as Vagrant and Ansible, it's a job that can take days not hours.  But once it's done, you'll quickly get all that time back - and more - because you can now spin up a test environment whenever you need one.  And if you're working in a team, everyone else will benefit immediately from your automation efforts.

Storyplayer comes with built-in support for [creating and destroying test environments every time you run a story](../stories/test-environment-setup-teardown.html).

## Virtual Machines, Or Hardware?

Should you test on physical hardware, or are virtual machines good enough?

There are many advantages to using virtual machines to test on.  They're fast enough for most testing.  You can run them directly on your dev box.  If you've got a laptop, you can run them on your laptop, which is great if you're working out of the office.  You can create and destroy them whenever you want, and it won't affect anyone else.  There are some great tools out there to help you work with virtual machines.  All you need is enough RAM and disk space, and some spare CPU.

And, it has to be said, most apps simply aren't busy enough to need anything else.

Once your business starts to scale, and your app becomes larger than a single entity, then testing on physical hardware becomes more important.  The hardware - your server choice, your network topology, your switches, your gateways - becomes part of your app, because it becomes part of how your app works at scale.  Here, testing inside a virtual machine doesn't test all of your app any more, and won't catch all of reasons why a test will fail.

When we got to this point, we adopted a three-way testing strategy:

* virtual machines for the functional tests during development - making sure our stories worked at all
* dedicated test hardware for the non-functional tests - making sure our stories worked at scale
* testing directly against production environment - making sure our code worked after it had shipped

With this approach, anyone can still run tests at any time inside virtual machines - which is good, because you don't want your developers to have excuses not to run tests.  And, our quality assurance folks can run larger, more demanding tests in parallel to catch any problems before new builds go out to production.

Storyplayer supports virtual machines created by [Vagrant](vagrant.html) and [Amazon EC2](ec2.html).  When you need to test against [physical hosts](physical-hosts.html), that's very easy to do too.  [You can even run the same test against either](multiple-environments.html) - we do it all the time.

