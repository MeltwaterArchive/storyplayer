---
layout: v2/using-devices
title: How To Test With Browsers And Devices
prev: '<a href="../../using/devices/remotewebdriver.html">Prev: Testing Unusual Browsers Using The Remote WebDriver</a>'
next: '<a href="../../using/stories/index.html">Next: Introducing Stories</a>'
---

# How To Test With Browsers And Devices

When developing tests for different devices, following these steps will help you get your tests up and running in the shortest possible time.

## Get Your Environments Sorted

The first thing to do is to identity which environments you are going to be running tests against, and add them to your Storyplayer config files.

Here at DataSift, we support running tests against all of the following environments:

* development environments
* dedicated QA environments
* staging environment
* production environment

Build your own list of environments to suit your organisation or project.

## Pick One Environment, Then Adapt

When we write a new test, we get it working in one environment first.  We only run it against other environments once we're happy that it works repeatably against that first environment.

There's a few things we sometimes have to adjust when we start running a new test against the other environments:

* Anything that's been hard-coded into the test, but which is different in different environments, needs turning into a config file entry.  It's normally URLs that are the problem here.
* Timeouts on page loads often need adjusting.  Some of our environments are virtual, whilst others run on dedicated hardware, and as a result, they perform differently.  (This is something we're going to look at in a future release; I'm sure we can make this much easier than it currently is).
* Different environments run different versions of the code, and have different functionality. You've got to be careful here; the more `if` statements in your tests, the more fragile your tests become.  You're better off maintaining different tests for different versions of your app.
* Some tests are too dangerous to run in production, especially when attempting to test superadmin functionality.  You can use Storyplayer's support for [safeguarding environments](../environments/safeguarding.html) to help with this.

## Pick One Browser And Device, Then Adapt

When we write a new test, we get it working on one browser/device combination first.  For us, that's normally Chrome running locally on our development desktops or laptops.  That gives us the fastest development cycle when writing a new test.

We only start testing with other browsers and devices once we're happy that the test is repeatable and reliable with our primary browser/device combination.

## Write Your Tests To Follow The User's Journey

You can take your website or web-based application, and draw a simple graph showing all of the major paths through it.

* For a website, you'll have landing pages that your marketing leads new users to; your copywriting on these pages will then direct your visitors through a structured discovery of your site.
* For a web-based app, a user will normally have to register or login before he can access many of your web pages.  Some pages will be part of 'wizard'-like processes.  Some pages will only be available to some of your users (e.g. premium users).

These are your user journeys, and they represent how you designed your website or web-based app to be used.  Pick a user journey, and write a series of tests for it.  Then pick another journey, and write those tests.

You can create [tales](../stories/tales.html) to string sequences of tests together in order.  Each tale will be a single user journey.

As you build up your library of tests, you'll start to find that some of your journeys can re-use tests you've already written (especially those that involved registration or logging in).

## Write Your Tests To Follow User Activity

User journeys are important, but your users will always find their own weird and wonderful ways to use your website or web-based app, ways that you did not anticipate up front.  Use your web site analytics (your website / web-based app _has_ proper analytics, right?) to identify any additional ways that your users are using your site, and create tales for those too.

## Test For Robustness

A slick website / web-based app doesn't just work well, but it also handles failures well.  Preventing your users from doing things that you don't want them to do is very important, but if you don't write tests for them, do you know that you're on top of this?

Write tests that post garbage or dangerous data into your web forms.  Write tests that mess about with any parameters in the URLs of your web pages.  Write tests that try to do operations that users don't have privileges to do.  Think of ways to break your site.  Test them.

## Test For Regressions

No test suite is perfect, and your users are going to report bugs to you.  Shipping a bug once is regrettable, but it happens to us all.  Fixing a bug, and then shipping the bug again at some point in the future ... that's when it becomes outright incompetence.

Write tests for the bugs your users report, and add them to the list of tests that you run before each new release.