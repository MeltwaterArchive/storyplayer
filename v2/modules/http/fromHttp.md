---
layout: v2/modules-http
title: fromHttp()
prev: '<a href="../../modules/http/expectsHttpResponse.html">Prev: expectsHttpResponse()</a>'
next: '<a href="../../modules/http/usingHttp.html">Next: usingHttp()</a>'
updated_for_v2: true
---

# fromHttp()

_fromHttp()_ allows you to make a GET call to a (possibly) remote HTTP server.

The source code for these actions can be found in the class `Prose\FromHttp`.

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  None of these actions throw exceptions on failure.

## get()

Use `fromHttp()->get()` to make a GET request to a (possibly) remote HTTP server.

{% highlight php startinline %}
$response = usingHttp()->get($url, $params = array(), $headers = array(), $timeout = null);
{% endhighlight %}

_get()_ takes up to four parameters:

* `$url` - the URL to make the GET request to
* `$params` - (optional) list of query string parameters to use in the request
* `$headers` - (optional) list of extra HTTP headers to use in the request
* `$timeout` - (optional) how long to wait for the HTTP request to complete

_get()_ returns a _[HttpClientResponse](HttpClientResponse.html)_ object with the response from the HTTP server.