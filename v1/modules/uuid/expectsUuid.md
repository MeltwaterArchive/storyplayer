---
layout: v1/modules-uuid
title: expectsUuid()
prev: '<a href="../../modules/uuid/fromUuid.html">Prev: fromUuid()</a>'
next: '<a href="../../modules/vagrant/index.html">Next: The Vagrant Module</a>'
---

# expectsUuid()

_expectsUuid()_ allows you to test UUID strings and the dependencies for the UUID module.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\ExpectsUuid_.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## requirementsAreMet()

Use `$st->expectsUuid()->requirementsAreMet()` to make sure that any calls to the UUID module will work.

{% highlight php %}
$st->expectsUuid()->requirementsAreMet();
{% endhighlight %}

This will throw an exception if any of the required dependencies are not available.

For maximum effect, use this in any of the following phases:

* [test setup](../../stories/test-setup-teardown.html) (recommended), or
* [pre-test inspection](../../stories/pre-test-inspection.html)
