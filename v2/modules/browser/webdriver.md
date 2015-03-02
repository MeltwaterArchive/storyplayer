---
layout: v2/modules-browser
title: The WebDriver Library
prev: '<a href="../../modules/browser/usingBrowser.html">Prev: usingBrowser()</a>'
next: '<a href="../../modules/checkpoint/index.html">Next: The Checkpoint Module</a>'
---

# The WebDriver Library

The _Browser_ module uses [the WebDriver client library](https://github.com/datasift/php_webdriver/) to talk to Selenium Server (which, in turn, talks to the web browser that is being controlled).  This library was [originally written and released by Facebook](https://github.com/facebook/php-webdriver); we've forked it to add new functionality, bundle Selenium and the chromedriver, PSR-0 autoloading support, and PEAR packaging. Just as with the original, this library is released under the terms of [the Apache license](http://opensource.org/licenses/Apache-2.0) (the rest of Storyplayer uses [the new BSD license](http://opensource.org/licenses/BSD-3-Clause)).

You will find the source code for the WebDriver client library under the _DataSift\WebDriver_ namespace.

Although we've added many features to the Browser module, there may be times when you need to work directly with the WebDriver library.  This isn't intended to be a full tutorial on WebDriver, but should hopefully provide enough pointers for you dig deeper into it yourself.

## Supported Browsers

For a full list of supported browsers, please [see the Devices section](../../devices/index.html) of this manual.

## Most Common Case - XPath Queries

Most of the actions defined by the _Browser_ module ultimately get turned into [XPath](http://www.w3.org/TR/xpath/) queries against the loaded DOM.  If you need to extract data that the _Browser_ module has no suitable [search term](searching-the-dom.html) for, you'll have to write your own XPath queries.  Teaching you how to write XPath is beyond the scope of this manual.

To run your XPath query, you need a _WebDriverElement_ object to run it against.  You can get one of these using _[fromBrowser()->getTopElement()](fromBrowser.html#gettopelement)_:

{% highlight php startinline %}
$xpath = 'descendant::div/div/table/tr/*';
$topElement = fromBrowser()->getTopElement();
$elements = $topElement->getElements('xpath', $xpath);
{% endhighlight %}

You can run XPath queries using either _getElement()_ or _getElements()_. Both take the same two parameters:

* `$type` - the type of search to perform
* `$query` - the query data to use in the search

_getElement()_ returns a single _WebDriverElement_ object (or NULL on failure), whilst _getElements()_ returns an array of _WebDriverElement_ objects.

If you need to delve deeper than this, then you need to learn the Json Wire Protocol and the WebDriverElements class.

## The Json Wire Protocol

The [Json Wire Protocol](https://code.google.com/p/selenium/wiki/JsonWireProtocol) is the ultimate documentation for what Selenium WebDriver can do.  It describes all of the commands that can be sent to the Selenium standalone server via its REST interface.

The protocol is relatively stable; we've been working with it since late 2011 and haven't seen major changes that have disrupted our testing.  Unfortunately there doesn't seem to be any versioning or formal changelog for the protocol.

Facebook's original WebDriver client library acts as a thin facade to the Json Wire Protocol, making use of PHP's magic methods to translate PHP method calls into the Json Wire Protocol and back again.  We've added a few helpers to improve convenience, but largely the approach remains the same.

## WebDriver Sessions

The _DataSift\WebDriver\WebDriverSession_ represents a single running instance of a web browser.  You can get the current session using the [Device Manager module](../devicemanager/index.html):

{% highlight php startinline %}
$session = fromDeviceManager()->getRunningWebBrowser();
{% endhighlight %}

The session provides a lot of useful functionality, including opening web pages, working with multiple browser windows, working with cookies, and much more.  We hope to add support for most of this functionality to the _Browser_ module over time.

## WebDriver Elements

The _DataSift\WebDriver\WebDriverElement_ represents a single DOM element inside the web browser.

{% highlight php startinline %}
$session = fromDeviceManager()->getRunningWebBrowser();
$body = $session->getElement('tag name', 'body');
{% endhighlight %}

You can get elements via the _WebDriverSession_, or via other _WebDriverElements_.  See _[POST /session/:sessionId/element](https://code.google.com/p/selenium/wiki/JsonWireProtocol#POST_/session/:sessionId/element)_ in the Json Wire Protocol for the search strategies that Selenium supports.

Once you have a _WebDriverElement_, it's important to note that the object itself is just like an open file handle; if you want to know anything about the DOM element it refers to, you need to carry out operations on the _WebDriverElement_ to get that information.

## Dealing With Failed Searches

When we're working with _WebDriverElements_ directly, we've noticed that we end up spending most of that time writing search queries that work.  Here are our tips to help speed up the process:

* _Have the Selenium server screen session open in a terminal._ This allows you to see exactly what Selenium thinks it has been asked to do by the PHP _WebDriver_ client library.
* _Use an XPath extension in your browser._ Creating the XPath query interactively speeds up the iteration cycle enormously, and is especially useful when your web page contains less-than-perfect markup that defeats what you think the "obvious" XPath query should be.
* _Avoid monolithic XPath queries._ XPath is fantastically expressive - in a single query, you can write a very precise search.  Unfortunately, the more parts to your XPath query, the more likely it is that a markup change to your app will break your query.
