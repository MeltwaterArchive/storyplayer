---
layout: v2/top-level
title: Installing Storyplayer
prev: '<a href="learn/index.html">Prev: Learning Storyplayer</a>'
next: '<a href="running-storyplayer.html">Next: Running Storyplayer</a>'
---

# Installing Storyplayer

## Dependencies

To run Storyplayer, you'll need at least the following:

### PHP Dependencies

* [PHP 5.3](http://php.net) or later
* `apt-get install php5-json` on latest Debian / Ubuntu versions
* cURL extension for PHP
* YAML extension for PHP

### Other Dependencies

* GNU screen - `apt-get install screen` works on Ubuntu
* Google Chrome or Mozilla Firefox or Apple Safari (if you want to test web pages)
* Java JRE 1.6 or later (if you want to test web pages)
* Vagrant (if you want to create test virtual machines)
* Ansible (if you want to deploy into your test virtual machines from Storyplayer)

## Install Via Composer

Storyplayer can be installed using Composer. Simply add the following `require-dev` entry to your `composer.json` file, for example:

{% highlight json %}
{
    "require-dev": {
        "datasift/storyplayer": "*"
    }
}
{% endhighlight %}

Then run `composer update` if Composer is installed globally or `php composer.phar update` if Composer is installed locally.

After this, if you want to use the [Browser module](modules/browser/index.html), you will need to ask Storyplayer to download Selenium and ChromeDriver, like so (versions downloaded may differ from this example):

<pre>
$ vendor/bin/storyplayer install
Additional files will be added to the vendor/ folder
Downloading: http://chromedriver.googlecode.com/files/chromedriver_linux64_2.1.zip (7.026mb)
Downloading: http://selenium.googlecode.com/files/selenium-server-standalone-2.33.0.jar (32.708mb)
$ vendor/bin/browsermob-proxy.sh start
$ vendor/bin/selenium-server.sh start
</pre>

This will download some additional tools into your project's `vendor/bin` folder, and start them running in the background on your computer.

## Install From Source

You can also run the very latest Storyplayer from a GitHub checkout:

<pre>
git clone https://github.com/datasift/storyplayer.git
cd storyplayer
composer install
export PATH=`pwd`/src/bin:$PATH
</pre>

After this, if you want to use the [Browser module](modules/browser/index.html), you will need to ask Storyplayer to download Selenium and ChromeDriver, like so (versions downloaded may differ from this example):

<pre>
$ cd &lt;your-project-folder&gt;
$ storyplayer install
Additional files will be added to the vendor/ folder
Downloading: http://chromedriver.googlecode.com/files/chromedriver_linux64_2.1.zip (7.026mb)
Downloading: http://selenium.googlecode.com/files/selenium-server-standalone-2.33.0.jar (32.708mb)
$ vendor/bin/browsermob-proxy.sh start
$ vendor/bin/selenium-server.sh start
</pre>

This will download some additional tools into your project's `vendor/bin` folder, and start them running in the background on your computer.
