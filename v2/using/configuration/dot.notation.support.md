---
layout: v2/using-configuration
title: dot.notation.support
prev: '<a href="../../using/configuration/user-dot-config.html">Prev: User dot-Storyplayer Config</a>'
next: '<a href="../../using/configuration/overriding-from-the-command-line.html">Next: Overriding From The Command-Line</a>'
updated_for_v2: true
---
# dot.notation.support

Use _dot.notation.support_ to figure out the name of any of Storyplayer's config settings. You can then use the _dot.notation.support_ name to get the value of the config setting that you want.

The easiest way to explain it is with an example.

## An Example

Let's start with this JSON object

{% highlight json %}
{
    "moduleSettings": {
        "aws": {
            "key": "my-aws-key",
            "secret": "aVeryRandomStringHonest",
            "region": "theDeathStar"
        },
        "http": {
            "validateSsl": true
        }
    }
}
{% endhighlight %}

we can use _dot.notation.support_ to access all of the contents like this:

Setting | Dot Notation
--------|-------------
AWS Key | moduleSettings.aws.key
AWS Secret | moduleSettings.aws.secret
AWS Region | moduleSettings.aws.region
HTTP SSL Validation | moduleSettings.http.validateSsl

## Where You Can Use dot.notation.support

* [fromConfig()->get()](../modules/config/fromConfig.html#get)
* [fromConfig()->getModuleSetting()](../modules/config/fromConfig.html#getmodulesetting)
* [fromHost()->getStorySetting()](../modules/config/fromHost.html#getstorysetting)
* [fromStoryplayer()->getStorySetting()](../modules/config/fromStoryplayer.html#getstorysetting)
* [fromSystemUnderTest()->getStorySetting()](../modules/systemundertest/fromSystemUnderTest.html#getstorysetting)