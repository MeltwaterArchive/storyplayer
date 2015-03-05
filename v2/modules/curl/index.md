---
layout: v2/modules-curl
title: The cURL Module
prev: '<a href="../../modules/checkpoint/usingCheckpoint.html">Prev: usingCheckpoint()</a>'
next: '<a href="../../modules/curl/fromCurl.html">Next: fromCurl()</a>'
---

# The cURL Module

The `cURL` module allows you to work with `libcurl`, the defacto standard client library for HTTP on the Internet.

The source code for this module can be found in these PHP classes:

* `Prose\FromCurl`
* `Prose\UsingCurl`

## Why Two Competing Modules?

For making HTTP requests, you've currently got two choices: the [cURL module](../curl/index.html) and the [HTTP module](../http/index.html). Which one should you choose?

__Write your tests for the HTTP module, and switch to the cURL module if the HTTP module's strictness causes you problems.__

* The HTTP module is specifically built for in-depth testing of HTTP requests and responses. It provides detailed information about the response. It's a lot stricter than libcurl, and sometimes this strictness can get in the way of testing a service that's built on top of a low-quality HTTP stack.
* The cURL module is available for when you can't use the HTTP module for whatever reason. It doesn't provide detailed information about the response, making it unsuitable for detailed testing of HTTP APIs.

## Dependencies

You need to make sure that you have installed the cURL extension for PHP.

## Using The cURL Module

The basic format of an action is:

{% highlight php startinline %}
MODULE()->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromCurl()](fromCurl.html)_ - get data from remote systems via GET requests
* _[usingCurl()](usingCurl.html)_ - send data to remove systems via PUT, POST and DELETE requests

and __action__ is one of the documented methods available on the __module__ you choose.

Here are some examples:

{% highlight php startinline %}
$data = fromCurl()->get('http://api.example.com/balance');
{% endhighlight %}