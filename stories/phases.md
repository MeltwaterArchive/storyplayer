---
layout: stories
title: The 8 Phases Of A Story
prev: '<a href="../stories/service-stories.html">Prev: Service Stories</a>'
next: '<a href="../stories/the-checkpoint.html">Next: The Checkpoint</a>'
---

# The 8 Phases Of A Story

Each Story can be divided into up to eight phases.

Every story starts with an import of the `StoryTeller` library:

    use DataSift\Storyteller\PlayerLib\StoryTeller;

This step is *required*.

Next, come the story details:

    // ========================================================================
    //
    // STORY DETAILS
    //
    // ------------------------------------------------------------------------

    $story = newStoryFor('Twitter Stories')
      ->inGroup('Web Browsing')
        ->called("Can log in using the login form");

The story details are followed by the user role definition:

    $story->addValidRole('loggedout user');
