---
layout: devices
title: Testing With Different Devices
prev: '<a href="../environments/vagrant.html">Prev: Creating Test Environments Using Vagrant</a>'
next: '<a href="../devices/how-it-works.html">Next: How We Control Web Browsers</a>'
---

# Testing With Different Devices

In today's world, it's very unusual for someone to interact with your website or your web-based app via one single browser running on just one device.  They will probably use multiple web browsers at the office and at home, and they will probably use both a smartphone and a tablet too.  And, increasingly, the very first time they look at your website or web-based app, they'll probably be using a mobile device rather than a desktop or a laptop.

It's never been more important to test different browsers on different devices.

## Supported Browsers And Devices

Storyplayer supports the following browsers and devices:

* [Chrome, Firefox or Safari running locally on a desktop or laptop](localbrowser.html).
* [Internet Explorer, Chrome, Firefox, Safari or Opera running on Windows, Linux, OS X, iOS or Android via Sauce Labs](saucelabs.html).
* [Any arbitrary web browser that supports WebDriver, running remotely](remotewebdriver.html).

We support testing web sites that are running behind your firewalls, and also web sites that are protected via HTTP Basic Auth (as dev & demo websites often are).

## Stuff We Still Need To Add

We've got pretty good support for desktop browsers, but we know that we've got the following stuff still to add:

* Touch gestures in mobile browsers
* Full HAR support for browsermob-proxy (provides network-related information to your tests)

We're adding these as and when we need them.  If you need something before we get to it, or before we've thought of it, pull requests are always welcome.

## Not Supported

Storyplayer's pretty flexible, and very extensible.  You'll probably find ways to make it do things we never dreamed of when we built it.  However, just because you can do it, that doesn't make it a good idea.  Here's a list of things that are possible, but we advise against, and won't help you with:

* testing via headless browsers such as PhantomJs

## Not Possible

We can't support absolutely everything that can be done on the web, because the web contains plenty of things that simply don't support automation.  Here's an incomplete list of the things that Storyplayer can't do (and probably won't ever be able to do):

* deal with CAPTCHAs
* interact with Flash applets
* interact with Java applets
* interact with Silverlight applets
* test anything involving sound