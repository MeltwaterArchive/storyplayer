---
layout: v2/configuration
title: Per-Environment Configuration
next: '<a href="../configuration/device-config.html">Next: Per-Device Configuration</a>'
prev: '<a href="../configuration/test-phases.html">Prev: Test Phases Configuration</a>'
---

# Per-Environment Configuration

When you run the `storyplayer` command-line tool, the first parameter that you pass to it is the name of the environment that you want to run your tests against.  This environment __must__ have an entry in the _environments_ section of your config files.  You can put this section inside the main [storyplayer.json](storyplayer-json.html) file, or you can [break it out into a per-environment config file](#advanced_configuration).

## Basic Configuration

The most basic way to configure an environment is to add an entry to your [storyplayer.json](storyplayer-json.html) config file:

{% highlight json %}
{
    "environments": {
        "defaults": {
            ...
        },
        "production": {
            ...
        }
    }
}
{% endhighlight %}

In this example, we have a [default section for app settings](app-settings.html), and a section for the _production_ environment.

If you're happy with the defaults, and don't need to override them at all, then just make the entry for each environment an empty object:

{% highlight json %}
{
    "environments": {
        "defaults": {
            ...
        },
        "production": {}
    }
}
{% endhighlight %}

## Advanced Configuration

As your test suite grows, and you add more apps to test, and there are more people running the tests, it can be very helpful to move the config for each environment out into its own file.

For example, here at DataSift, if I look in one of our test repositories, I see a list of config files like this:

* _storyplayer.json_ - the main config file, containing the default app settings
* _etc/production.json_ - some overrides that allow us to run our tests against our production environment
* _etc/staging.json_ - some overrides that allow us to run our tests against a shared copy of the next version of DataSift
* _etc/stu-office.json_ - some overrides that I use when I run tests against my own copy of the next version of DataSift
* _etc/stu-home.json_ - some overrides that I use when I want to run tests on an evening on my machine at home

Other engineers in the company also have their own config files too.

Splitting things up like this means that each of us can tailor the app settings to suit our own copies of DataSift, and not worry about breaking anything that anyone else is doing at the time.  It makes code reviews easier too, because we can easily see if someone has changed a config file that they should really have left alone :)

## Naming Your Configuration File

If you are testing against code running on your local computer, use the `show-local-environment` to find out which name Storyplayer recommends for your local config file:

<pre>
$ vendor/bin/storyplayer show-local-environment
qa-air-2
</pre>

If you create a per-environment config file with the same name (e.g. `etc/qa-air-2.json`), then Storyplayer will use this file by default in future.  This saves you having to pass in the `-e` switch when you run Storyplayer.  Once you've created the file, you can verify that Storyplayer will use this by using the `show-default-environment` command:

<pre>
$ vendor/bin/storyplayer show-default-environment
qa-air-2
</pre>

## Each File Is A Complete Configuration File

__Please note__ that each per-environment config file is a _complete_ config file for Storyplayer, and that you need to put your config inside an _environments_ section like this:

{% highlight json %}
{
    "environments": {
        "production": {
            ...
        }
    }
}
{% endhighlight %}

Yes, this does mean that your per-environment config file can override other config sections too if you want to (such as [logging](logging.html) or [test execution phases](test-phases.html)).  We recommend that you stick to just overriding the app settings for your environment, but the extra flexibility is there if you ever need it.

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
