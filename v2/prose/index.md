---
layout: v2/prose
title: Introducing Prose
prev: '<a href="../stories/tales.html">Prev: Tales</a>'
next: '<a href="../prose/the-st-object.html">Next: The $st Object</a>'
---

# Introducing Prose

When we created Storyplayer, we designed it to be used by both experienced software developers and software testers.  We wanted a style of programming that was incredibly easy to read, and yet at the same time resulted in code that was very reliable (after all, there are few things worse than wasting time debugging tests).

In keeping with the story theme, we've called this style _Prose_.

## What Is Prose?

Prose is the programming style used to automate [user stories](../stories/user-stories.html) and [service stories](../stories/service-stories.html).  Prose is pure PHP - there's no _Domain-Specific Language (DSL)_ to learn.

Stories are told using PHP statements that call Storyplayer's [$st object](the-st-object.html):

{% highlight php %}
$st->verbMODULE()->ACTION();
{% endhighlight %}

For example:

{% highlight php %}
$st->usingBrowser()->gotoPage("https://datasift.com");
$st->usingBrowser()->waitForTitle(5, "DataSift | The Leading Social Platform");
{% endhighlight %}

## Prose Modules

[Modules](../modules/index.html) are libraries of code that ship with Storyplayer for you to reuse.  This allows you to be productive straight away.  [The $st object](the-st-object.html) takes care of loading and instantiating them for you.

You can also [create your own modules](creating-modules.html) for Storyplayer.  You will want to do this to reuse functionality across your tests (especially for your web pages), or if you need to add new features to Storyplayer.

## Prose Verbs

When you call a module from your story, you prefix the module name with a verb:

{% highlight php %}
// gets data from the Browser module
$title = $st->fromBrowser()->getTitle();

// uses the Browser module to make sure that
// a condition is met
$st->expectsBrowser()->hasTitle("Dashboard");

// uses the Browser module to do something
$st->usingBrowser()->gotoPage("https://datasift.com");
{% endhighlight %}

There are three standard verbs used in Prose:

* _from_ - get data from somewhere.  This should never change state in whatever you are testing (if it does, it is not _deterministic_, and cannot be used reliably in your testing).  Returns the requested data on success, or NULL on failure.
* _expects_ - make sure that a condition is met.  If the condition isn't met, an Exception is thrown.  This should never change state in whatever you are testing (if it does, it is not _deterministic_, and cannot be used reliably in your testing).
* _using_ - do something.  This normally changes the state of whatever you are testing, or the state of your test environment.

You'll sometimes see other verbs used (such as _asserts_), but those are the main three verbs commonly supported by Prose modules.

## Exceptions When Things Go Wrong

Each _ACTION()_ throws an exception if something goes wrong.  You write your stories to assume that everything has worked, and you let Storyplayer catch and handle the exceptions for you.

This approach improves the reliability of your tests.  There are no return values to check, and no actions that you need to take when things go wrong and your tests fail.

