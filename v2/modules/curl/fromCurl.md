---
layout: v2/modules-curl
title: fromCurl()
prev: '<a href="../../modules/curl/index.html">Prev: The cURL Module</a>'
next: '<a href="../../modules/curl/usingCurl.html">Next: usingCurl()</a>'
updated_for_v2: true
---

# fromCurl()

_fromCurl()_ allows you to retrieve data from HTTP services via GET requests.

The source code for these actions can be found in the class `Prose\FromCurl`.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## get()

Use `fromCurl()->get()` to retrieve data via a HTTP GET request.

{% highlight php startinline %}
$data = fromCurl()->get($url, $params);
{% endhighlight %}

where:

* __$url__ is the URL to make the GET request to
* __$params__ is an _optional_ array of query string parameters to add to __$url__

_get()_ returns the response body. If the response contains JSON, _get()_ will automatically decode the JSON for you and return a PHP object or array.