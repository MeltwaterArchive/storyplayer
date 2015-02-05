---
layout: v2/learn-test-your-platform
title: Test Your Platform
prev: '<a href="../../learn/test-your-code/final-thoughts.html">Prev: Final Thoughts</a>'
next: '<a href="../../learn/worked-examples/index.html">Next: Worked Examples</a>'
---
# Guide To Testing Your Platform With Storyplayer

Storyplayer is commonly used for two types of testing:

* __Component testing__: these tests are designed to thoroughly test a single app or service. They’ll use the app’s documented interfaces, and they’ll also check log files, databases, and anything else where the app makes changes. These stories normally live in the same code repository as the source code for what they are testing. You’ll normally deploy your code into a one-off virtual machine to run this test, to prove that your app or service works as intended.
* __End-to-end tests__: these tests will your platform by automating the actions that your end-users can do. They’ll only use the same APIs and interfaces that your end-users can use, and they’ll never attempt to access anything that your end-user isn’t allowed to access. These stories normally live in their own code repository. You’ll normally run these stories against your test, staging and production platforms, to prove that your product or service works in that environment.

In this guide, you'll learn how to use Storyplayer to test a platform end-to-end.