---
layout: v2/learn-fundamentals
title: Installing Storyplayer
prev: '<a href="../../learn/fundamentals/index.html">Prev: Fundamentals Of Storyplayer</a>'
next: '<a href="../../learn/fundamentals/running-storyplayer.html">Next: Running Storyplayer</a>'
updated_for_v2: true
---

# Installing Storyplayer

We're assuming that you've already [setup your computer](../getting-setup/index.html). If not, please go and do that before continuing.

## Install Via Composer

Storyplayer should be installed using Composer. Simply add the following `require-dev` entry to your `composer.json` file, for example:

{% highlight json %}
{
    "require-dev": {
        "datasift/storyplayer": "~2.0.0"
    }
}
{% endhighlight %}

Then run `composer update` if Composer is installed globally or `php composer.phar update` if Composer is installed locally.

After this, you'll find Storyplayer in `vendor/bin/storyplayer`.

Next, we need to use Storyplayer to download some additional files. These are dependencies that currently can't be installed via Composer.

    $ vendor/bin/storyplayer install
    Additional files will be added to the vendor/ folder
    Downloading: http://chromedriver.googlecode.com/files/chromedriver_linux64_2.1.zip (7.026mb)
    Downloading: http://selenium.googlecode.com/files/selenium-server-standalone-2.33.0.jar (32.708mb)
    $ vendor/bin/browsermob-proxy.sh start
    $ vendor/bin/selenium-server.sh start

This will download some additional tools into your project's `vendor/bin` folder, and start them running in the background on your computer.
