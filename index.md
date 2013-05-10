---
layout: top-level
title: Storyplayer
prev: '&nbsp;'
next: '<a href="installation.html">Next: Installing Storyplayer</a>'
---

# Storyplayer

Bring your user and service stories to life through your test automation.

## Introduction

[Storyplayer](https://github.com/datasift/storyplayer) is [DataSift](http://datasift.com)'s in-house tool for automating the functional testing of our user and service stories.  We've built it to make it easy to create repeatable end-to-end tests, and to make it just as easy to create repeatable functional tests.

Additionally, Storyplayer can measure non-functional requirements at the same time.

Storyplayer is highly modular, and can be easily extended to support your own custom needs.

### What Can You Test With Storyplayer?

Storyplayer was initially designed and built to test DataSift's real-time filtering product.  This is a service-oriented architecture consisting of:

* Data pipline (using ZeroMQ and HTTP)
* Supporting services (using HTTP and sometimes ZeroMQ)
* Internal and public-facing APIs (using JSON over HTTP)
* Front end interfaces (using HTML)

### Stories And Prose

Storyplayer introduces terminology designed to help developers and managers think about testing using high-level concepts before digging into the details of the implementation. The core concept is that of a [User Story](/storyplayer/stories/index.html).

All Stories are written using [Prose](/storyplayer/prose/index.html)&mdash;a way of writing PHP code that makes it not only natural to read, but also to think about Stories.

## Source Code

The source code for Storyplayer [is available from GitHub](https://github.com/datasift/storyplayer).

## Current Status

* __Code:__ recently open-sourced, bound to be a few rough edges at first
* __Docs:__ most modules are documented, tutorial section to come in the next couple of weeks
* __Examples:__ we'll be building up its self-test suite over the next couple of weeks

## Licensing

[Storyplayer](https://github.com/datasift/storyplayer) is [open source software](http://datasift.github.io/storyplayer/copyright.html#license).
