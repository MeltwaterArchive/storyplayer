---
layout: v2/using-configuration
title: User Dotfile
prev: '<a href="../../using/configuration/test-environment-config.html">Prev: Test Environment Configuration</a>'
next: '<a href="../../using/configuration/dot.notation.support.html">Next: dot.notation.support</a>'
updated_for_v2: true
---

# User Dotfile

Like many UNIX command-line applications, Storyplayer supports a global config file that you can use to override settings in your local config files.

This file is commonly used to host authentication keys for services such as Amazon AWS or SauceLabs.

## Location

Your user dotfile is the file `$HOME/.storyplayer/storyplayer.json`. This file is optional.

## Content

Your user dotfile is a JSON file. If it exists, it must define an object. The following sections are permitted:

{% highlight json %}
{
    "moduleSettings": { ... }
}
{% endhighlight %}

where:

* `moduleSettings` are [configuration settings for Storyplayer modules to use](module-settings.html)