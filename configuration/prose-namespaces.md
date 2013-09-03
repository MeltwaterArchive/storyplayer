---
layout: configuration
title: Prose Namespaces
prev: '<a href="../configuration/user-config.html">Prev: Per-User Configuration</a>'
next: '<a href="../configuration/runtime-config.html">Next: The Runtime Configuration</a>'
---
# Prose Namespaces

Storyplayer can be configured to load [Prose modules](../prose/index.html) from additional places.

## Default Behaviour

Out of the box, Storyplayer's `$st` dynamic module loader is configured to automatically search for Prose modules in the following PHP namespaces:

* Prose
* DataSift\Storyplayer\Prose

[The full details are in the Prose section of this manual.](../prose/module-namespaces.html)

## Searching Additional Namespaces

To search additional namespaces for Prose modules, add your namespace(s) to the `prose->namespaces` section of your Storyplayer config file:

{% highlight json %}
{
    "prose": {
        "namespaces": [
        ]
    }
}
{% endhighlight %}

