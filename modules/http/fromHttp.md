---
layout: modules-http
title: fromHttp()
prev: '<a href="../../modules/http/expectsHttpResponse.html">Prev: expectsHttpResponse()</a>'
next: '<a href="../../modules/http/usingHttp.html">Next: usingHttp()</a>'
---

# fromHttp()

_fromHttp()_ allows you to make a GET call to a (possibly) remote HTTP server.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromHttp_.

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  None of these actions throw exceptions on failure.

## get()

Use `$st->fromHttp()->get()` to make a GET request to a (possibly) remote HTTP server.

{% highlight php %}
$response = $st->usingHttp()->get('http://api.datasift.com/balance');
{% endhighlight %}

_get()_ takes up to three parameters:

* _$url_ - the URL to make the GET request to
* _$params_ - (optional) list of query string parameters to use in the request
* _$headers_ - (optional) list of extra HTTP headers to use in the request

_get()_ returns a _[HttpClientResponse](HttpClientResponse.html)_ object with the resposne from the HTTP server.