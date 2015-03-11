---
layout: v2/learn-test-your-code
title: Defining Your System Under Test
prev: '<a href="../../learn/test-your-code/defining-your-test-environment.html">Prev: Designing Your Test Environment</a>'
next: '<a href="../../learn/test-your-code/recommended-first-tests.html">Next: Recommended First Tests</a>'
updated_for_v2: true
---
# Defining Your System Under Test

The _system under test_ is Storyplayer's term for the component that you are testing.

Storyplayer needs a config file for each system under test. You can start with an empty config file, and then build this up as you write your stories.

## Naming Your Config File

The component you are testing will have name and a version, such as:

* storyplayer-2.0
* savaged-1.3

That is the name to use for your system-under-test config file:

* `.storyplayer/systems-under-test/storyplayer-2.0.json`
* `.storyplayer/systems-under-test/savaged-1.3.json`

<div class="callout info" markdown="1">
#### One Config File Per Version

The idea is to have a config file for each supported version of your component. That way, as new versions of your component need their own configuration, you've still got the config for the old versions around for regression testing.

We'll look at that in a lot more detail in a moment.
</div>

## Start With A Blank Slate

Before you start writing any stories, start with an empty config file:

{% highlight json %}
{
}
{% endhighlight %}

That's enough to be able to run Storyplayer and get started on writing stories.

In fact, at this point, it's probably a good idea to skip ahead to [our recommended first tests for your component](recommended-first-tests.html), and then come back after you've got some stories to work with.

## Build Up The Config Bit By Bit

As you will have seen if you skipped ahead, you can add an `appSettings` section to the system under test's config file.

{% highlight json %}
{
	"appSettings": {
		"my_app": {
			"pages": {
				"healthcheck": "/healthcheck.php"
			}
		}
	}
}
{% endhighlight %}

You can then get these settings using Storyplayer's [SystemUnderTest module](../../modules/systemundertest/index.html):

{% highlight php startinline %}
$myAppSettings = $st->fromSystemUnderTest()->getAppSettings('my_app');
$url = $myAppSettings->pages->healthcheck;
{% endhighlight %}

## Version (Semi-)Independence

Why should you start putting settings into your system under test config file? The answer is that you're future-proofing your tests as much as is practical.

Let's say that `my_app-1.x` has these three pages:

* healthcheck: `/healthcheck.php`
* login: `/login.php`
* registration: `/registration.php`

You could just put these values directly into your tests, and they will run as expected.

Now, let's say that `my_app-2.0` comes along, and has different URLs for these pages:

* healthcheck: `/private/healthcheck.php`
* login: `/login/`
* registration: `/signup/`

All of a sudden, the tests that work great against `my_app-1.x` no longer work against `my_app-2.0`, and all because the pages moved. (We'll assume that the pages themselves work the same, and just the URL changed.) How are you going to modify your tests to work against both versions?

This is where the system under test config file comes in. Create a file called `.storyplayer/systems-under-test/my_app-1.x.json` with the original URLs:

{% highlight json %}
{
	"appSettings": {
		"my_app": {
			"pages": {
				"healthcheck": "/healthcheck.php",
				"login": "/login.php",
				"registration": "/registration.php"
			}
		}
	}
}
{% endhighlight %}

and then create a second file called `.storyplayer/systems-under-test/my_app-2.0.json` with the new URLs:

{% highlight json %}
{
	"appSettings": {
		"my_app": {
			"pages": {
				"healthcheck": "/private/healthcheck.php",
				"login": "/login/",
				"registration": "/signup/"
			}
		}
	}
}
{% endhighlight %}

You can then tell Storyplayer which file to use for your tests:

    vendor/bin/storyplayer -s my_app-v1.x
    vendor/bin/storyplayer -s my_app-v2.0

and as long as your stories use the [SystemUnderTest module](../../modules/system-under-test/index.html) to read the `appSettings`, you can test against both versions without having to change your tests:

{% highlight php startinline %}
$story->addAction(function($st) {
	$myAppSettings = $st->fromSystemUnderTest()->getAppSettings('my_app');

	// with -s 'my_app-v1.x', this is "/healthcheck.php"
	// with '-s my_app-v2.0', this is "/private/healthcheck.php"
	$healthCheckPath = $myAppSettings->pages->healthcheck;
});
{% endhighlight %}

## Going Further

Most of the time, adding `appSettings` to your system under test config file will be all that you need to do for your tests.

If you're curious about what else you can do with this config file, we have [a page dedicated to the System Under Config config file in our reference manual](../../using/configuration/system-under-test-config.html).