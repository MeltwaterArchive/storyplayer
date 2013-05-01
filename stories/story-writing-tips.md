---
layout: stories
title: Story-Writing Tips
---

# Story-Writing 

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
