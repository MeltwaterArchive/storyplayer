---
layout: v2/using-configuration
title: System Under Test Configuration
next: '<a href="../../using/configuration/test-environment-config.html">Next: Test Environment Configuration</a>'
prev: '<a href="../../using/configuration/module-settings.html">Prev: moduleSettings Section</a>'
updated_for_v2: true
---

# System Under Test Configuration

The _system-under-test config file_ contains settings for a _specific version_ of the software / service / platform that you are testing.

Create one file for each version of whatever you are testing. When you start testing a new version, create an additional _system-under-test config file_ for that new version. That way, you can easily switch back to testing any old version at any time.

Use the `-s` command-line switch to tell Storyplayer which _system-under-test config file_ to load when you run your tests:

{% highlight bash %}
vendor/bin/storyplayer -s storyplayer-2.x
{% endhighlight %}

## Location

Your _system-under-test config file_ goes inside the `storyplayer/systems-under-test` folder. (__Note__ that the folder is 'systems' plural, not 'system'!)

There is one _system-under-test config file_ for each version of the software / service / platform that you are testing.

<pre>
project-root-folder/
|- storyplayer/
   |- systems-under-test/
      | &lt;system-under-test-name&gt;-&lt;version&gt;.json
</pre>

For example, Storyplayer ships with these files for testing itself:

* `storyplayer/systems-under-test/storyplayer-2.2.0.json` - config file for testing the v2.2.0 release
* `storyplayer/systems-under-test/storyplayer-2.x.json` - config file for testing the `develop` branch

## Contents

Each _system-under-test config file_ is a JSON file. It must define an object. The following sections are permitted:

{% highlight json %}
{
    "storySettings": { ... },
    "moduleSettings": { ... },
    "roles": [ ... ]
}
{% endhighlight %}

where:

* `storySettings` are [configuration settings for your stories to use](story-settings.html)
* `moduleSettings` are [configuration settings for Storyplayer modules to use](module-settings.html)
* `roles` are settings to inject into your test environment's config

__Each section is optional.__

Start with a file that defines an empty object:

{% highlight json %}
{}
{% endhighlight %}

and add settings as your test suite grows.

## The roles Section

The `roles` section provides you with a way to inject provisioning parameters (or `params` for short) into your test environment.

{% highlight json %}
{
    "roles": [
        {
            "role": "<role-name>",
            "params": {
                "<param1>": <param-value>,
                "<param2>": <param-value>,
                ...
            }
        }
    ]
}
{% endhighlight %}

`roles` is an array of JSON objects. Each object contains two attributes:

* `role` is the name of the test-environment role that you want to inject one or more params into
* `params` is an object containing a list of the params you want to inject

Any `params` that you specify here are added to your test environment config before Storyplayer runs your chosen provisioning engine.