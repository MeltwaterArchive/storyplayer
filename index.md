---
layout: top-level
title: Storyplayer
prev: '&nbsp;'
next: '<a href="what-is-storyplayer.html">Next: What Is Storyplayer?</a>'
---

# Storyplayer

Bring your user and service stories to life through your test automation.

## Introduction

[Storyplayer](https://github.com/datasift/storyplayer) is [DataSift's](http://datasift.com) in-house tool for automating the functional testing of our user and service stories.  We've built it to make it easy to create repeatable end-to-end tests, and to make it just as easy to create repeatable functional tests.

Additionally, Storyplayer can measure non-functional requirements at the same time.

Storyplayer is highly modular, and can be easily extended to support your own custom needs.

### What can you test with Story Player?

 * Back-end services
 * APIs
 * Front end interfaces

## Licensing

[Storyplayer](https://github.com/datasift/storyplayer) is Open Source software.

### Tales, Stories, and Prose

Storyplayer introduces terminology designed to help developers and managers think about testing using high-level concepts before digging into the details of the implementation. The core concept is that of a [User Story](/storyplayer/stories/index.html).

### Telling a Story in Eight Parts

Each Story can be divided into up to eight parts.

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

### Testing a Backend

### Testing an API

### Testing Web UI

### Telling Tales

### F.A.Q

1. The browser window closes immediately after the test completes. How can I make it stay on screen?

Add `exit(0);` near the end of the callback function defined in a call the `addAction()` in the [Action Phase](/storyplayer/stories/action.html).

    $story->addAction(function(StoryTeller $st) {

            // get the checkpoint, to store data in
            $checkpoint = $st->getCheckpoint();

            $st->usingBrowser()->gotoPage("https://twitter.com");

            // get the title of the test page
            $checkpoint->title = $st->fromBrowser()->getTitle();

            exit(0);
    });
