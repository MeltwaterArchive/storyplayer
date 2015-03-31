---
layout: v2/using-configuration
title: "The storyplayer.json File"
next: '<a href="../../using/configuration/story-settings.html">Next: storySettings Section</a>'
prev: '<a href="../../using/configuration/index.html">Prev: Configuring Storyplayer</a>'
updated_for_v2: true
---

# The storyplayer.json File

`storyplayer.json` is the main configuration file for your tests.  You should place `storyplayer.json` in the top-level folder of the repository containing your tests.

## Contents

`storyplayer.json` is a JSON file. It must define an object. The following sections are permitted:

{% highlight json %}
{
    "defaults": [ ],
    "storySettings": { },
    "moduleSettings": { }
}
{% endhighlight %}

where:

* `defaults` is a list of the default command-line arguments (discussed in a moment)
* `storySettings` are [configuration settings for your stories to use](story-settings.html)
* `moduleSettings` are [configuration settings for Storyplayer modules to use](module-settings.html)

__All sections are optional.__  You are encouraged to always have a `defaults` section, simply to make it easier for other people to run your tests.

## The defaults Section

`defaults` is an array of strings. It contains the command-line arguments for Storyplayer to use. Put one argument per string.  For example:

{% highlight json %}
{
    "defaults": [
        "--system-under-test", "storyplayer-2.x",
        "--target", "vagrant-centos6-ssl",
        "--users", "src/tests/stories/default-users.json",
        "play-story", "src/tests/stories/"
    ]
}
{% endhighlight %}

When you run Storyplayer with no command-line arguments:

{% highlight bash %}
vendor/bin/storyplayer
{% endhighlight %}

it is exactly the same as running Storyplayer with all of the arguments listed in the `defaults` section:

{% highlight bash %}
vendor/bin/storyplayer --system-under-test storyplayer-2.x --target vagrant-centos6-ssl --users src/tests/stories/default-users.json play-story src/tests/stories/
{% endhighlight %}

It's always a good idea to add a `defaults` section, so that anyone can run your tests without having to know what arguments are needed.

### Notes

* `defaults` is optional. It can be an empty array. It can be left out entirely.

## The storyplayer.json.dist File

If Storyplayer cannot find a `storyplayer.json` file, it will look for a file called `storyplayer.json.dist`.  This is the same approach that PHPUnit uses with its `phpunit.xml` config file.

Name your config file as `storyplayer.json.dist` when you add it to version control. If anyone wants their own settings instead, they can just write their own `storyplayer.json` file - they don't have to edit the `storyplayer.json.dist` file.

### Notes

* Storyplayer will never load `storyplayer.json.dist` if there is a `storyplayer.json` file.