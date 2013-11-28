---
layout: modules-zeromq
title: The ZeroMQ Module
prev: '<a href="../../modules/vagrant/expectsVagrant.html">Prev: expectsVagrant()</a>'
next: '<a href="../../modules/zeromq/expectsZmq.html">Next: expectsZmq()</a>'
---

# The ZeroMQ Module

The __ZeroMQ__ module allows you to send and receive messages via the lightweight [ZeroMQ](http://www.zeromq.org/) socket-level messaging layer.

The source code for this Prose module can be found in these PHP classes:

* DataSift\Storypayer\Prose\ExpectsZmq
* DataSift\Storypayer\Prose\UsingZmq

## Sometimes An External Messaging Tool Is Best

ZeroMQ is typically used in two main ways:

* 'Command' interfaces and
* 'Data' pipelines

Depending on what you want to test, you might be better off creating a custom test client that you run using the [UNIX Shell](../shell/index.html) module.

* If your test involves sending a small number of message to a service deployed inside a [Vagrant virtual machine](../vagrant/index.html), then this module is perfect for your needs.
* But ... if your test involves sending a large number of messages to that service - i.e. you're testing that the data pipeline works as required - then a custom test client often produces the better test.  Create a client that pumps data into the data pipeline, and a second client that pulls data out the other end, and control them both from Storyplayer.

## Dependencies

You need to install:

* ZeroMQ (we recommend version 3.2 or later)
* The PHP extension for ZeroMQ

## Using The ZeroMQ Module

The basic pattern for using the ZeroMQ module is:

1. Create a ZeroMQ socket that either binds or connects as appropriate.
1. Use that socket to send or receive single or multi-part messages.

Note that we don't close the socket afterwards ... at the time of writing, the PHP extension for ZeroMQ doesn't support closing and destroying sockets :(

Here's an example:

{% highlight php %}
// make it explicit that we're using a class from the global namespace
use ZMQ;

$story->addAction(function(StoryTeller $st) {
	// get the ports that Ogre uses
	$appDetails = $st->fromEnvironment()->getAppSettings('ogre');

	// create our sending socket
	$sendSock = $st->usingZmq()->connect($appDetails->command_socket, ZMQ::SOCKET_PUSH);

	// send a command to Ogre
	// $message is an array to send as a multipart message
	$st->usingZmq()->sendMulti($sendSock, $message);

	// create our receiving socket
	$recvSock = $st->usingZmq()->connect($appDetails->response_socket, ZMQ::SOCKET_PULL);

	// get the response back from Ogre
	$response = $st->usingZmq()->recvMulti($recvSocket);

	// store the response for later inspection
	$checkpoint = $st->getCheckpoint();
	$checkpoint->response = $response;
});
{% endhighlight %}