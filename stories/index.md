---
layout: stories
title: Introducing Stories
prev: '<a href="../what-is-storyplayer.html">Prev: What Is Storyplayer?</a>'
next: '<a href="../stories/user-stories.html">Next: User Stories</a>'
---

# Introducing Stories

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

You can find a longer discussion of [user stories](http://en.wikipedia.org/wiki/User_story) elsewhere on the web. 
