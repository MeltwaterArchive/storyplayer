---
layout: prose
title: Introducing Prose
prev: '<a href="../stories/post-test-inspection.html">Prev: Post-Test Inspection Phase</a>'
next: '<a href="../prose/the-st-object.html">Next: The $st Object</a>'
---

# Introducing Prose

Prose is the grammar and the syntax used to tell [User Stories](/storyplayer/stories/user-stories.html) and [Service Stories](/storyplayer/stories/service-stories.html).

Stories are told using sentences that call the methods and reference the properties of the [$st object](/storyplayer/prose/the-st-object.html). The general syntax used to construct Prose follows the syntax shown below:

    $st->MODULE()->ACTION();

There are two exceptions, the Assertions Module and the Browser Module, which use slightly different syntax:

    //The Assertions Module syntax:

    $st->MODULE($actualData)->COMPARISON($expectedData);

    //The Browser Module syntax:

    $st->MODULE()->ACTION()->SEARCHTERM()

