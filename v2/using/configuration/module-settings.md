---
layout: v2/using-configuration
title: moduleSettings Section
prev: '<a href="../../using/configuration/story-settings.html">Prev: storySettings Section</a>'
next: '<a href="../../using/configuration/phases.html">Next: Test Phases Configuration</a>'
updated_for_v2: true
---

# moduleSettings Section

The `moduleSettings` sections of your config files are where Storyplayer's modules look for their configuration settings.

## Where Can You Add moduleSettings?

You can add `moduleSettings` to any of your config files:

* your [personal dot-storyplayer config file](dot-storyplayer-config.html)
* your [system-under-test config file](system-under-test-config.html)
* your [test environment config file](test-environment-config.html)
* your [storyplayer.json](storyplayer-json.html) file

Storyplayer will search these files in the order given above, and use the first one that it finds.

## Which Setting Goes Where?

As a general rule:

* anything with usernames / passwords / security tokens belongs in your personal `.storyplayer` config file
* anything where you need different test environments to behave differently belongs in your test environment config files
* anything else belongs in your `storyplayer.json` config file

There's really no reason to put any `moduleSettings` into your system-under-test config file at this time.

## Example: Amazon Web Service Keys

The [Amazon EC2 module](../../modules/ec2/index.html) needs you to provide your Amazon Web Service API 'key' and 'secret'. The safest place to put this is in your personal `.storyplayer` config file:

{% highlight json %}
{
    "moduleSettings": {
        "aws": {
            "key": "...",
            "secret": "...",
            "region": "..."
        }
    }
}
{% endhighlight %}

That way, there's no chance of you accidentally publishing your AWS credentials on GitHub for others to find and abuse.

## Example: HTTP SSL Certificate Validation

By default, the [HTTP module](../../modules/http/index.html) attempts to validate all SSL certificates when connecting to a 'https' URL. This is absolutely the correct behaviour when you're testing your production environment. The last thing you want in production is to fail to spot a 'bad' SSL certificate during your testing.

However, your development environment is probably using self-signed certificates. Self-signed certificates cannot be validated. You don't want your tests to fail, and you don't want to skip using 'https' in development.

The answer is to use a `moduleSetting` in the test environment config file for your dev environment to switch off SSL validation.

{% highlight json %}
{
    "moduleSettings": {
        "http": {
            "validateSsl": false
        }
    }
}
{% endhighlight %}

## Accessing moduleSettings

<div class="callout warning" markdown="1">
#### For Modules Only

`moduleSettings` are configuration for Storyplayer's modules - and for any modules of your own that you create.

You should never need to access `moduleSettings` from your stories.
</div>

Use _[fromConfig()->getModuleSetting()](../../modules/fromConfig.html#getmodulesetting)_ to retrieve a module's settings:

{% highlight php startinline %}
$settings = fromConfig()->getModuleSetting('aws');
{% endhighlight %}

You can also use [dot.notation.support](dot.notation.support.html) to access individual settings:

{% highlight php startinline %}
$validateSsl = fromConfig()->getModuleSetting('http.validateSsl');
{% endhighlight %}

`fromConfig()->getModuleSetting()` will search through all of the loaded config files in order (see the top of this page for the search order), and return the first setting found.