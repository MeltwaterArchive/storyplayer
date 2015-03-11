---
layout: v2/modules-curl
title: usingCurl()
prev: '<a href="../../modules/curl/fromCurl.html">Prev: fromCurl()</a>'
next: '<a href="../../modules/failure/index.html">Next: The Failure Module</a>'
updated_for_v2: true
---

# usingCurl()

_usingCurl()_ allows you to send data to HTTP services via PUT, POST and DELETE requests.

The source code for these actions can be found in the class `Prose\UsingCurl`.

<div class="callout warning" markdown="1">
#### Not Implemented

This module has not yet been implemented.
</div>

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.
