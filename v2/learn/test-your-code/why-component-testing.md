---
layout: v2/learn-test-your-code
title: Why Component Testing?
prev: '<a href="../../learn/test-your-code/index.html">Prev: Test Your Code</a>'
next: '<a href="../../learn/test-your-code/what-are-you-testing.html">Next: What Are You Testing?</a>'
updated_for_v2: true
---
# Why Component Testing?

## The Largest Gap In Testing Today

_Why do you need to write tests for your individual components? If you have unit tests and end-to-end tests, surely component tests are an expensive luxury?_

Check out any project from GitHub. Join any firm and look at the tests they have in their private source code repositories. If there are any tests at all, you'll probably find some unit tests (hopefully a lot of them!), and you might find some BDD-based acceptance tests too. But in between? When was the last time you came across software that included a comprehensive functional test suite that ran against the deployed software?

The biggest gap in software testing today is _component testing_: repeatable tests for a single app or service on a platform.

## Component Tests Bridge The Gap

Let's look at the two most common types of tests that are performed in software development today.

* __Unit tests__ operate at the class / function level, and prove that the written code executes as intended. They're great for libraries of code, but as you move away from isolated pieces of functionality, it becomes harder to create and maintain unit tests that verify the quality of the code.

* __End-to-end__ tests operate at the end-user level, and prove that your users can perform the actions that they are expecting. They're especially great for testing platforms, but as it isn't safe for these tests to take over a platform and start editing database records etc directly, there are limits to the test conditions that they can reproduce.

__Component tests__ sit in between these two layers of testing.  They perform actions like end-users do, so they can exercise those parts of your apps much easier (and cheaper) than unit testing can. And, because they are run against on-demand test environments, they can safely edit database records or perform any other setup steps to reproduce all of your test conditions.

Component tests cannot replace unit tests for code libraries. Component tests need an interface (web, command-line, network API) to interact with. They can't replace end-to-end tests for platforms. A platform typically has a number of components working together.

## Component Tests Catch Faults Earlier

If you think about software development as a pipeline:

    Requirements > Develop > Test > Deploy > Used By Customers

then both unit tests and component tests happen during the `Develop` stage of the development pipeline. Together, their purpose is to catch as many avoidable bugs as possible before the component is handed down to the Test / QA team.

The earlier that faults are detected, the cheaper they are to fix.  The more bugs you find and fix before the code ships, the less time you spend later on doing rework instead of working on the next new set of requirements or features.

## Component Tests Use Repeatable Test Environments

In component testing, we use Storyplayer to create and destroy test environments for each test run. These test environments are normally virtual machines that run on your desktop or laptop. They're re-created using automated build instructions of some kind (whether a simple shell script, or an orchestration solution such as Ansible, Chef or Puppet) to ensure that they are reproducible.

Everyone who is working on the component can run the tests at any time.  Each test environment is completely isolated from all other test environments.  Two or more people running the tests on different desktop or laptop computers aren't going to block each other's work or tests.

Contrast this with platform testing. In platform testing, we point Storyplayer at a platform that isn't managed by Storyplayer. This platform might be called _qa_, _testing_, _staging_, _pre-production_ or even _production_.  These platforms aren't built for each test run. They're normally long-lived.  And they're normally being used for multiple purposes, including end-to-end testing using Storyplayer.  In the case of _production_, these platforms also have end-users on them, and they store commercially-important data that your tests simply can't be allowed to edit or delete.

A platform can be re-created if you're using tools like Ansible, Chef or Puppet - and if you have very good backups of your databases too! But they're not repeatable in the way that test environments for components are.

## Component Tests Can Perform Deep Inspection

Component tests can do things that aren't safe - or often even possible - on platforms.

* Component tests can log into machines in the test environment to check log files, database records and even check for files stored on disk. These are important checks to do in your `PostTestInspection` phase to ensure that your component is working correctly.

* Component tests can also truncate log files - something that's simply not safe to do on a platform. Truncating a log file at the start of a test is a great way to see exactly what has happened on a server during a test.

* Component tests can delete database records, truncate databases, or even upload new databases to create the necessary pre-conditions for a test. None of these actions are safe to perform on a platform.

_Deep inspection is a unique opportunity that only component tests can do._ Unit tests can't do these checks, as they rarely interact with real web servers, database servers and the like. End-to-end tests can't do these checks, as they're running against platforms where these checks aren't safe or otherwise permitted.

## Component Tests Validate Your Contract

The single biggest reason to perform component testing isn't technical. Component tests validate your contract between the component and anything that will ever be integrated with it.

* You can deploy with confidence, knowing that your component really does do what you've said that it does. You've got repeatable evidence that your component holds up its end of any deal.

* Anyone integrating with your component can do so with confidence. Their work will have fewer surprises and disruptions because your component has gaps or inaccuracies.

And, finally but perhaps most importantly of all, you can deploy new versions of your component knowing that you're still delivering on the interfaces and functionality that other developers have already integrated against.