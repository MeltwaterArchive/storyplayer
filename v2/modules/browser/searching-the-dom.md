---
layout: v2/modules-browser
title: Searching The DOM
prev: '<a href="../../modules/browser/index.html">Prev: The Browser Module</a>'
next: '<a href="../../modules/browser/search-targets.html">Next: Supported Search Targets</a>'
---

# Searching The DOM

## Introduction

Most of the Browser module's operations need a DOM element to work on.  You tell the Browser module which element to use by adding a _search term_ to the end of your operation.

An element search term looks like this:

{% highlight php startinline %}
[from|into|of]ButtonWithId('your-id');
{% endhighlight %}

where:

* __from__ or __into__ or __of__ are entirely optional - you can add them to the front of the search term to make it nicer to read, or you can leave it out entirely!
* __Button__ is one of our [supported targets](search-targets.html)
* __WithId__ is one of our [supported filters](search-filters.html)

You can use any combination of search target and filter with all of the operations that require a search term.

<div class="callout info" markdown="1">
#### camelCase Your Search Terms

Search terms are _magic methods_. They look like real methods in your code, but behind the scenes Storyplayer takes advantage of PHP's meta-programming capabilities to create the search that you want.

The key to this magic is the name of the search term method you use. Make sure that it is in _camelCase_. Storyplayer relies on this to break the method name up into the individual words. If you don't _camelCase_ your search term (e.g. `frombuttonwithid`) then Storyplayer can't understand it!
</div>

## Examples

Here are some examples:

{% highlight php startinline %}
usingBrowser()->click()->buttonWithText("Login");
usingBrowser()->type('storyplayer is great!')->intoBoxWithLabel("Feedback");
$contents = fromBrowser()->getText()->fromFieldWithClass("important");
{% endhighlight %}

Search terms are (effectively) map reduce jobs that we run inside the browser:

* the _search target_ produces a list of DOM elements by their tags (the map), and
* the _search filter_ reduces that list down into the final search result.

Inside Storyplayer, search terms are converted into [XPath](http://www.w3.org/TR/xpath/) expressions, which are then passed over to Selenium to run inside the web browser.  Storyplayer's log always shows the XPath expression used - very handy for understanding why a search failed.

