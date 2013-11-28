---
layout: modules-zeromq
title: usingZmq()
prev: '<a href="../../modules/zeromq/expectsZmq.html">Prev: expectsZmq()</a>'
next: '<a href="../../changelog.html">Next: ChangeLog</a>'
---
# usingZmq()

_usingZmq()_ allows you to create ZeroMQ sockets, and to send and receive data over those sockets.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingZmq_.

## Behaviour And Return Codes

If the action succeeds, control is returned to your code.  __Most actions return a value__, which breaks the normal convention for a _usingXXXX()_ module, but which we feel makes sense in this specific case.

If the action fails, an exception is thrown. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## bind()

Use `$st->usingZmq()->bind()` to create a new ZeroMQ socket, and to bind it to a URI to listen for incoming connections.

{% highlight php %}
$sock = $st->usingZmq()->bind($address, $socketType);
{% endhighlight %}

where:

* `$address` is a URI that ZeroMQ supports (such as _tcp://\*:5000_)
* `$socketType` is one of the _ZMQ::SOCKET\_\*_ constants
* `$sock` is the `ZMQ_Socket` for you to send/receive on

__TIPS:__

* Do not hard-code addresses into your app. [Put them into your storyplayer config file](../../configuration/app-settings.html), and use _[fromEnvironment()->getAppSettings()](../environment/fromEnvironment.html#getappsettings)_ to use the config in your tests.

## connect()

Use `$st->usingZmq()->connect()` to create a new ZeroMQ socket, and to connect it to a (possibly remote) URI to send and receive ZeroMQ messages.

{% highlight php %}
$sock = $st->usingZmq()->connect($address, $socketType, [$sendHwm = 100, [$recvHwm = 100]]);
{% endhighlight %}

where:

* `$address` is a URI that ZeroMQ supports (such as _tcp://qa-test:5000_)
* `$socketType` is one of the _ZMQ::SOCKET\_\*_ constants
* `$sendHwm` is an _optional_ size for ZeroMQ's sending buffer (defaults to 100, not sure it's reliably implemented in the PHP extension)
* `$recvHwm` is an _optional_ size for ZeroMQ's receiving buffer (defaults to 100, not sure it's reliably implemented in the PHP extension)
* `$sock` is the `ZMQ_Socket` for you to send/receive on

__TIPS:__

* Do not hard-code addresses into your app. [Put them into your storyplayer config file](../../configuration/app-settings.html), and use _[fromEnvironment()->getAppSettings()](../environment/fromEnvironment.html#getappsettings)_ to use the config in your tests.

## recv()

Use `$st->usingZmq()->recv()` to receive a single-part message via a ZeroMQ socket previously created using _[bind()](#bind)_ or _[connect()](#connect)_.

{% highlight php %}
$message = $st->usingZmq()->recv($socket);
{% endhighlight %}

where:

* `$socket` is a `ZMQ_Socket` previously created using _[bind()](#bind)_ or _[connect()](#connect)_
* `$message` is the single-part message read from the `$socket`

As `$socket` is a genuine `ZMQ_Socket`, you could simply _recv()_ directly on the socket like this:

{% highlight php %}
$message = $socket->recv();
{% endhighlight %}

and it will work.  The reason we recommend using _usingZmq()->recv()_ et al instead of working directly with the `ZMQ_Socket` is because these wrapper methods write additional information to [the Storyplayer log](../../configuration/logging.html).  This information can be useful when attempting to understand why a test is failing or is hanging.

## recvMulti()

Use `$st->usingZmq()->recvMulti()` to receive a multi-part message via a ZeroMQ socket previously created using _[bind()](#bind)_ or _[connect()](#connect)_.

{% highlight php %}
$message = $st->usingZmq()->recvMulti($socket);
{% endhighlight %}

where:

* `$socket` is a `ZMQ_Socket` previously created using _[bind()](#bind)_ or _[connect()](#connect)_
* `$message` is the multi-part message read from the `$socket`

See _[usingZmq()->recv()](#recv)_ for a discussion about why we recommend using these wrapper methods instead of simply working directly with the `ZMQ_Socket`.

## recvMultiNonBlocking()

Use `$st->usingZmq()->recvMultiNonBlocking()` to receive a multi-part message via a ZeroMQ socket previously created using _[bind()](#bind)_ or _[connect()](#connect)_.  If the socket would have blocked, `NULL` is returned to the caller.

{% highlight php %}
$message = $st->usingZmq()->recvMultiNonBlocking($socket);
{% endhighlight %}

where:

* `$socket` is a `ZMQ_Socket` previously created using _[bind()](#bind)_ or _[connect()](#connect)_
* `$message` is the multi-part message read from the `$socket`, or NULL if the socket would have blocked

See _[usingZmq()->recv()](#recv)_ for a discussion about why we recommend using these wrapper methods instead of simply working directly with the `ZMQ_Socket`.

## recvNonBlocking()

Use `$st->usingZmq()->recvNonBlocking()` to receive a single-part message via a ZeroMQ socket previously created using _[bind()](#bind)_ or _[connect()](#connect)_.  If the socket would have blocked, `NULL` is returned to the caller.

{% highlight php %}
$message = $st->usingZmq()->recvNonBlocking($socket);
{% endhighlight %}

where:

* `$socket` is a `ZMQ_Socket` previously created using _[bind()](#bind)_ or _[connect()](#connect)_
* `$message` is the single-part message read from the `$socket`, or NULL if the socket would have blocked

See _[usingZmq()->recv()](#recv)_ for a discussion about why we recommend using these wrapper methods instead of simply working directly with the `ZMQ_Socket`.

## send()

Use `$st->usingZmq()->send()` to send a single-part message via a ZeroMQ socket previously created using _[bind()](#bind)_ or _[connect()](#connect)_.

{% highlight php %}
$st->usingZmq()->send($socket, $message);
{% endhighlight %}

where:

* `$socket` is a `ZMQ_Socket` previously created using _[bind()](#bind)_ or _[connect()](#connect)_
* `$message` is the single-part message to be send via the `$socket`

See _[usingZmq()->recv()](#recv)_ for a discussion about why we recommend using these wrapper methods instead of simply working directly with the `ZMQ_Socket`.

## sendMulti()

Use `$st->usingZmq()->send()` to send a multi-part message via a ZeroMQ socket previously created using _[bind()](#bind)_ or _[connect()](#connect)_.

{% highlight php %}
$st->usingZmq()->sendMulti($socket, $message);
{% endhighlight %}

where:

* `$socket` is a `ZMQ_Socket` previously created using _[bind()](#bind)_ or _[connect()](#connect)_
* `$message` is the multi-part message to be send via the `$socket`

See _[usingZmq()->recv()](#recv)_ for a discussion about why we recommend using these wrapper methods instead of simply working directly with the `ZMQ_Socket`.
