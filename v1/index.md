---
layout: v1/top-level
title: Storyplayer
prev: '&nbsp;'
next: '<a href="installation.html">Next: Installing Storyplayer</a>'
---

# Storyplayer

Bring your user and service stories to life through your test automation.

## Introduction

[Storyplayer](https://github.com/datasift/storyplayer) is [DataSift](http://datasift.com)'s in-house tool for automating the testing of our [user and service stories](stories/index.html).  Storyplayer makes it easy to test both your front-end website and your back-office services.  Storyplayer can test both functional and non-functional requirements.  It sits between unit testing tools such as PHPUnit (used by your developers) and acceptance testing tools such as Behat (used by your product team).

Written in PHP, Storyplayer is highly [modular](modules/index.html), and can be easily extended to support your own custom needs.

{% include presentations/storyplayer-20130502.html %}

## What Can You Test With Storyplayer?

Storyplayer was initially designed and built to test DataSift's real-time firehose-filtering product.  This is a service-oriented architecture consisting of:

* Data piplines (using [ZeroMQ](modules/zeromq/index.html) and [HTTP](modules/http/index.html))
* Supporting services (using HTTP and sometimes ZeroMQ)
* Internal and public-facing APIs (using JSON over HTTP)
* Front end interfaces (using [HTML](modules/browser/index.html))

Storyplayer can test software written in any language, because Storyplayer is designed to interact with your software just like a user would.

## Built For Developers And Testers Alike

From the very beginning, Storyplayer has been designed as a testing tool for software engineers (who want to write code using the language they already know, not learn yet another DSL) and testers (who want a toolkit that allows them to focus on creating sophisticated tests with the minimum of code).

## User And Service Stories

The skeleton of a strong development process is the [story](stories/index.html).

* Each [user story](stories/user-stories.html) is a simple description of one feature or benefit that your product or service provides.
* They are written in plain English, and they include clear acceptance tests and a place to record the 'why' behind anything that your product or service provides.
* They can be shared between your product teams, your project management, your architects, your engineers and your testers.
* [Service stories](stories/service-stories.html) are exactly like user stories, but for internal services and APIs.

Storyplayer is designed from the outset to automate the testing of all of your stories - to fill that gap between unit testing and product acceptance testing.

## Prose

All stories are written in the PHP that you already know using a style that we call [Prose](prose/index.html) (so-called because we like the whole story telling theme).  Prose is a way of writing PHP code that makes it not only natural to read, but also to think about stories.  There's no DSL to learn!

At the heart of Prose is the [$st dynamic module loader](prose/the-st-object.html), which makes it hard _not_ to share code between your tests.  We've already published [over 15 re-usable modules](modules/index.html) - __you can get started with your testing right away__ - and it's very easy to [create your own Prose modules](prose/creating-prose-modules.html) when you need to do something that we haven't already covered.

## Test Environments

For those larger and more complicated apps (like the DataSift platform), Storyplayer can provision and destroy whole [test environments](environments/index.html) to run your tests against.  Today, Storyplayer ships with fully-working support for [Vagrant](environments/vagrant.html) and [EC2](environments/ec2.html).

## Test Devices

Storyplayer works with a large variety of [web browser and platform combinations](devices/index.html).  You can test using the [web browsers running on your desktop or laptop](devices/localbrowsers.html).  We've integrated with [SauceLabs](devices/saucelabs.html) for automated cross-browser testing of your apps.  And, for advanced users, we support [remote WebDriver instances](devices/remotewebdriver.html) too.

## Documentation

You're reading the front-page of Storyplayer's online manual, over 65,000 words covering:

* [installation](installation.html) and [configuration](configuration/index.html) of Storyplayer
* what [stories](stories/index.html) are, and their [test phases](stories/phases.html)
* how to [write tests for your stories](prose/index.html), and how to [create your own Storyplayer modules](prose/creating-prose-modules.html)
* how to [create test environments](environments/index.html) to test your apps in
* a [comprehensive reference to every module that ships with Storyplayer](modules/index.html)

## Licensing

Storyplayer is [open source software](http://datasift.github.io/storyplayer/copyright.html#license).

## Source Code And Issues

The [source code for Storyplayer](https://github.com/datasift/storyplayer) is available from GitHub, and [bug reports / feature requests](https://github.com/datasift/storyplayer/issues?state=open) and [pull requests](https://github.com/datasift/storyplayer/pulls) are all very welcome.