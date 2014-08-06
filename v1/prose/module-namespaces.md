---
layout: v1/prose
title: Module Namespaces
prev: '<a href="../prose/module-loading.html">Prev: Module Loading</a>'
next: '<a href="../prose/creating-prose-modules.html">Next: Creating Your Own Prose Modules</a>'
---

# Module Namespaces

Storyplayer uses [PHP namespaces](http://php.net/manual/en/language.namespaces.php) to keep its internal code organised.  There are several namespaces for Prose modules, depending on whether the module is built into Storyplayer, or is an additional module created by yourself or someone else.

## The Namespace Search Order

When `$st` is searching for a module, it looks for the module's class in these PHP namespaces, in this order:

1. `Prose`
2. the namespaces listed in `prose->namespaces` in your [environment config](../configuration/prose-namespaces.html) (if defined)
3. `DataSift\Storyplayer\Prose`

For example, when you call `$st->usingBrowser()`, the `$st` object searches these PHP namespaces for the class `UsingBrowser`:

1. `Prose`
2. any additional configured namespaces
3. `DataSift\Storyplayer\Prose`

As [Browser](../modules/browser/index.html) is a built-in module, it will end up finding the class `DataSift\Storyplayer\Prose\UsingBrowser`.

## Which Namespace Is Right For Your Module?

Follow these guidelines when picking a namespace for your module.

* Use the `Prose` namespace for any modules that you don't distribute separately.  These are normally modules that you've created inside the GitHub repo that contains your tests.
* [Configure your own namespace](../configuration/prose-namespaces.html) for any modules that you intend to distribute (e.g. as Composer packages for others to use).  We recomend following the PSR-0 naming scheme to avoid making unnecessary problems for anyone who wants to reuse your modules.
* Use the `DataSift\Storyplayer\Prose` namespace only for modules that you are going to send to us via a pull request to ship with future releases of Storyplayer.
