---
layout: v2/modules-zmq
title: The ZMQ Module
prev: '<a href="../../modules/zeromq/usingZmqSocket.html">Prev: usingZmqSocket()</a>'
next: '<a href="../../modules/zmq/expectsZmq.html">Next: expectsZmq()</a>'
updated_for_v2: true
---

# The ZMQ Module

The __ZMQ__ module allows you to send and receive messages via the lightweight [ZeroMQ](http://www.zeromq.org/) socket-level messaging layer.

The source code for this module can be found in these PHP classes:

* `Prose\ExpectsZmq`
* `Prose\UsingZmq`

<div class="callout info" markdown="1">
#### A Lower-Level Module

This is the ZeroMQ module originally created for Storyplayer v1. It was created before Storyplayer supported test environments. The new [ZeroMQ module](../zeromq/index.html) is much easier to use.

We recommend only using this module if you're already using it in existing tests.

We've no plans to get rid of this module. This module is heavily in use inside DataSift. We can't make any changes that will break backwards compatibility.
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
        $ipAddress  = fromHost($hostId)->getIpAddress();
        $inPort     = fromHost($hostId)->getAppSetting('acme_queue.zmq.in');
        $outPort    = fromHost($hostId)->getAppSetting('acme_queue.zmq.out');

        // create our sending socket
        $sendSock = usingZmq()->connect("tcp://{$ipAddress}:{$inPort}", ZMQ::SOCKET_PUSH);

        // send a command to the ACME queue
        // $message is an array to send as a multipart message
        usingZmq()->sendMulti($sendSock, $message);

        // create our receiving socket
        $recvSock = usingZmq()->connect("tcp://{$ipAddress}:{$outPort}", ZMQ::SOCKET_PULL);

        // get the response back from the ACME queue
        $response = usingZmq()->recvMulti($recvSocket);

        // store the response for later inspection
        $checkpoint = getCheckpoint();
        $checkpoint->response = $response;
    }
});
{% endhighlight %}