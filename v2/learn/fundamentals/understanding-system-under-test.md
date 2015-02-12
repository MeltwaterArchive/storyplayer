---
layout: v2/learn-fundamentals
title: Understanding The System Under Test
prev: '<a href="../../learn/fundamentals/service-stories.html">Prev: Service Stories</a>'
next: '<a href="../../learn/fundamentals/understanding-test-environments.html">Next: Understanding Test Environments</a>'
---
# Understanding The System Under Test

Storyplayer runs [stories](understanding-stories.html) that test a specific [system under test](understanding-system-under-test.html) running inside a specific [test environment](understanding-test-environments.html).

Support for _systems under test_ is a new feature for Storyplayer v2.0. You can now write tests for different versions of your system under test, so that you don't lose the ability to test older versions as time goes on.

## What Is A System Under Test?

A _system under test_ is the [software](../test-your-code/index.html) or [platform](../test-your-platform/index.html) that you are testing.

Here are some examples of systems under test from our projects:

* `storyplayer-2.0` - this is version 2.0.x of Storyplayer
* `datasift-production` - this is the DataSift production platform

Each system under test has:

* a name (e.g. `storyplayer`)
* a version (e.g. `2.0`)

This allows us to have different configuration files for different versions of a system under test if we need them. That way, if we ever need to test an older version of the system under test, we've still got the Storyplayer config file for it. This is very handy if you're supporting multiple versions of your software at the same time!

For each system under test, you create a simple JSON config file, and then pass the name of the config file to Storyplayer using the `-s` switch. The JSON file goes in your project's `.storyplayer/systems-under-test/` folder:

<pre>
&lt;project-folder&gt;
|- .storyplayer/
|  |- systems-under-test/
|  |  \- storyplayer-2.0.json
|  \- test-environments/
|     \- dsbuild-centos6.json
|- storyplayer.json
</pre>

Storyplayer uses the JSON file's filename as the name of the system under test.

## What Does Storyplayer Need To Know About A System Under Test?

In short: nothing. It's perfectly legal to create a system under test config file that's empty like this:

<pre>
{
}
</pre>

However, if there are any settings that might change from version to version of your system under test, it's better to put them in the config file than hard-code them in your stories.

You can put any or all of these types of settings into your system under test config file(s):

* application settings
* module settings
* test environment parameters

Most of the time, you'll use them for storing application settings that change from version to version of your system under test. You'll rarely use the other settings; for details about them, see [the System Under Test Config File](../../using/configuration/system-under-test-config.html).

### Application Settings

Use application settings to avoid hard-coding variables into your stories.

For example, if your system-under-test is a web application, it will have URLs for landing pages such as registration, logging in, the user's account page, the backend admin page, and so on.  You could just hard code this into your story:

{% highlight php startinline %}
$story->addAction(function() {
    $hostname = fromFirstHostWithRole('web-server')->getHostname();
    usingBrowser()->gotoPage("http://{$hostname}/users/login/")
});
{% endhighlight %}

But what happens if the URL moves in a later version of your app? If you put these URLs into your system-under-test config file like this:

{% highlight json %}
{
    "appSettings": {
        "storyplanner": {
            "loginPage": "/login/"
        }
    }
}
{% endhighlight %}

... you can then get these settings using the [Config()](../../modules/config/index.html) module. You can get a single setting like this:

{% highlight php startinline %}
$loginPage = fromSystemUnderTest()->getAppSetting("storyplanner.loginPage");
usingBrowser()->gotoPage("http://{$hostname}{$loginPage}");
{% endhighlight %}

or you can get all of the settings like this:

{% highlight php startinline %}
$storyplannerSettings = fromSystemUnderTest()->getAppSettings("storyplanner");
usingBrowser()->gotoPage("http://{$hostname}{$storyplannerSettings->loginPage}");
{% endhighlight %}

This helps your stories become independent of which version of your app that you're testing.

### Further Reading

* [System Under Test Config File](../../using/configuration/system-under-test-config.html) is the complete reference to everything you can do with this config file.
* Your stories need to use [the Config Module](../../modules/config/index.html) to retrieve any settings from your system under test config file.