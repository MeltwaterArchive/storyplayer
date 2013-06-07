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

Storyplayer is installed using PEAR:

{% highlight bash %}
sudo pear config-set auto_discover 1
sudo pear channel-discover datasift.github.io/pear
sudo pear install DataSift/storyplayer
{% endhighlight %}

This will install the <code>storyplayer</code> command onto your computer.