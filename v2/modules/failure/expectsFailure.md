---
layout: v2/modules-failure
title: expectsFailure()
prev: '<a href="../../modules/failure/index.html">Prev: The Failure Module</a>'
next: '<a href="../../modules/file/index.html">Next: The File Module</a>'
---

# expectsFailure()

_expectsFailure()_ allows you to run a set of steps that are supposed to fail.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception.  _Do not catch exceptions thrown by these actions_.  Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## when()

Use _when()_ to run a set of steps that should fail.

{% highlight php startinline %}
expectsFailure()->when("check is logged in", function() {
	// we should not be logged in
	usingBrowser()->gotoPage("http://www.example.com/dashboard");
	expectsBrowser()->doesNotHave()->linkWithText("Logout");
});
{% endhighlight %}

_when()_ takes two parameters:

* __$what__: a string to say what
* __$callback__: a PHP closure. If the steps inside the closure fail, then _when()_ will catch the failure and allow your story to continue.  If none of the steps fail, then _when()_ will treat that as a failure, and throw an exception for Storyplayer to catch.