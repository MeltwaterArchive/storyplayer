---
layout: v1/prose
title: The $st Object
prev: '<a href="../prose/index.html">Prev: Introducing Prose</a>'
next: '<a href="../prose/st-helper-methods.html">Next: $st Helper Methods</a>'
---

# The $st Object

The `$st` object (st stands for Storyteller) is the core object used in Storyplayer Story scripts. It provides the methods and the properties necessary to tell Stories.

## Module Loading

The `$st` object's main purpose is to act as [a dynamic module loader for stories](module-loading.html).  We wanted stories to be as simple as possible to write, and a big part of that is completely taking away the pain of calling reusable Prose modules.  There's no need to explicitly import modules before they are used.  The `$st` object [searches a list of PHP namespaces](module-namespaces.html) for the right class to load for you.

It also helps anyone who wants to [write their own modules](creating-prose-modules.html).  Any Prose module can use the `$st` object to call other modules, making it easy to reuse everything that Storyplayer can already do.

The one downside of this approach is that it defeats autocompletion support in most IDEs and editors.  We know that autocompletion is very important to many developers, but in the end we decided that the benefits of dynamically loading modules, and being able to drop in your own modules to extend Storyplayer, were just too important.

## Helpers

The other thing that the `$st` object does is provide [a set of useful helper methods](st-helper-methods.html), for you to use in your stories, story templates, and modules.  You don't have to worry about require()ing any files; these helpers are available everywhere that the `$st` object is available.