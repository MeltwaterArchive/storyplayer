---
layout: v2/using-configuration
title: Per-Device Configuration
prev: '<a href="../../using/configuration/system-under-test-config.html">Prev: System Under Test Configuration</a>'
next: '<a href="../../using/configuration/user-config.html">Next: Per-User Configuration</a>'
---

# Per-Device Configuration

When you run the `storyplayer` command-line tool, you can use the `-d` switch to tell Storyplayer which web browser (or other UI device) it should use in your test.  Storyplayer ships with a number of devices already pre-configured (such as [local web browsers](../devices/localbrowsers.html) and [a wider range of browsers available via Sauce Labs](../devices/saucelabs.html)).

You can also define your own devices, which we cover in this section of the manual.  You can define more devices inside the main [storyplayer.json](storyplayer-json.html) file, or you can [break them out into per-device config files](#advanced_configuration).

## Basic Configuration

The most basic way to configure a device is to add an entry to your [storyplayer.json](storyplayer-json.html) config file:

{% highlight json %}
{
    "devices": {
        "<device-name>": {
           ...
        }
    }
}
{% endhighlight %}

where:

* _device-name_ is what you'd use with the `-d` switch

## Advanced Configuration

As your test suite grows, and you add more apps to test, and there are more people running the tests, it can be very helpful to move the config for each device out into its own file.  Splitting things up like this makes code reviews easier too, because you can easily see if someone has changed a config file that they should really have left alone :)

__Please note__ that each per-device config file is a _complete_ config file for Storyplayer, and that you need to put your config inside an _devices_ section like this:

{% highlight json %}
{
    "devices": {
        "<device-name>": {
            ...
        }
    }
}
{% endhighlight %}

Yes, this does mean that your per-device config file can override other config sections too if you want to (such as [logging](logging.html) or [test execution phases](test-phases.html)).  We recommend that you stick to just defining a single device, but the extra flexibility is there if you ever need it.

## Which Way Should I Use?

Storyplayer's configuration flexibility can seem confusing when you first start using it.  We hope this advice helps you get started as quickly as possible.

__We recommend using the basic configuration__ (putting all the environments in your main [storyplayer.json](storyplayer-json.html) file):

* for any projects where the Storyplayer tests live in the same source code repository as the app's source code and unit tests

__We recommend using the advanced configuration__ (putting the [default app settings](app-settings.html) into your main [storyplayer.json](storyplayer-json.html) file, and the per-environment overrides into one config file per environment):

* when you have one communial repository containing all the tests for all of your applications

Finally, please be aware that you can mix and match the two approaches, putting some environments in your main storyplayer.json file, and putting other environments into their own config files.

## How Storyplayer Merges The Environment Configurations

Storyplayer's algorithm for loading and combining all of these config files should always leave your tests with a final config that is always exactly what you expect it to be.

Here's how the overriding works under the hood:

1. Storyplayer starts with a completely empty _environments_ section in the config.
1. Storyplayer loads _storyplayer.json_, the main config file, from your tests repository.
1. Storyplayer loads any existing per-user config file, and merges it into the config it has already loaded.
1. Storyplayer loads any per-environment config file, and merges that into the config that it has already loaded.

At this point, Storyplayer will have a single config object tree, containing a _environments->defaults_ section with your default settings, and separate _environments->\*_ sections for each of the environments you've listed in your config files.

Storyplayer then creates an internal _environment_ object to hold the final config for the environment that you're testing against, and populates that object as follows:

1. Storyplayer copies the _environments->defaults_ section from the loaded config into the _envrionment_ object
1. Storyplayer looks in the loaded config, finds the section for the environment that you're testing against, and merges that into the _environment_ object

The end result is a single _environment_ object (which you can always get at using _$st->getEnvironment()_) which contains all of your default settings, and any configuration overrides or additions that you've applied.
