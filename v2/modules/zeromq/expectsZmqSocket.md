---
layout: v2/modules-zeromq
title: expectsZmqSocket()
prev: '<a href="../../modules/zeromq/usingZmqContext.html">Prev: usingZmqContext()</a>'
next: '<a href="../../modules/zeromq/fromZmqSocket.html">Next: fromZmqSocket()</a>'
updated_for_v2: true
---

# expectsZmqSocket()

_expectsZmqSocket()_ allows you to make sure that a ZMQ socket would behave in the way that you expect it to.

The source code for these actions can be found in the class `Prose\ExpectsZmqSocket`.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## canSendmultiNonBlocking()

Use `expectsZmqSocket()->canSendmultiNonBlocking()` to make sure that the ZMQ socket's sending message buffer isn't currently full.

{% highlight php startinline %}
expectsZmqSocket($socket)->canSendmultiNonBlocking($message);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$message` is an array containing a multipart ZeroMQ message to send

If sending the message would cause ZeroMQ (and therefore your process) to block, an exception is thrown.  Otherwise, the message is sent.

__NOTE:__

If a message doesn't block when you attempt to send it, that doesn't guarantee that the message has reached the receiving process.  The message could be buffered by ZeroMQ itself, or it could be buffered by the operating system's TCP/IP stack.

In other words, be conservative about the conclusions you draw in your tests.