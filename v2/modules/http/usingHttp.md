---
layout: v2/modules-http
title: usingHttp()
prev: '<a href="../../modules/http/fromHttp.html">Prev: fromHttp()</a>'
next: '<a href="../../modules/iterators/index.html">Next: Iterators</a>'
---

# usingHttp()

_usingHttp()_ allows you to make HTTP requests to a (possibly) remote HTTP server.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingHttp_.

## Behaviour And Return Codes

Unlike most 'usingXXX' modules, every action here returns a _[HttpClientResponse](HttpClientResponse.html)_ object, which you can then test using _[expectsHttpResponse()](expectsHttpResponse.html)_ and/or [the Assertions module](../assertions/index.html).

If the HTTP call fails, an exception is thrown. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## get()

If you're looking to make a GET request, use _[fromHttp()->get()](fromHttp.html#get)_.

## delete()

Use `usingHttp()->delete()` to make a HTTP DELETE request to a (possibly) remote HTTP server.

{% highlight php startinline %}
$response = usingHttp()->delete('http://api.example.com/destination/ec2-1');
{% endhighlight %}

_delete()_ takes up to four parameters:

* `$url` - the URL to send the DELETE request to
* `$params` - (optional) additional parameters to add to the query string
* `$body` - (optional) content to send (such as a JSON payload)
* `$headers` - (optional) additional headers to add to the request

## post()

Use `usingHttp()->post()` to make a HTTP POST request to a (possibly) remote HTTP server.

{% highlight php startinline %}
$payload = json_encode(array('ami' => 'ubuntu-13.04'));
$response = usingHttp()->post('http://api.example.com/destination/ec2-1', array(), $payload);
{% endhighlight %}

_post()_ takes up to four parameters:

* `$url` - the URL to send the DELETE request to
* `$params` - (optional) additional parameters to add to the query string
* `$body` - (optional) content to send (such as a JSON payload)
* `$headers` - (optional) additional headers to add to the request

## put()

Use `usingHttp()->put()` to make a HTTP PUT request to a (possibly) remote HTTP server.

{% highlight php startinline %}
$payload = json_encode(array('ami' => 'ubuntu-13.04', 'name' => 'ec2-1'));
$response = usingHttp()->delete('http://api.example.com/destination/');
{% endhighlight %}

_put()_ takes up to four parameters:

* `$url` - the URL to send the DELETE request to
* `$params` - (optional) additional parameters to add to the query string
* `$body` - (optional) content to send (such as a JSON payload)
* `$headers` - (optional) additional headers to add to the request
