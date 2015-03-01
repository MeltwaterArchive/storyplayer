---
layout: v2/modules-browser
title: The Browser Module
prev: '<a href="../../modules/assertions/assertsString.html">Prev: String Assertions</a>'
next: '<a href="../../modules/browser/searching-the-dom.html">Next: Searching The DOM</a>'
---

# The Browser Module

## Introduction

The __Browser__ module allows you to load and inspect HTML pages.  It also has (currently limited) support for inspecting the HTTP response when a HTML page was loaded.

The source code for this Prose module can be found in these PHP classes:

* `Prose\ExpectsBrowser`
* `Prose\FromBrowser`
* `Prose\UsingBrowser`

## We Only Support Real Web Browsers

Storyplayer is (first and foremost) a story automation tool, built to mimic as closely as possible how your end-users are going to interact with your product or service.  To stay true to this mission, Storyplayer's Browser module only supports running tests against real web browsers.

Any non-trivial web-based application these days relies heavily on client-side JavaScript, not just to assemble the page in the first place, but also to trigger events as the end-user moves the mouse around, clicks on things, and types into input boxes.  Different web browsers have their own JavaScript engines, with their own performance characteristics and their own quirks and bugs.  The only way to be certain that your app works on these browsers is to test them using the browsers you want to support.

'Headless' engines, such as PhantomJS, whilst great pieces of software in their own right, aren't exactly the same as desktop and mobile web browsers.  Tests should give you maximum confidence, and that's why we only support real web browsers.

## Dependencies

Storyplayer automatically installs [many of the dependencies required](../../devices/how-it-works.html) for you when you run `storyplayer install`.

You need to install:

* [Google Chrome](http://google.com/chrome) - Google's web browser
* [Mozilla Firefox](http://www.mozilla.org/en-US/firefox/new/) - Mozilla's web browser
* [screen](http://www.gnu.org/software/screen/) - terminal management

Additionally, to use this module, you need to run Storyplayer on a machine with a real desktop installed and running, because it works by running a real web browser session.  You can't use this module on a 'headless' machine, nor (in our experience) inside Xnest.  (If you prefer, you can take advantage of our [Sauce Labs integration](../../devices/saucelabs.html), or for advanced users you can use Storyplayer with [a remote WebDriver](../../devices/remotewebdriver.html)).

## Using The Browser Module

The basic format of an action is:

{% highlight php %}
MODULE()->ACTION()->SEARCHTERM()
{% endhighlight %}

where __module__ is one of:

* _[fromBrowser()](fromBrowser.html)_ - get data from the browser and the HTML page that is loaded
* _[expectsBrowser()](expectsBrowser.html)_ - test the contents of the HTML page
* _[usingBrowser()](usingBrowser.html)_ - load HTML pages, and interact with the pages

__action__ is one of the documented actions available from that module, and __searchterm__ is one of [the ways to search inside the HTML page](searching-the-dom.html).

Here are some examples:

{% highlight php %}
usingBrowser()->gotoPage('http://datasift.com');
$title = fromBrowser()->getTitle();
expectsBrowser()->has()->linkWithText('Login');
{% endhighlight %}
