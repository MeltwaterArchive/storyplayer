---
layout: top-level
title: Storyplayer
prev: '&nbsp;'
next: '<a href="what-is-storyplayer.html">Next: What Is Storyplayer?</a>'
---

# Storyplayer

Bring your user and service stories to life through your test automation.

## Introduction

[Storyplayer](https://github.com/datasift/storyplayer) is [DataSift](http://datasift.com)'s in-house tool for automating the functional testing of our user and service stories.  We've built it to make it easy to create repeatable end-to-end tests, and to make it just as easy to create repeatable functional tests.

Additionally, Storyplayer can measure non-functional requirements at the same time.

Storyplayer is highly modular, and can be easily extended to support your own custom needs.

### What can you test with Story Player?

 * Back-end services
 * APIs
 * Front end interfaces

## Licensing

[Storyplayer](https://github.com/datasift/storyplayer) is Open Source software.

### Tales, Stories, and Prose

Storyplayer introduces terminology designed to help developers and managers think about testing using high-level concepts before digging into the details of the implementation. The core concept is that of a [User Story](/storyplayer/stories/index.html). Why not talk about tests instead of Stories? Because stories describe not only the results of a test, but also the path taken by the user to get those results.  This metaphor is specially useful when you are testing user-facing front-end modules, which may pass all tests a developer might think of, but still fail the user test.

Take for example a login page that shows two forms: login and registration. Let's assume both have a password field. A programmer might write a test that uses basic authntication with sample credentials and such test would return a positive result while the user might get confused and not know which password field he or she should use.  From the point of view of the user, the login test might fail or pass; it all depends on what the user thinks is the correct choice.  As you can see, User Stories can be used to construct test suites that more realistically mimic actual user behavior and thus allow QA teams test both the functionality of the software and the user experience.

Stories can be organized into collections called Tales.  All Stories are written using Prose&mdash;the way of writing PHP code that makes it not only natural to read, but also to think about Stories.
