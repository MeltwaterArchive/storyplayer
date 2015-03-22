---
layout: v2/modules-zeromq
title: fromZmqSocket()
prev: '<a href="../../modules/zeromq/expectsZmqSocket.html">Prev: expectsZmqSocket()</a>'
next: '<a href="../../modules/zeromq/usingZmqSocket.html">Next: usingZmqSocket()</a>'
updated_for_v2: true
---
# fromZmqSocket()

_fromZmqSocket()_ allows you to receive data from a ZeroMQ socket.

The source code for these actions can be found in the class `Prose\FromZmqSocket`.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and returns a value.
* If the test fails, the action throws an exception. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## getEndpoints()

Use `fromZmqSocket()->getEndpoints()` to receive a list of every address that the `ZMQSocket` is either bound to or connected to.

{% highlight php startinline %}
$addresses = fromZmqSocket($socket)->getEndpoints();
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$addresses` is set to an array containing a list of addresses where the socket is bound to or connected to

## recv()

Use `fromZmqSocket()->recv()` to receive a single-part message via a ZeroMQ socket previously created using _[usingZmqContext()->bindToPort()](usingZmqContext.html#bindToPort)_ or _[usingZmqContext()->connectToHost()](usingZmqContext.html#connectToHost)_.

{% highlight php startinline %}
$message = fromZmqSocket($socket)->recv($timeout=null);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$timeout` is how long to wait (in seconds) for a message (default is to wait 5 seconds)
* `$message` gets set to the single-part message read from the `$socket`

## recvMulti()

Use `fromZmqSocket()->recvMulti()` to receive a multi-part message via a ZeroMQ socket previously created using _[usingZmqContext()->bindToPort()](usingZmqContext.html#bindToPort)_ or _[usingZmqContext()->connectToHost()](usingZmqContext.html#connectToHost)_.

{% highlight php startinline %}
$message = fromZmqSocket($socket)->recvMulti($timeout=5);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$timeout` is how long to wait (in seconds) for a message (default is to wait 5 seconds)
* `$message` gets set to the multi-part message read from the `$socket`

## recvMultiNonBlocking()

Use `fromZmqSocket()->recvMultiNonBlocking()` to receive a multi-part message via a ZeroMQ socket previously created using _[usingZmqContext()->bindToPort()](usingZmqContext.html#bindToPort)_ or _[usingZmqContext()->connectToHost()](usingZmqContext.html#connectToHost)_.  If the socket would have blocked, `NULL` is returned to the caller.

{% highlight php startinline %}
$message = fromZmqSocket()->recvMultiNonBlocking();
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$message` gets set to the multi-part message read from the `$socket`, or NULL if the read would have blocked

See _[usingZmq()->recv()](#recv)_ for a discussion about why we recommend using these wrapper methods instead of simply working directly with the `ZMQ_Socket`.

## recvNonBlocking()

Use `fromZmqSocket()->recvNonBlocking()` to receive a single-part message via a ZeroMQ socket previously created using _[usingZmqContext()->bindToPort()](usingZmqContext.html#bindToPort)_ or _[usingZmqContext()->connectToHost()](usingZmqContext.html#connectToHost)_.  If the socket would have blocked, `NULL` is returned to the caller.

{% highlight php startinline %}
$message = fromZmqSocket()->recvNonBlocking();
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$message` gets set to the single-part message read from the `$socket`, or NULL if the read would have blocked