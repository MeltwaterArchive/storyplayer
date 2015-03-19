---
layout: v2/modules-host
title: The Host Module
prev: '<a href="../../modules/graphite/expectsGraphite.html">Prev: expectsGraphite()</a>'
next: '<a href="../../modules/host/fromHost.html">Next: fromHost()</a>'
updated_for_v2: true
---

# The Host Module

The __Host__ module allows you to inspect and run commands on the computers in your test environment.

The source code for this module can be found in these PHP classes:

* `Prose\ExpectsHost`
* `Prose\FromHost`
* `Prose\UsingHost`

## Dependencies

You need to install:

* [GNU Screen](http://www.gnu.org/software/screen/), a terminal emulator from [the Free Software Foundation](http://www.fsf.org/), and
* [bash](http://www.gnu.org/software/bash/), a popular UNIX shell

_bash_ is probably already installed on your computer. _screen_ may not be installed; you will need to install it by hand before you use this module.

<div class="callout warning" markdown="1">
#### OSX Users Need To Replace Apple's screen

Apple's OSX already includes `screen`. Unfortunately, it doesn't behave identically to the original GNU Screen. Please follow our [Setup Your Computer](../../learn/getting-setup/index.html) instructions and replace Apple's `screen` with the original.

What's different?

* When you close a GNU Screen, it automatically shuts down all child processes. Storyplayer relies heavily on this functionality to make sure that we do not leave rogue processes behind afterwards. Unfortunately, Apple's Screen does not shut down all child processes :(
</div>

## Using The Host Module

The basic format of an action is:

{% highlight php startinline %}
MODULE($hostName)->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromHost()](fromHost.html)_ - get information about a host
* _[expectsHost()](expectsHost.html)_ - test the state of a host
* _[usingHost()](usingHost.html)_ - perform actions on the host

and __action__ is one of the methods available on the __module__ you choose.