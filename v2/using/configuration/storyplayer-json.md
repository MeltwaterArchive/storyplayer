---
layout: v2/using-configuration
title: "The storyplayer.json File"
next: '<a href="../configuration/app-settings.html">Next: Adding App Settings To Your Config File</a>'
prev: '<a href="../configuration/index.html">Prev: Configuring Storyplayer</a>'
---

# The storyplayer.json File

_storyplayer.json_ is the main configuration file for your tests.  You should place _storyplayer.json_ in the top-level folder of the repository containing your tests.

## An Example storyplayer.json File

{% highlight json %}
{
    "environments": {
        "defaults": {
            "api": {
                "url": "https://api.dev0",
                "streamUrl": "https://stream.dev0"
            },
            "vagrant": {
                "provisioning_vars_file": "ansible-playbooks/vars/storyplayer.yml"
            }
        },
        "staging": {
            "api": {
                "url": "https://api.stagingdatasift.com",
                "streamUrl": "https://stream.stagingdatasift.com"
            }
        },
        "production": {
            "api": {
                "url": "https://api.datasift.com",
                "streamUrl": "https://stream.datasift.com"
            }
        }
    }
}
{% endhighlight %}

## Structure Of The storyplayer.json File

Your storyplayer.json file must contain the following sections:

* _environments_: this an object containing a list of environments to run tests against, one object per test environment.  There must be at least one test environment in the list.  Each of these objects contains any [app settings](app-settings.html) that you want to use in your tests.

Your storyplayer.json file may contain the following sections:

* _environments->defaults_: this object contains any app settings that you want to use in your tests, to avoid having to copy all of the settings for each of your environments.  At runtime, any default settings are merged into any settings for the environment that you choose to run the test against.
* _logging_: this object contains the [configuration for Storyplayer's output log](logging.html).
* _phases_: this object allows you to [disable specific phases of each story](test-phases.html), which can save a lot of time when you're developing a new test.

## The storyplayer.json.dist File

If Storyplayer cannot find a _storyplayer.json_ file, it will look for _storyplayer.json.dist_.  This is the same approach that PHPUnit uses with its _phpunit.xml_ config file.

For your open-source projects, it allows you to ship a _storyplayer.json.dist_ file, and if anyone wants their own settings instead, they can just write their own _storyplayer.json_ file - they don't have to edit the _storyplayer.json.dist_ file.