---
layout: v2/modules-zeromq
title: usingZmqSocket()
prev: '<a href="../../modules/zeromq/fromZmqSocket.html">Prev: fromZmqSocket()</a>'
next: '<a href="../../modules/zmq/index.html">Next: The ZeroMQ Module</a>'
updated_for_v2: true
---
# usingZmqSocket()

_usingZmqSocket()_ allows you to send data over a ZeroMQ socket. You can also use it to connect your ZeroMQ socket to more endpoints, or to disconnect from current endpoints.

The source code for these actions can be found in the class `Prose\UsingZmqSocket`.

## Behaviour And Return Codes

If the action succeeds, control is returned to your code.  __Most actions return a value__, which breaks the normal convention for a _usingXXXX()_ module, but which we feel makes sense in this specific case.

If the action fails, an exception is thrown. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## bindToPort()

Use `usingZmqSocket()->bindToPort()` to bind to a port on `localhost` to receive ZMQ connections.

{% highlight php startinline %}
usingZmqSocket($socket)->bindToPort($portNumber, $sendHwm=100, $recvHwm=100);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$portNumber` is the port number that you want to bind to
* `$sendHwm` sets the length of the internal queue for sending messages
* `$recvHwm` sets the length of the internal queue for receiving messages

## close()

Use `usingZmqSocket()->close()` to close all open bound ports and connections.

{% highlight php startinline %}
usingZmqSocket($socket)->close();
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`

__NOTES__:

* This is the equivalent of calling `usingZmqSocket()->unbindAllPorts()` followed by `usingZmqSocket()->disconnectFromAllHosts()`
* It is safe to call this action if there are no ports currently connected

## connectToHost()

Use `usingZmqSocket()->connectToHost()` to connect to a port on a host in your test environment.

{% highlight php startinline %}
usingZmqSocket($socket)->connectToHost($hostId, $portNumber, $sendHwm=100, $recvHwm=100);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$hostId` is the ID of the host in your test environment to connect to
* `$portNumber` is the port number that you want to connect to
* `$sendHwm` sets the length of the internal queue for sending messages
* `$recvHwm` sets the length of the internal queue for receiving messages

## disconnectFromAllHosts()

Use `usingZmqSocket()->disconnectFromAllHosts()` to disconnect from all machines in your test environment that you previously connected to using _[usingZmqContext()->connectToHost()](usingZmqContext.html#connectToHost)_.

{% highlight php startinline %}
usingZmqSocket($socket)->disconnectFromAllHosts();
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`

__NOTES__:

* It is safe to call this action if there are no ports currently connected

## disconnectFromHost()

Use `usingZmqSocket()->disconnectFromHost()` to disconnect from a machine in your test environment that you previously connected to using _[usingZmqContext()->connectToHost()](usingZmqContext.html#connectToHost)_.

{% highlight php startinline %}
usingZmqSocket($socket)->disconnectFromHost($hostId, $portNumber);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$hostId` is the ID of the host in your test environment you previously connected to
* `$portNumber` is the port number that you want to disconnect from

## send()

Use `usingZmqSocket()->send()` to send a single-part message via a ZeroMQ socket previously created using _[usingZmqContext()->bindToPort()](usingZmqContext.html#bindToPort)_ or _[usingZmqContext()->connectToHost()](usingZmqContext.html#connectToHost)_.

{% highlight php startinline %}
usingZmqSocket($socket)->send($message, $timeout=5);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$message` is the single-part message to be send via the `$socket`
* `$timeout` is how long to wait (in seconds) to send a message (default is to wait up to 5 seconds)

## sendMulti()

Use `usingZmqSocket()->sendMulti()` to send a multi-part message via a ZeroMQ socket previously created using _[usingZmqContext()->bindToPort()](usingZmqContext.html#bindToPort)_ or _[usingZmqContext()->connectToHost()](usingZmqContext.html#connectToHost)_.

{% highlight php startinline %}
usingZmqSocket($socket)->sendMulti($message, $timeout=5);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$message` is the multi-part message to be send via the `$socket`
* `$timeout` is how long to wait (in seconds) to send a message (default is to wait up to 5 seconds)

## sendMultiNonBlocking()

Use `usingZmqSocket()->sendMultiNonBlocking()` to send a multi-part message via a ZeroMQ socket previously created using _[usingZmqContext()->bindToPort()](usingZmqContext.html#bindToPort)_ or _[usingZmqContext()->connectToHost()](usingZmqContext.html#connectToHost)_. This call will return straight away, and return a boolean to indicate whether the message was sent or not.

{% highlight php startinline %}
$sent = usingZmqSocket($socket)->sendMultiNonBlocking($message);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$message` is the multi-part message to be send via the `$socket`
* `$sent` gets set to TRUE if the message was sent, FALSE otherwise

## sendNonBlocking()

Use `usingZmqSocket()->sendNonBlocking()` to send a single-part message via a ZeroMQ socket previously created using _[usingZmqContext()->bindToPort()](usingZmqContext.html#bindToPort)_ or _[usingZmqContext()->connectToHost()](usingZmqContext.html#connectToHost)_. This call will return straight away, and return a boolean to indicate whether the message was sent or not.

{% highlight php startinline %}
$sent = usingZmqSocket($socket)->sendNonBlocking($message);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$message` is the single-part message to be send via the `$socket`
* `$sent` gets set to TRUE if the message was sent, FALSE otherwise

## unbindFromAllPorts()

Use `usingZmqSocket()->unbindFromAllPorts()` to close all ports you've previously opened using _[usingZmqContext()->bindToPort()](usingZmqContext.html#bindToPort)_.

{% highlight php startinline %}
usingZmqSocket($socket)->unbindFromAllPorts();
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`

__NOTES__:

* It is safe to call this action if there are no ports currently open

## unbindFromPort()

Use `usingZmqSocket()->unbindFromPort()` to close a port that you've previously opened using _[usingZmqContext()->bindToPort()](usingZmqContext.html#bindToPort)_.

{% highlight php startinline %}
usingZmqSocket($socket)->unbindFromPort($portNumber);
{% endhighlight %}

where:

* `$socket` is a `ZMQSocket`
* `$portNumber` is the port number that you previously bound to