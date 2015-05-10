---
layout: v2/using-configuration
title: Configuring Storyplayer
next: '<a href="../../using/configuration/storyplayer-json.html">Next: The storyplayer.json File</a>'
prev: '<a href="../../using/index.html">Prev: Storyplayer Reference Manual</a>'
updated_for_v2: true
---

# Configuring Storyplayer

Storyplayer's config files are designed to allow you to _write once, re-use anywhere_:

* write a story once
* re-use it to test different versions of your system under test
* re-use it to test against different test environments

## Three Key Files

Your stories require three configuration files:

* [storyplayer.json](storyplayer-json.html) - contains settings for how to run Storyplayer
* A [system-under-test config file](system-under-test-config.html) - contains settings about the software or platform that you are testing
* A [test environment config file](test-environment-config.html) - contains a description of the test environment where your system-under-test is to be tested

## Personal Settings

Some settings, such as your Amazon AWS keys or your Sauce Labs credentials, are associated with the person who runs Storyplayer. It often makes no sense to put these settings into the three key config files.

Storyplayer provides an optional [per-user config file](user-dot-config.html) exactly for this purpose.