---
layout: v2/learn-test-your-code
title: Test Your Code
prev: '<a href="../../learn/fundamentals/belt-and-braces-testing.html">Prev: Belt and Braces Testing</a>'
next: '<a href="../../learn/test-your-code/why-component-testing.html">Next: Why Component Testing?</a>'
---
# Guide To Testing Your Code With Storyplayer

Storyplayer is commonly used for two types of testing:

* __Component testing__: these tests are designed to thoroughly test a single app or service. They’ll use the app’s documented interfaces, and they’ll also check log files, databases, and anything else where the app makes changes. These stories normally live in the same code repository as the source code for what they are testing. You’ll normally deploy your code into a one-off virtual machine to run this test, to prove that your app or service works as intended.
* __End-to-end tests__: these tests will your platform by automating the actions that your end-users can do. They’ll only use the same APIs and interfaces that your end-users can use, and they’ll never attempt to access anything that your end-user isn’t allowed to access. These stories normally live in their own code repository. You’ll normally run these stories against your test, staging and production platforms, to prove that your product or service works in that environment.

In this guide, you'll learn how to use Storyplayer to test a single app or service.

## In This Guide

This guide contains a step-by-step process to testing a single app or service using Storyplayer:

* [Why Component Testing?](why-component-testing.html)
* [What Are You Testing?](what-are-you-testing.html)
* [Sample Layout For Source Code Repo](sample-layout-for-source-code-repo.html)
* [Defining Your Test Environment](defining-your-test-environment.html)
* [Defining Your System Under Test](defining-your-system-under-test.html)
* [Recommended First Tests](recommended-first-tests.html)
* [Organising Your Tests Into Groups](organising-into-groups.html)
* [Smoke Testing](smoke-testing.html)
* [Integration Testing](integration-testing.html)
* [Final Thoughts](final-thoughts.html)