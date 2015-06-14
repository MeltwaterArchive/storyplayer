---
layout: v2/learn-getting-setup
title: Testing Your Setup
prev: '<a href="../../learn/getting-setup/setting-up-ubuntu-desktop.html">Prev: Setting Up Ubuntu Desktop</a>'
next: '<a href="../../learn/fundamentals/index.html">Next: Fundamentals Of Storyplayer</a>'
updated_for_v2: true
---

# Testing Your Setup

## Running Storyplayer's Own Test Suite

The best way to test that everything is installed is to run Storyplayer's own test suite:

{% highlight bash %}
# download the Storyplayer source code
cd $HOME
mkdir Projects
cd Projects
git clone https://github.com/datasift/storyplayer.git
cd storyplayer

# install Storyplayer's PHP dependencies
# assumes you have installed composer as /usr/local/bin/composer
composer install

# download Storyplayer's Java dependencies
src/bin/storyplayer install

# Selenium is used to control web browsers
vendor/bin/selenium-server.sh start

# check that Vagrant and Virtualbox are working together
( cd storyplayer/test-environments/vagrant-vbox-centos6-ssl && vagrant up )

# this will run Storyplayer's own test suite
src/bin/storyplayer
{% endhighlight %}

Afterwards, you can delete the `$HOME/Projects/storyplayer` folder.