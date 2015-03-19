---
layout: v2/modules-supervisor
title: The Supervisor Module
prev: '<a href="../../modules/storyplayer/fromStoryplayer.html">Prev: fromStoryplayer()</a>'
next: '<a href="../../modules/supervisor/fromSupervisor.html">Next: fromSupervisor()</a>'
updated_for_v2: true
---

# The Supervisor Module

The __Supervisor__ module allows you to start and stop processes that are being managed by [Supervisor](http://supervisord.org).

The source code for this Storyplayer module can be found in these PHP classes:

* `Prose\ExpectsSupervisor`
* `Prose\FromSupervisor`
* `Prose\UsingSupervisor`

## Dependencies

See the [Host module](../host/index.html) for dependencies.

## Using The Supervisor Module

The basic format of an action is:

{% highlight php startinline %}
MODULE()->ACTION();
{% endhighlight %}

where __module__ is one of:

* _[fromSupervisor()](fromSupervisor.html)_ - get information about processes that are managed by Supervisor
* _[expectsSupervisor()](expectsSupervisor.html)_ - test processes that are managed by Supervisor
* _[usingSupervisor()](usingSupervisor.html)_ - start and stop processes that are managed by Supervisor

and __action__ is one of the documented actions available from __module__.