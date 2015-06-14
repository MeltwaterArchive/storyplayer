---
layout: v2/learn-getting-setup
title: Setting Up Ubuntu Desktop
prev: '<a href="../../learn/getting-setup/setting-up-osx-with-macports.html">Prev: Setting Up Apple OSX Using Macports</a>'
next: '<a href="../../learn/getting-setup/testing-your-setup.html">Next: Testing Your Setup</a>'
updated_for_v2: true
---

# Setting Up Ubuntu Desktop

These are the instructions to prepare your Ubuntu Desktop machine for running Storyplayer.

These instructions are written for Ubuntu 14.04 LTS. They should work without modification on Ubuntu 14.10 or later too.

## Why Ubuntu Desktop?

Any tests that involve a web browser need a real X11 desktop to work, and Ubuntu Server does not come with an X11 desktop (although you can install one if you wish). Browsers do not work reliably when run inside `Xnest` or equivalent.

## Update Your Package List

Start with an up-to-date list of available packages from the Ubuntu servers.

{% highlight bash %}
sudo apt-get -y update
{% endhighlight %}

## Compilers

Install the standard Linux compiler tools. You'll need them for installing any PECL PHP extensions that you need in your tests, such as ZeroMQ.

{% highlight bash %}
sudo apt-get -y install build-essential libtool automake git pkg-config
{% endhighlight %}

## PHP

### Install PHP

Use Ubuntu's standard PHP packages.

{% highlight bash %}
sudo apt-get -y install php5-cli php5-curl php5-json php5-mysql php5-dev php-pear
{% endhighlight %}

### Install Composer

Download and install [Composer](https://getcomposer.org/download/). Composer is the modern package manager for PHP libraries. You'll use Composer to install Storyplayer into your projects.

{% highlight bash %}
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod 755 /usr/local/bin/composer
{% endhighlight %}

## Virtual Machine Tools

### Install Virtualbox

Oracle provide a Debian package repo that we can use for installing Virtualbox (and for keeping it up to date!)

{% highlight bash %}
# add the virtualbox repo
#
# replace 'utopic' (Ubuntu 14.10) with 'vivid' (Ubuntu 15.04) or 'trusty' (Ubuntu 14.04)
wget -q -O - http://download.virtualbox.org/virtualbox/debian/oracle_vbox.asc | sudo apt-key add -
sudo sh -c 'echo "deb http://download.virtualbox.org/virtualbox/debian utopic contrib" >> /etc/apt/sources.list.d/virtualbox.org.list'

# pull down the list of packages in the virtualbox repo
sudo apt-get update

# install virtualbox
sudo apt-get -y install virtualbox-4.3
{% endhighlight %}

### Install Vagrant

Vagrant has to be downloaded and installed by hand.

1. Download the latest [Vagrant](http://vagrantup.com) .deb package.

   Vagrant is a CLI tool to manage creating and destroying virtual machines.  It's most often used with VirtualBox.

1. Install the downloaded .deb package

{% highlight bash %}
# vagrant is downloaded from their website, there is no ppa or repo
#
# by default, your browser's downloads go into your Downloads/ folder
sudo dpkg -i ~/Downloads/vagrant_1.7.2_x86_64.deb
{% endhighlight %}

## Provisioning

### Install Ansible

If you're using Ansible for provisioning, you can get Ansible from a third-party repo.

{% highlight bash %}
# add the ansible repo
sudo apt-add-repository ppa:rquillo/ansible

# update the repos
sudo apt-get update

# install packages
sudo apt-get -y install ansible
{% endhighlight %}

## Web Browsers

### Install Java JVM

Download and install [a Java VM for OSX](http://www.java.com). Storyplayer uses [Selenium v2 aka WebDriver](http://www.seleniumhq.org) to control real web browsers. Selenium is written in Java.

{% highlight bash %}
sudo apt-get install -y openjdk-7-jdk
{% endhighlight %}

### Install Chrome

Download and install [Google Chrome](https://www.google.com/chrome/). Ubuntu comes with [Mozilla Firefox](https://www.mozilla.org) already installed. Storyplayer can use either of these browsers for testing websites.

## ZeroMQ

If you're using ZeroMQ, you will need to build it from source:

{% highlight bash %}
# build libzmq first
git clone https://github.com/zeromq/zeromq4-x.git
cd zeromq4-x
./autogen.sh
./configure
make
sudo make install

# install PHP support
echo | sudo pecl install zmq-1.1.2
sudo bash -c 'echo "extension=zmq.so" > /etc/php5/mods-available/zmq.ini'
sudo php5enmod zmq
{% endhighlight %}

## All Done

When you get to here, your Ubuntu desktop should be all setup for Storyplayer, and any other CLI apps written in PHP. [Test your setup](testing-your-setup.html) to make sure!