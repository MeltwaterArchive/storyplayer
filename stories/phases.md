---
layout: stories
title: The Eight Phases Of A Story
prev: '<a href="../stories/service-stories.html">Prev: Service Stories</a>'
next: '<a href="../stories/the-checkpoint.html">Next: The Checkpoint</a>'
---

# The Eight Phases Of A Story

* Where is the software deployed? (_test environment setup_)
* Has the right test data been injected? (_test setup_)
* Is the test likely to succeed? (_pre-test prediction_)
* What is the state of the software before we run our test? (_pre-test inspection_)
* Did the action succeed? (_post-test inspection_)


Each Story consists of up to 8 phases, in this order:

1. [Test Environment Setup](test-environment-setup-teardown.html) - create the test environment
1. [Test Setup](test-setup-teardown.html) - modify the test environment for this specific test
1. [Pre-Test Prediction](pre-test-prediction.html) - do you expect the test to pass or fail?
1. [Pre-Test Inspection](pre-test-inspection.html) - cache any data in the [Checkpoint](the-checkpoint.html) before the story makes any changes
1. [Action](action.html) - perform the story
1. [Post-Test Inspection](post-test-inspection.html) - did the story achieve the expected results?
1. [Test Teardown](test-setup-teardown.html) - tidy up the test environment
1. [Test Environment Teardown](test-environment-setup-teardown.html) - destroy the test environment