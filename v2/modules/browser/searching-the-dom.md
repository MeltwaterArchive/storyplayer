---
layout: modules-browser
title: Searching The DOM
prev: '<a href="../../modules/browser/index.html">Prev: The Browser Module</a>'
next: '<a href="../../modules/browser/search-targets.html">Next: Supported Search Targets</a>'
---

# Searching The DOM

## Introduction

Most of the Browser module's operations need a DOM element to work on.  You tell the Browser module which element to use by adding a _search term_ to the end of your operation.

An element search term looks like this:

{% highlight php %}
[from|into]ButtonWithId('your-id');
{% endhighlight %}

where:

* __from|into__ are entirely optional - you can add either _from_ or _into_ at the front of the search term to make it nicer to read, or you can leave it out entirely!
* __button__ is one of our [supported targets](search-targets.html)
* __WithId__ is one of our [supported filters](search-filters.html)

You can use any combination of search target and filter with all of the operations that require a search term.

## Examples

Here are some examples:

{% highlight php %}
$st->usingBrowser()->click()->buttonWithText("Login");
$st->usingBrowser()->type('storyplayer is great!')->intoBoxWithLabel("Feedback");
$contents = $st->fromBrowser()->getText()->fromFieldWithClass("important");
{% endhighlight %}

Search terms are (effectively) map reduce jobs that we run inside the browser:

* the _search target_ produces a list of DOM elements by their tags (the map), and
* the _search filter_ reduces that list down into the final search result.

Inside Storyplayer, search terms are converted into [XPath](http://www.w3.org/TR/xpath/) expressions, which are then passed over to Selenium to run inside the web browser.  Storyplayer's log always shows the XPath expression that a search term becomes - very handy for when we find bugs!

