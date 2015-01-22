---
layout: v2/using-stories
title: The Checkpoint
prev: '<a href="../stories/phases.html">Prev: The Eight Phases Of A Story Test</a>'
next: '<a href="../stories/the-environment.html">Next: The Environment</a>'
---

# The Checkpoint

From day one, Storyplayer has been designed for two types of users:

* software developers, and
* software testers

We've always had to strike a balance between people who code all day for a living, and people who can just do enough coding to get by. This design constraint has produced three key results: the checkpoint (covered in this chapter), the [Prose](../prose/index.html) style, and the [$st module loader](../prose/the-st-object.html).

## Story Phases Are Stateless

Each story phase is a function.  There is no `$this` to save variables to.  And you should __never__ use `$GLOBALS` to get around the lack of `$this`.  (Likewise, each module that you call in your story tests is also stateless).

This is a deliberate choice, to minimise time-consuming bugs in your test code caused either by someone writing code that's too clever, or someone writing code that isn't clever enough.  We believe that you want to spend time debugging your app, not debugging your tests.  Making Storyplayer as stateless as possible is a key part of making that possible.

However, there are times when you need to share data between the phases of your story.  For that, there is the checkpoint.

## The Checkpoint Object

The _checkpoint_ is a plain old PHP object that's created at the start of every story.  It starts as an empty object, and it's available for you to store data into, or read data from, in any phase of your story:

{% highlight php %}
$checkpoint = $st->getCheckpoint();
$checkpoint->stats = $st->fromStusCustomModule()->getCurrentUsageStats();
{% endhighlight php %}

You can put anything that you want into it - Storyplayer doesn't care.  Storyplayer's own code never looks inside the checkpoint; as far as Storyplayer is concerned, the checkpoint is your data.

## The Checkpoint Is Not Persistent

When your story test finishes, Storyplayer destroys the checkpoint object.  _The checkpoint is not persistent between test runs._ You're always guaranteed to start with an empty checkpoint at the start of your test.  This helps ensure that your tests are more likely to be deterministic - that the tests behave the same way every time they execute.

## When To Use The Checkpoint

The checkpoint is available in any phase of your story, and you're free to use it however you want.  Here's how we use it in our own tests.  We think you'll find yourself using in a similar way.

In the [test setup phase](test-setup-phase.html), we'll store information that the [action](action.html) and [post-test inspection](post-test-inspection.html) phases might need, especially to avoid hard-coding the same filenames or loop counts in more than one phase.

In the [pre-test inspection phase](pre-test-inspection.html), we'll store any data that we want to use in a _before and after_ comparison in the [post-test inspection phase](post-test-inspection.html).  For example, we might store a customer's current credit balance, to see if it has changed after the [action](action.html) has run.