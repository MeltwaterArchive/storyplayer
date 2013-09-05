---
layout: environments
title: Test Environments
prev: '<a href="../prose/using-classes.html">Prev: Using Classes</a>'
next: '<a href="../environments/your-machine.html">Next: Testing On Your Machine</a>'
---

# Test Environments

Storyplayer can take away the pain of testing complex software by creating new test environments on demand.

## Why Are Test Environments Important?

Whenever you are testing complex software, the environment you're using to test it in has a big impact on whether or not your tests are _reproducible_.  If you can re-create the test environment every time that you run your tests, you're much more likely to get the same results each time.

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

## A Discussion About Hardware And Virtual Machines

Should you test on physical hardware, or are virtual machines good enough?

There are many advantages to using virtual machines to test on.  They're fast enough for most testing.  You can run them directly on your dev box.  If you've got a laptop, you can run them on your laptop, which is great if you're working out of the office.  You can create and destroy them whenever you want, and it won't affect anyone else.  There are some great tools out there to help you work with virtual machines.  All you need is enough RAM and disk space.

And, it has to be said, most apps simply aren't busy enough to need anything else.

Once your business starts to scale, then testing on physical hardware becomes more important.  The hardware - your server choice, your network topology, your switches, your gateways - becomes part of your app, because it becomes part of how your app works at scale.  Here, testing inside a virtual machine doesn't test all of your app any more, and won't catch all of reasons why a test will fail.

When we got to this point, we adopted a dual testing strategy:

* virtual machines for the functional tests - making sure our stories worked at all
* dedicated test hardware for the non-functional tests - making sure our stories worked at scale

With this approach, anyone can still run tests at any time inside virtual machines - which is good, because you don't want your developers to have excuses not to run tests.  And, our quality assurance folks can run larger, more demanding tests in parallel to catch any problems before new builds go out to production.