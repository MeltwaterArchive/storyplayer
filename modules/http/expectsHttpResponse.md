---
layout: modules-http
title: expectsHttpResponse()
prev: '<a href="../../modules/http/HttpClientResponse.html">Prev: The HttpClientResponse Object</a>'
next: '<a href="../../modules/http/fromHttp.html">Next: fromHttp()</a>'
---

# expectsHttpResponse()

_expectsHttpResponse()_ allows you to test the contents of a _[HttpClientResponse](HttpClientResponse.html)_ object returned by _[fromHttp()](fromHttp.html)_ or _[usingHttp()](usingHttp.html)_.

This is a convenience module to improve the readability of your tests. You can do the same tests by using [the Assertions module](../assertions/index.html) directly on the _HttpClientResponse's_ public API if you prefer.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\ExpectsHttpResponse_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## hasStatusCode()

Use `$st->expectsHttpResponse()->hasStatusCode()` to make sure that a received _HttpClientResponse_ has the expected HTTP status code in the response.

{% highlight php %}
$response = $st->usingHttp()->get('http://api.datasift.com/balance');
$st->expectsHttpResponse($response)->hasStatusCode('200');
{% endhighlight %}

## hasBody()

Use `$st->expectsHttpResponse()->hasBody()` to make sure that a received _HttpClientResponse_ has the content you expect in the body.

{% highlight php %}
$expectedBody = '{"status": "okay", "balance": 200}';
$response = $st->usingHttp()->get('http://api.datasift.com/balance');
$st->expectsHttpResponse($response)->hasBody($expectedBody);
{% endhighlight %}

This is very handy when testing APIs that return a JSON-encoded response as the body.