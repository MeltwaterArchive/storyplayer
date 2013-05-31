---
layout: configuration
title: Per-User Configuration
prev: '<a href="../configuration/environment-config.html">Prev: Per-Environment Configuration</a>'
next: '<a href="../configuration/runtime-config.html">Next: The Runtime Configuration</a>'
---

# Per-User Configuration

Storyplayer supports an optional configuration file that lives in _$HOME/.storyplayer/storyplayer.json_, where you can put any Storyplayer settings that you want to apply across multiple test repositories.

## Why Do We Need Per-User Configuration?

As your application grows in size and complexity, you'll start to use Storyplayer to control other test tools, and you'll start to create virtual machines on demand to test your application inside.  The location of these additional tools - where they are on your computer - will be unique to your computer.  It makes sense to store these settings inside your own dotfiles rather than inside your test repository's config files.

For example, here at DataSift, many of our tests rely on:

* _our vagrant-environments project_, which contains our virtual machine images and provisioning files, and
* _Hornet_, our EvilLoadTestTool(tm)

It's more convenient for us if we use both _Hornet_ and the _vagrant-environments_ from their own Git repositories.

* Both of these tools are generic, so we don't need a unique copy inside each of our test repositories.
* Both of these tools are large (multi-gigabyte), so we don't want to clone a copy for every test repository that we create.
* Both tools get modified and extended to support new tests. By running them from their own Git repositories, we reduce the effort required by developers to commit and share these changes.

My per-user config file ends up looking like this:

{% highlight json %}
{
    "environments": {
        "defaults": {
            "hornet": {
                "path": "/home/stuart/Devel/datasift/ms-hornet/src/main/cli"
            },
            "vagrant": {
                "dir": "/home/stuart/Devel/datasift/vagrant-environments"
            }
        }
    }
}
{% endhighlight %}

Other uses for your storyplayer dotfile include:

* setting your own preferences for how verbose the output of the `storyplayer` command is
* temporarily disabling [test phases](test-phases.html) to help you speed up writing new tests