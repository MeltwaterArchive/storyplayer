---
layout: v2/using-deprecated
title: Deprecated Features
prev: '<a href="../../using/configuration/browsermob-proxy.html">Prev: browsermob-proxy Configuration</a>'
next: '<a href="../../using/deprecated/appSettings.html">Next: appSettings</a>'
updated_for_v2: true
---
# Deprecated Features

This section of the manual lists all of the Storyplayer features that we have deprecated.

Each deprecated feature clearly states:

* what has been deprecated
* why it has been deprecated
* how to migrate your stories

<div class="callout warning" markdown="1">
#### When Will These Features Be Removed?

All deprecated features will be removed in Storyplayer v3.
</div>

## What Are Deprecated Features?

Deprecated features are parts of Storyplayer that we wish to change or remove one day.  We do not want to remove them now, as that will break existing stories.

So we are listing them here (and in the [ChangeLog](../../changelog.html)) to warn you in advance, and to give you plenty of time to update your stories to the new approach.

## Our Deprecation Policy

When you upgrade your copy of Storyplayer, you expect your existing tests to continue to work. It's our policy that stories written for Storyplayer v2.0.0 should work _without modification_ for all Storyplayer v2.x releases.

There are a few exceptional cases where we will break backwards-compatibility if we have to:

* anything that does not work because of a bug, or that only works because of a bug that needs fixing
* any old SPv1 functionality that is rarely used outside of DataSift