---
layout: modules-zeromq
title: expectsZmq()
prev: '<a href="../../modules/zeromq/index.html">Prev: The ZeroMQ Module</a>'
next: '<a href="../../modules/zeromq/usingZmq.html">Next: usingZmq()</a>'
---

# expectsZmq()

_expectsZmq()_ allows you to make sure that a ZMQ socket would behave in the way that you expect it to.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\ZmqExpects_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## canSendmultiNonBlocking()

Use _$st->expectsZmq()->canSendmultiNonBlocking()_ to make sure that the ZMQ socket's sending message buffer isn't currently full.

{% highlight php %}
$st->expectsZmq()->canSendmultiNonBlocking($socket, $message);
{% endhighlight %}

where:

* _$socket_ is a ZMQ_Socket created by _[usingZmq()->bind()](usingZmq.html#bind)_ or _[usingZmq()->connect()](usingZmq.html#connect)_
* _$message_ is an array containing a multipart ZeroMQ message to send

If sending the message would cause ZeroMQ (and therefore your process) to block, an exception is thrown.  Otherwise, the message is sent.

__NOTE:__

If a message doesn't block when you attempt to send it, that doesn't guarantee that the message has reached the receiving process.  The message could be buffered by ZeroMQ itself, or it could be buffered by the operating system's TCP/IP stack.

In other words, be conservative about the conclusions you draw in your tests.