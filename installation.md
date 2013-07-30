---
layout: top-level
title: Installing Storyplayer
prev: '<a href="index.html">Prev: Storyplayer</a>'
next: '<a href="running-storyplayer.html">Next: Running Storyplayer</a>'
---

# Installing Storyplayer

## Dependencies

To run Storyplayer, you'll need at least the following:

* [PHP 5.3](http://php.net) or later
* cURL extension for PHP
* YAML extension for PHP
* Python - 2.7 preferred
* Python netifaces - _pip install netifaces_
* Google Chrome (if you want to test web pages)
* Java JRE 1.6 or later (if you want to test web pages)

If you want to run Storyplayer from inside a GitHub clone, you'll also need to install [Phix](http://phix-project.org) in order to build the vendor folder and PEAR packages.

## How To Install

### Install Via Composer

From version 1.3, Storyplayer can be installed using Composer. Simply add the following <code>require-dev</code> entry to your <code>composer.json</code> file, for example:

{% highlight json %}
{
    "name": "vendor/my-project",
    "require-dev": {
        "datasift/storyplayer": "*"
    }
}
{% endhighlight %}

Then run <code>composer update</code> if Composer is installed globally or <code>php composer.phar update</code> if Composer is installed locally.

After this, if you want to use the Browser module, you will need to ask Storyplayer to download Selenium and ChromeDriver, like so (versions downloaded may differ from this example):

{% highlight bash %}
$ vendor/bin/storyplayer install
Additional files will be added to the vendor/ folder
Downloading: http://chromedriver.googlecode.com/files/chromedriver_linux64_2.1.zip (7.026mb)
Downloading: http://selenium.googlecode.com/files/selenium-server-standalone-2.33.0.jar (32.708mb)
{% endhighlight %}

### Install Via PEAR

Storyplayer is installed using PEAR:

{% highlight bash %}
sudo pear config-set auto_discover 1
sudo pear channel-discover datasift.github.io/pear
sudo pear install DataSift/storyplayer
{% endhighlight %}

This will install the <code>storyplayer</code> command onto your computer.

### Install From Source

You can also run the very latest Storyplayer from a GitHub checkout:

{% highlight bash %}
git clone https://github.com/datasift/storyplayer.git
cd storyplayer
composer install
export PATH=`pwd`/src/bin:$PATH
{% endhighlight %}
