---
layout: v2/modules
title: Internal Modules
---
# Internal Modules

## Introduction

We've built Storyplayer to be highly modular. The modules that you can call from your stories - and Storyplayer's runtime itself - use Storyplayer modules too. These are the _internal modules_.

## When To Use Internal Modules

Only call internal modules from:

* other modules that ship with Storyplayer
* Storyplayer's runtime code

We'll always update Storyplayer and its modules to cope when internal module APIs change. We can't do the same for your own modules or stories.

<div class="callout danger" markdown="1">
#### Never Call An Internal Module From Your Stories

Internal modules are not designed to be used directly from stories.
</div>

<div class="callout warning" markdown="1">
#### Guarantees About Internal Modules

In short, there are none. If we need to, we'll change the API of an internal module __without warning__.
</div>

<div class="callout info" markdown="1">
#### If You Need An Internal Module

If an internal module really is the only way that you can get something done, [please let us know by raising an issue on GitHub](https://github.com/datasift/storyplayer/issues). We'll create a new module or extend an existing module that you can safely use in your stories and modules.
</div>