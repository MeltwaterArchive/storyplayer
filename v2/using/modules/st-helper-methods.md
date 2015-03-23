---
layout: v2/using-modules
title: $st Helper Methods
prev: '<a href="../../using/modules/the-st-object.html">Prev: The $st Object</a>'
next: '<a href="../../using/modules/module-loading.html">Next: Module Loading</a>'
---

# $st Helper Methods

The `$st` object provides the following methods for you to use inside your own stories and modules.

## getCheckpoint()

Use `$st->getCheckpoint()` to retrieve the [checkpoint object](../stories/the-checkpoint.html).

{% highlight php startinline %}
$checkpoint = $st->getCheckpoint();
{% endhighlight %}

The checkpoint is used to store data between [story phases](../stories/phases.html).

Where can you use `getCheckpoint()`?

* Use in stories? Yes
* Use in modules? Yes

## getParams()

Use `$st->getParams()` to get a list of the [params](../stories/story-params.html) passed into this story.

{% highlight php startinline %}
$params = $st->getParams();
{% endhighlight %}

Where can you use `getParams()`?

* Use in stories? Yes
* Use in modules? Yes, but not advised

## startAction()

Use `$st->startAction()` to retrieve the `$log` object that is used for [logging about the actions of your module](adding-logging.html).

{% highlight php startinline %}
$log = $st->startAction($msg);
{% endhighlight %}

where:

* _$msg_ is a text string to say what action your module's method call is about to perform

Where can you use `startAction()`?

* Use in stories? No
* Use in modules? Yes