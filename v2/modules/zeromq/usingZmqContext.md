---
layout: v2/modules-zeromq
title: usingZmqContext()
prev: '<a href="../../modules/zeromq/index.html">Prev: The ZeroMQ Module</a>'
next: '<a href="../../modules/zeromq/expectsZmqSocket.html">Next: expectsZmqSocket()</a>'
updated_for_v2: true
---
# usingZmqContext()

_usingZmqContext()_ allows you to create a ZeroMQ context object. These are used in creating ZeroMQ sockets.

The source code for these actions can be found in the class `Prose\UsingZmqContext`.

## Behaviour And Return Codes

If the action succeeds, control is returned to your code. __Most actions return a value__, which breaks the normal convention for a usingXXXX() module, but which we feel makes sense in this specific case.

If the action fails, an exception is thrown. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## Supported ZMQ Socket Types

Several of the actions take a `$socketType` parameter. Here's a list of the supported values, and which kind of ZMQ socket they map to.

$socketType | ZMQ Socket Type
------------|----------------
"PUB"       | ZMQ::SOCKET_PUB
"SUB"       | ZMQ::SOCKET_SUB
"REQ"       | ZMQ::SOCKET_REQ
"REP"       | ZMQ::SOCKET_REP
"XREQ"      | ZMQ::SOCKET_XREQ
"XREP"      | ZMQ::SOCKET_XREP
"PUSH"      | ZMQ::SOCKET_PUSH
"PULL"      | ZMQ::SOCKET_PULL
"ROUTER"    | ZMQ::SOCKET_ROUTER
"DEALER"    | ZMQ::SOCKET_DEALER

## Connecting With A Shared Context

Create a `ZMQContext`, and then reuse it for all of the sockets in your test:

{% highlight php startinline %}
// create a shared context
$context = usingZmqContext()->getZmqContext();

// connect a socket
$sock1 = usingZmqContext($context)->connectToHost('default', 5000, 'PUSH');

// connect a second socket using the same context
$sock2 = usingZmqContext($context)->connectToHost('default', 5001, 'PULL')
{% endhighlight %}

## Connecting Without Creating A Context

This module also supports creating ZMQ sockets without sharing a `ZMQContext`:

{% highlight php startinline %}
// connect without a shared context
$sock1 = usingZmqContext()->connectToHost('default', 5000, 'PUSH');
$sock2 = usingZmqContext()->connectToHost('default', 5001, 'PULL');
{% endhighlight %}

When used like this, `usingZmqContext()` will create a new `ZMQContext` option for each socket.

## Shared Context Or Not?

All sockets that share the same `ZMQContext` share the same processing threads. You might find that you need separate `ZMQContext` objects for input and output sockets if you're handling large amounts of traffic.

If in doubt, connect using a shared context.

## bindToPort()

Use `usingZmqContext()->bindToPort()` to bind to a port on `localhost` to receive ZMQ connections.

{% highlight php startinline %}
$socket = usingZmqContext($context)->bindToPort(
    $portNumber, $socketType, $sendHwm=100, $recvHwm=100
);
{% endhighlight %}

where:

* `$context` is a `ZMQContext` created using `usingZmqContext()->getZmqContext()`. If omitted, Storyplayer will create a new ZMQContext for this action.
* `$portNumber` is the port number that you want to bind to
* `$socketType` is one of the [supported ZMQ socket types](#supported-zmq-socket-types)
* `$sendHwm` sets the length of the internal queue for sending messages
* `$recvHwm` sets the length of the internal queue for receiving messages

## connectToHost()

Use `usingZmqContext()->connectToHost()` to connect to a port on a host in your test environment.

{% highlight php startinline %}
$socket = usingZmqContext($context)->connectToHost(
    $hostId, $portNumber, $socketType, $sendHwm=100, $recvHwm=100
);
{% endhighlight %}

where:

* `$context` is a `ZMQContext` created using `usingZmqContext()->getZmqContext()`. If omitted, Storyplayer will create a new ZMQContext for this action.
* `$hostId` is the ID of the host in your test environment to connect to
* `$portNumber` is the port number that you want to connect to
* `$socketType` is one of the [supported ZMQ socket types](#supported-zmq-socket-types)
* `$sendHwm` sets the length of the internal queue for sending messages
* `$recvHwm` sets the length of the internal queue for receiving messages

## getZmqContext()

Use `usingZmqContext()->getZmqContext()` to create a new `ZMQContext` object to reuse when creating ZMQ sockets.

{% highlight php startinline %}
$context = usingZmqContext()->getZmqContext();
$sock1 = usingZmqContext($context)->connectToHost('default', 5000, 'PUSH');
$sock2 = usingZmqContext($context)->connectToHost('default', 5001, 'PULL')
{% endhighlight %}

where:

* `$context` gets set to a new `ZMQContext` object