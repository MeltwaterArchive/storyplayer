---
layout: v2/using-stories
title: Test Phases
prev: '<a href="../../using/stories/tests.html">Prev: The test</a>'
next: '<a href="../../using/stories/the-checkpoint.html">Next: The Checkpoint</a>'
updated_for_v2: true
---

# Test Phases

## What Are Test Phases?

We believe that a test is only as good as its design. To help you design better tests, we've split up a test into distinct activities, called _phases_.

Each phase is a separate anonymous PHP function that you write. You'll find examples of each phase in the following pages.

## The Life-Cycle Of A Single Test

With Storyplayer, you can automate the complete life-cycle of every single test:

1. [Test can run check](test-can-run-check.html): Examine the system under test, test environment and test data to see if this test should run or should be skipped.
1. [Test setup](test-setup-teardown.html): Inject test-specific data, start any service mocks that are required, and start any monitoring that is required.
1. [Pre-test prediction](pre-test-prediction.html): Look at the test conditions. Is the test likely to succeed?
1. [Pre-test inspection](pre-test-inspection.html): Record the state of the software before we run our test.
1. [Action](action.html): Perform the action.
1. [Post-test inspection]([post-test-inspection.html): Look at the database, or anything else that should have changed. Did the action succeed?
1. [Test teardown](test-setup-teardown.html): Tidy up after the test, in case we want to re-use the test environment.

All phases are optional - you only code what you need for your test.