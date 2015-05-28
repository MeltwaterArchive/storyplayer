---
layout: v2/using-stories
title: The Checkpoint
prev: '<a href="../../using/stories/post-test-inspection.html">Prev: Post-Test Inspection Phase</a>'
next: '<a href="../../using/stories/grouping-tests.html">Next: Grouping Tests</a>'
updated_for_v2: true
---

# The Checkpoint

From day one, Storyplayer has been designed for two types of users:

* software developers, and
* software testers

We've always had to strike a balance between people who code all day for a living, and people who can just do enough coding to get by. We wanted to create a tool powerful enough to test every aspect of a world-class platform like DataSift's, without ending up spending more time debugging tests than the system being tested.

Three things came out of this:

* We made test phases stateless.
* We made Storyplayer modules stateless too.
* We created the checkpoint to pass data between test phases.

## Test Phases Are Stateless

Each test phase is a function.  There is no `$this` to save variables to.  And you should __never__ use `$GLOBALS` to get around the lack of `$this`.  (Likewise, each module that you call in your tests is also stateless).

This is a deliberate choice, to minimise time-consuming bugs in your test code caused either by someone writing code that's too clever, or someone writing code that isn't clever enough.  We believe that you want to spend time debugging your app, not debugging your tests.  Making Storyplayer as stateless as possible is a key part of making that possible.

However, there are times when you need to share data between the phases of your test.  For that, there is the checkpoint.

## The Checkpoint Object

The _checkpoint_ is a plain old PHP object that's created at the start of every test.  It starts as an empty object, and it's available for you to store data into, or read data from, in any phase of your test:

{% highlight php startinline %}
$checkpoint = getCheckpoint();
$checkpoint->stats = fromStusCustomModule()->getCurrentUsageStats();
{% endhighlight php %}

You can put anything that you want into it - Storyplayer doesn't care.  Storyplayer's own code never looks inside the checkpoint; as far as Storyplayer is concerned, the checkpoint is your data.

## A Fresh Checkpoint For Every Test

Each test starts with an empty checkpoint. When your test finishes, Storyplayer destroys the checkpoint and its contents. This helps ensure that your tests are more likely to be deterministic - that the tests behave the same way every time they execute.

## When To Use The Checkpoint

The checkpoint is available in any phase of your test, and you're free to use it however you want.  Here's how we use it in our own tests.  We think you'll find yourself using in a similar way.

* In the [`TestSetup()` phase](test-setup-phase.html), we'll store information that the [`Action()`](action.html) and [`PostTestInspection()`](post-test-inspection.html) phases might need, especially to avoid hard-coding the same filenames or loop counts in more than one phase.
* In the [`PreTestInspection()` phase](pre-test-inspection.html), we'll store any data that we want to use in a _before and after_ comparison in the [`PostTestInspection()` phase](post-test-inspection.html).  For example, we might store a customer's current credit balance, to see if it has changed after the [`Action()`](action.html) has run.
* In the [`Action()` phase](action.html), we'll store any results that need checking in the [`PostTestInspection()` phase](post-test-inspection.html). For example, this might be an invoice number after successfully testing a shop's checkout workflow.