---
layout: stories
title: Story-Writing Tips
prev: '<a href="../stories/soa-example.html">Prev: Example: Testing A Service</a>'
next: '<a href="../stories/tales.html">Next: Tales</a>'
---

# Story-Writing 

### The browser window closes immediately after the test completes. How can prevent it?

Add `exit(0);` near the end of the callback function defined in a call the `addAction()` in the [Action Phase](/storyplayer/stories/action.html).

    $story->addAction(function(StoryTeller $st) {

            // get the checkpoint, to store data in
            $checkpoint = $st->getCheckpoint();

            $st->usingBrowser()->gotoPage("https://twitter.com");

            // get the title of the test page
            $checkpoint->title = $st->fromBrowser()->getTitle();

            exit(0);
    });

### Use configuration files to store credentials

Create a directory called `config` and inside that direcotry store your story configuration file.

    {
        "environments": {
            "example": {
                "twitter": {
                    "username":"your_username",
                    "password":"your_password"
                }
            }
        }
    }

### How do I do a test with a login captcha?

Use `$st->usingBrowser()->waitForTitle(60, "Insert actual title here");` and set the timeout to 60 seconds.  Your test will pause for 60 second giving you time to respond to the captcha challenge.
