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

## Installing Storyplayer

### Prerequisites

Clone the [Storyplayer repository](https://github.com/datasift/storyplayer).

Install the following software packages:

* [PHP](http://php.net) 5.3.10
* [Python](http://python.org) 2.7.3 (or later)
* `pip install netifaces`
* (On Ubuntu, other systems will use differetn installation tools): `sudo apt-get install php5-curl`
* [Phinx](http://phix-project.org)
* `phing pear-package`
* `phing install-vendor`

### Tales, Stories, and Prose

Storyplayer introduces terminology designed to help developers and managers think about testing using high-level concepts before digging into the details of the implementation.

### What's a Story?

A story is a collection of steps that have to be completed in order to perform a higher-level action.  For example, registering with a web site is a Story that can be broken into a series of the following steps:

 * send the home page to the client
 * respond to a click on the Register link
 * display the registration form
 * respond to a click on the Register button
 * verify that the user has accepted the Term and Conditions of Use of the site
 * validate user data
 * add user data to the user database
 * send confirmation email
 * display a conformation page with a message asking the user to check their email and click on the verification link
 * activate user account
 * send account activation confirmation email
 * possibly more steps

A Story captures all of those steps in a short sentence, which greatly improves communication between key project members.

In case of user registration, you could have

 * UserRegistersAndVerifiesAccount
 * UserRegistersButDoesNotVerifyAccount
 * NonWelcomeUsersCannotRegister

When the level of abstraction provided by Stories is not abstract enough, you can group them into Tales. For example, after users register on your site they will start using it, which is usually told using at least the following three Stories:

 1. log in (a UserCanLogIn Story)
 2. post messages (a UserCanUpdateStatus Story)
 3. log out (a UserCanLogOut Story)

Most users follow this pattern to get value from the site and to contribute to the community, which could be told as a GoodUser Tale.  Some users will follow a different path, a SpammingUser Tale:

 1. log in (a UserCanLogIn Story)
 2. post messages (a UserCanUpdateStatus Story)
 3. violate the site's T&Cs (a UserSpams Story)
 4. get banned (a UserIsBanned Story)
 5. log out (a UserCanLogOut Story)

Stories are written in PHP using Storyplayer-specific Prose (grammar and vocabulary).  Each story combines two types of dialects:

 * Global -- generic Prose provided by Story player
 * Local -- Prose written by you, specific to your site, project

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
