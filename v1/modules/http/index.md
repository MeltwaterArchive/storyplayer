---
layout: v1/modules-http
title: The HTTP Module
prev: '<a href="../../modules/hoststable/usingHostsTable.html">Prev: usingHostsTable()</a>'
next: '<a href="../../modules/http/HttpClientResponse.html">Next: The HttpClientResponse Object</a>'
---

# The HTTP Module

## Introduction

The __HTTP__ module allows you to send GET, POST, PUT and DELETE requests to a HTTP server.

The source code for this Prose module can be found in these PHP classes:

* DataSift\Storyplayer\Prose\ExpectsHttpResponse
* DataSift\Storyplayer\Prose\FromHttp
* DataSift\Storyplayer\Prose\UsingHttp

## Which Module Should I Use - HTTP or Browser / Form?

As a general rule of thumb, use [the Browser module](../modules/browser/index.html) and [the Form module](../modules/form/index.html) when testing web pages, and the HTTP module when testing your app's APIs.

There's absolutely nothing stopping you from using both sets of modules in the same test; for example, performing an action via your app's API, and then using the web browser to log into your app and make sure that the API call made the changes that you expect.

You _can_ use the HTTP module to retrieve web pages from your app, if that's what you want to do, but if you do, __remember__ that any cookies your app sends won't get set in the browser.  As long as you treat the HTTP module and the Browser module as being completely independent of each other, you'll be fine.

## RESTful Or Not - It's Your Choice

There are many different styles of HTTP-based API out in the world, and we've kept this module quite low level to make sure that it should be able to test your API whether it's RESTful or not.

## SOAP Support

This module provides no helpers at all for working with SOAP-based APIs.  We don't use SOAP ourselves here at DataSift, but if someone wants to send a pull-request for a SOAP module, we'd be happy to add it to the global dialect.

## SSL Support

At the moment, this module does not support SSL at all (or, more accurately, the underlying _HttpLib_ in Stone doesn't support SSL yet).  This is something we'll be addressing in the near future.

## Dependencies

This module relies on [DataSift's Stone library](http://github.com/datasift/Stone), which is installed as part of Storyplayer's installation.

## Using The HTTP Module

The basic format of an action is:

{% highlight php %}
$st->MODULE()->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromHttp()](fromHttp.html)_ - get data from a (possibly) remote HTTP server
* _[expectsHttpResponse()](expectsHttpResponse.html)_ - test the data in the HttpClientResponse received from a (possibly) remote HTTP server
* _[usingHttp()](usingHttp.html)_ - make HTTP requests to a (possibly) remote HTTP server