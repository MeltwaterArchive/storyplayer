---
layout: v2/prose
title: Module Loading
prev: '<a href="../prose/st-helper-methods.html">Prev: $st Helper Methods</a>'
next: '<a href="../prose/module-namespaces.html">Next: Module Namespaces</a>'
---

# Module Loading

One of the `$st` object's main roles is to dynamically load Prose modules as your test tries to use them.

## Module Mapping

When you use the `$st` object in your tests, every method call that you make is mapped to a class inside a Prose module like this:

{% highlight php %}
# maps to Prose\UsingModule::doX()
$st->usingModule()->doX();

# maps to Prose\FromModule::getY()
$y = $st->fromModule()->getY();

# maps to Prose\ExpectsModule::someTest()
$st->expectsModule()->someTest();

# maps to Prose\AssertsModule::someTest()
$st->assertsModule()->someTest();
{% endhighlight %}

## How $st Loads Modules

Each of these method calls against the `$st` object are faked methods.

When you call any of these, the `$st` object's `__call()` method gets called, and the following happens:

1. `$st` takes the name of the method you've called, and uppercases the first letter. This is the name of the class to load.
2. `$st` searches [a list of namespaces](module-namespaces.html) for the class to load.
3. `$st` creates a new object from the found class, and checks that it extends the `Prose` class
4. `$st` returns that object to the caller.

For example, the code `$st->usingBrowser()` is handled like this:

1. `$st` takes the method name `usingBrowser`, and turns it into the string `UsingBrowser`
2. `$st` searches [a list of namespaces](module-namespaces.html) for the class `UsingBrowser`, and finds the class in the `DataSift\Storyplayer\Prose` namespace
3. `$st` creates a new object from the class `DataSift\Storyplayer\Prose\UsingBrowser`, and checks that it extends the `Prose` class
4. `$st` returns that object to the caller.
