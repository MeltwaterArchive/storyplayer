---
layout: stories
title: The Eight Phases Of A Story Test
prev: '<a href="../stories/story-test.html">Prev: The Story Test</a>'
next: '<a href="../stories/the-checkpoint.html">Next: The Checkpoint</a>'
---

# The Eight Phases Of A Story Test

## The Life-Cycle Of A Single Test

With Storyplayer, you can automate the complete life-cycle of every single test:

1. [Test environment setup](test-environment-setup-teardown.html): Create virtual machines, deploy the software to test
1. [Test setup](test-setup-teardown.html): Inject test-specific data, start any service mocks that are required, and start any monitoring that is required
1. [Pre-test prediction](pre-test-prediction.html): Look at the test conditions. Is the test likely to succeed?
1. [Pre-test inspection](pre-test-inspection.html): Record the state of the software before we run our test
1. [Action](action.html): Perform the action
1. [Post-test inspection]([post-test-inspection.html): Look at the database, or anything else that should have changed. Did the action succeed?
1. [Test teardown](test-setup-teardown.html): Tidy up after the test, in case we want to re-use the test environment
1. [Test environment teardown](test-environment-setup-teardown.html): Destroy the test environment

At first glance, having to work with eight separate phases of a test can seem a bit daunting, but we've made each of these phases very easy to code, and together they give you fully-automated tests that are very reliable each and every time you run them. Once you're in the habit of testing your stories for each new build of your app before release, your customers and users will really get the benefits of this approach to testing.