---
layout: v1/modules-http
title: The HttpClientResponse Object
prev: '<a href="../../modules/http/index.html">Prev: The HTTP Module</a>'
next: '<a href="../../modules/http/expectsHttpResponse.html">Next: expectsHttpResponse()</a>'
---

# The HttpClientResponse Object

Working with the HTTP module means working with the _HttpClientResponse_ object from [DataSift's Stone library](https://github.com/datasift/Stone).

## Public API

Here's the parts of the _HttpClientResponse_ that you can safely use in your tests.

{% highlight php %}
namespace DataSift\Stone\HttpLib;

class HttpClientResponse
{
	// what type of response did we get?
	//
	// TYPE_INVALID, TYPE_IDENTITY, TYPE_CHUNKED, TYPE_WEBSOCKET
	public $type = false;

	// HTTP version that the remote server speaks
	public $httpVersion;

	// response code
	public $statusCode;

	// text accompanying the response code
	public $statsMessage;

	// response headers
	public $headers = array();

	// response body
	public $body;

	// chunks if response is using HTTP streaming
	public $chunks = array();

	// frames if response is using websockets
	public $frames = array();

	// the raw response from the HTTP server
	public $rawResponse = '';

	// errors list
	public $errorMsgs = array();

	// do we need to disconnect?
	public $mustClose = false;

	// returns TRUE if the connection must close
	//
	// e.g. HTTP/1.0 response, streaming close headers,
	//      or a websocket close frame
	public function connectionMustClose();

	// returns TRUE if the HTTP server is streaming to us
	public function transferIsChunked();

	// returns TRUE if HttpLib has detected problems
	public function hasErrors();
}
{% endhighlight %}