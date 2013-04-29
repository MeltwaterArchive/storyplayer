---
layout: modules-browser
title: The Browser Module
prev: '<a href="../../modules/assertions/assertsString.html">Prev: String Assertions</a>'
next: '<a href="../../modules/browser/searching-the-dom.html">Next: Searching The DOM</a>'
---

# The Browser Module

## Introduction

The __Browser__ module allows you to load and inspect HTML pages.  It also has (currently limited) support for inspecting the HTTP response when a HTML page was loaded.

The source code for this Prose module can be found in these PHP classes:

* DataSift\Storyplayer\Prose\BrowserActions
* DataSift\Storyplayer\Prose\BrowserDetermine
* DataSift\Storyplayer\Prose\BrowserExpects

## We Only Support Real Web Browsers

Storyplayer is (first and foremost) a story automation tool, built to mimic as closely as possible how your end-users are going to interact with your product or service.  To stay true to this mission, Storyplayer's Browser module only supports running tests against real web browsers.

Any non-trivial web-based application these days relies heavily on client-side JavaScript, not just to assemble the page in the first place, but also to trigger events as the end-user moves the mouse around, clicks on things, and types into input boxes.  Different web browsers have their own JavaScript engines, with their own performance characteristics and their own quirks and bugs.  The only way to be certain that your app works on these browsers is to test them using the browsers you want to support.

'Headless' engines, such as PhantomJS, whilst great pieces of software in their own right, aren't exactly the same as desktop and mobile web browsers.  Tests should give you maximum confidence, and that's why we only support real web browsers.

## Dependencies

These dependencies are automatically installed when you install Storyplayer:

* [browsermob-proxy](https://github.com/webmetrics/browsermob-proxy) - HTTP proxy with REST API
* [Selenium WebDriver](http://docs.seleniumhq.org/) - web browser remote control with REST API
* [ChromeDriver](https://code.google.com/p/selenium/wiki/ChromeDriver) - WebDriver bridge between Selenium and Google Chrome
* [PHP WebDriver Client](https://github.com/datasift/php_webdriver) - client library for talking to Selenium, originally by [Facebook](http://facebook.com)

You need to install:

* [Google Chrome](http://google.com/chrome) - Google's web browser
* [screen](http://www.gnu.org/software/screen/) - terminal management

Support for other browsers will be added, and we also plan to add support for testing via [Sauce Labs](http://saucelabs.com) in the near future.

Additionally, to use this module, you need to run Storyplayer on a machine with a real desktop installed and running, because it works by running a real web browser session.  You can't use this module on a 'headless'
machine, nor (in our experience) inside Xnest.

## Starting BrowserMob-Proxy And Selenium Server

Behind the scenes, the Browser module uses a real web browser, controlled via [Selenium WebDriver](http://docs.seleniumhq.org/).  At the moment, only Chrome is supported, but support for other browsers is planned.

We also use a custom version of [browsermob-proxy](https://github.com/webmetrics/browsermob-proxy), which is required for SSL support, HTTP Basic Auth support, and Request/Response inspection via the HTTP Archive.

To use the Browser module, you need to have Selenium Server and BrowserMob-Proxy running on your machine before you run Storyplayer.  They are installed as part of the Storyplayer, but need to be started by hand:

{% highlight bash %}
browsermob-proxy.sh start
selenium-server.sh start
{% endhighlight %}

This will start _browsermob-proxy_ and _selenium_ in _[screen](http://www.gnu.org/software/screen/)_ sessions in the background.

We've looked at the possibility of having Storyplayer start these for you, but decided that this was too slow (both are Java applications, which take time to start) and too unreliable (both applications need a few seconds before they have finished initialising, and that time depends on the speed of your computer).  It's also sometimes handy to be able to look at the output from Selenium to understand why an operation isn't working.

## Using The Browser Module

The basic format of an action is:

{% highlight php %}
$st->MODULE()->ACTION()->SEARCHTERM()
{% endhighlight %}

where __module__ is one of:

* _[fromBrowser()](fromBrowser.html)_ - get data from the browser and the HTML page that is loaded
* _[expectsBrowser()](expectsBrowser.html)_ - test the contents of the HTML page
* _[usingBrowser()](usingBrowser.html)_ - load HTML pages, and interact with the pages

__action__ is one of the documented actions available from that module, and __searchterm__ is one of [the ways to search inside the HTML page](searching-the-dom.html).

Here are some examples:

{% highlight php %}
$st->usingBrowser()->gotoPage('http://datasift.com');
$title = $st->fromBrowser()->getTitle();
$st->expectsBrowser()->has()->linkWithText('Login');
{% endhighlight %}
