---
layout: v2/modules-zeromq
title: The ZeroMQ Module
prev: '<a href="../../modules/vagrant/expectsVagrant.html">Prev: expectsVagrant()</a>'
next: '<a href="../../modules/zeromq/usingZmqContext.html">Next: usingZmqContext()</a>'
updated_for_v2: true
---

# The ZeroMQ Module

The __ZeroMQ__ module allows you to send and receive messages via the lightweight [ZeroMQ](http://www.zeromq.org/) socket-level messaging layer.

The source code for this module can be found in these PHP classes:

* `Prose\UsingZmqContext`
* `Prose\ExpectsZmqSocket`
* `Prose\FromZmqSocket`
* `Prose\UsingZmqSocket`

<div class="callout info" markdown="1">
#### A New API For Storyplayer v2

For Storyplayer v2, we've created a new ZeroMQ module which is much clearer to understand. We recommend that all new stories use the new ZeroMQ module.

If you're looking for the original module from Storyplayer v1, this is now documented as the _[ZMQ module](../zmq/index.html)_. We've no plans to drop the original module.
</div>

## Sometimes An External Messaging Tool Is Best

ZeroMQ is typically used in two main ways:

* 'Command' interfaces and
* 'Data' pipelines

Depending on what you want to test, you might be better off creating a custom test client that you run using the [UNIX Shell](../shell/index.html) module.

* If your test involves sending a small number of message to a service deployed inside your test environment, then this module is perfect for your needs.
* But ... if your test involves sending a large number of messages to that service - i.e. you're testing that the data pipeline works as required - then a custom test client often produces the better test.  Create a client that pumps data into the data pipeline, and a second client that pulls data out the other end, and control them both from Storyplayer.

## Dependencies

You need to install:

* ZeroMQ (we recommend version 4.0 or later)
* The PHP extension for ZeroMQ

## Using The ZeroMQ Module

The basic pattern for using the ZeroMQ module is:

1. Create a ZeroMQ socket that either binds or connects as appropriate.
1. Use that socket to send or receive single or multi-part messages.

Note that we don't close the socket afterwards ... at the time of writing, the PHP extension for ZeroMQ doesn't support closing and destroying sockets :(

Here's an example:

{% highlight php startinline %}
$story->addAction(function() {
    // if there are multiple queues, we only need to write to one of them
    foreach(firstHostWithRole("acme_queue") as $hostId) {
        // get the ports that the ACME queue uses
        $inPort     = fromHost($hostId)->getAppSetting('acme_queue.zmq.in');
        $outPort    = fromHost($hostId)->getAppSetting('acme_queue.zmq.out');

        // use a shared context for our ZMQ sockets
        $context = usingZmqContext()->getZmqContext();

        // create our sending socket
        $sendSock = usingZmqContext($context)->connectToHost($hostId, $inPort, 'PUSH');

        // send a command to the ACME queue
        // $message is an array to send as a multipart message
        usingZmqSocket($sendSock)->sendMulti($message);

        // create our receiving socket
        $recvSock = usingZmqContext($context)->connectToHost($hostId, $outPort, 'PULL');

        // get the response back from the ACME queue
        $response = fromZmqSocket($recvSock)->recvMulti();

        // store the response for later inspection
        $checkpoint = getCheckpoint();
        $checkpoint->response = $response;
    }
});
{% endhighlight %}

## Storyplayer Does Not Use Lazy Connects

ZeroMQ's default behaviour of _lazy connect_ does not fit well with PHP applications. If the socket never successfully connects, libzmq continues to attempt to connect in the background and prevents PHP from exiting. This means that, if your test fails, you can end up with a Storyplayer process that never exits.

Storyplayer avoids this problem by disabling ZeroMQ's _lazy connect_ strategy. When you attempt to `connect()` to a (possibly) remote socket, Storyplayer tells ZeroMQ to return straight away if the `connect()` fails.

## ZeroMQ Sockets And Timeouts

ZeroMQ sockets have internal queues for holding messages that are waiting to be sent, or that have been received from the network but not yet read by the application.

These send and receive queues have a maximum length, known as the _high water mark_. Storyplayer defaults to each of these queues being 100 messages in length. You can override these defaults when you create your ZMQ sockets:

{% highlight php startinline %}
// create a socket with an send queue of 200 messages,
// and a receive queue of 500 messages
$socket = usingZmqContext($context)->connectToHost($hostId, $port, 'tcp', 200, 500);
{% endhighlight %}

When you `send()` or `sendMulti()` a message, you're actually asking ZeroMQ to put your message onto the socket's send queue. `send()` and `sendMulti()` will block until there is space on the send queue for your message.

That means, when the `send()` or `sendMulti()` method call returns, there's no guarantee that your message has made it to the receiving application yet.

To avoid `send()` and `sendMulti()` blocking forever, use the `$timeout` parameter. `$timeout` is the number of seconds to wait for there to be space on the send queue before giving up.
