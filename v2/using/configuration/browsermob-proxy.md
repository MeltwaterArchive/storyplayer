---
layout: v2/using-configuration
title: browsermob-proxy Configuration
updated_for_v2: true
prev: '<a href="../../using/configuration/device-config.html">Prev: Device Configuration</a>'
next: '<a href="../../using/deprecated/index.html">Next: Deprecated Features</a>'
---

# browsermob-proxy Configuration

`browsermob-proxy` is an open-source HTTP proxy that is designed to plug some of the gaps that exists with Selenium WebDriver.

<div class="callout info" markdown="1">
#### Now An Optional Feature

In SPv1, and early releases of SPv2, you had to use `browsermob-proxy` if you wanted to use the [Browser](../../modules/browser/index.html) or [Form](../../modules/form/index.html) modules.

In Storyplayer v2.2.0, we made `browsermob-proxy` an optional feature. Why?

* Browser-based tests run much faster when they're not using `browsermob-proxy`.
* You no longer need to install `browsermob-proxy`'s SSL certificate (this SSL certificate is a major security risk to your desktop computer, but is required if you want to test secure sites using `browsermob-proxy`).
* The maintainer of `browsermob-proxy` announced in January 2015 that he's no longer able to maintain the app. We may be forced to deprecate `browsermob-proxy` in the near future (it's too early to say).

If you have a test that absolutely requires `browsermob-proxy`, read on for details of how to switch this on.
</div>

## How To Enable

Add the following module setting to your `storyplayer.json` config file:

{% highlight json %}
{
    "moduleSettings": {
        "device": {
            "browsermob": {
                "enabled": true
            }
        }
    }
}
{% endhighlight %}