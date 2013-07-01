---
layout: stories
title: The Eight Phases Of A Story Test
prev: '<a href="../stories/story-test.html">Prev: The Story Test</a>'
next: '<a href="../stories/the-checkpoint.html">Next: The Checkpoint</a>'
---

# The Eight Phases Of A Story Test

## The Life-Cycle Of A Single Test

With Storyplayer, you can automate the complete life-cycle of every single test:

1. Create virtual machines, deploy the software to test \([test environment setup](test-environment-setup-teardown.html)\)
1. Inject test-specific data, start any service mocks that are required, and start any monitoring that is required \([test setup](test-setup-teardown.html)\)
1. Look at the test conditions. Is the test likely to succeed? \([pre-test prediction](pre-test-prediction.html)\)
1. Record the state of the software before we run our test \([pre-test inspection](pre-test-inspection.html)\)
1. Perform the action \([action](action.html)\)
1. Look at the database, or anything else that should have changed. Did the action succeed? \(post-test inspection\)
1. Tidy up after the test, in case we want to re-use the test environment \([test teardown](test-setup-teardown.html)\)
1. Destroy the test environment \([test environment teardown](test-environment-setup-teardown.html)\)

At first glance, having to work with eight separate phases of a test can seem a bit daunting, but we've made each of these phases very easy to code, and together they give you fully-automated tests that are very reliable each and every time you run them. Once you're in the habit of testing your stories for each new build of your app before release, your customers and users will really get the benefits of this approach to testing.