---
layout: v2/learn-getting-setup
title: Setting Up Apple OSX Using Macports
prev: '<a href="../../learn/getting-setup/setting-up-osx-with-homebrew.html">Prev: Setting Up Apple OSX Using Homebrew</a>'
next: '<a href="../../learn/getting-setup/setting-up-ubuntu-desktop.html">Next: Setting Up Ubuntu Desktop</a>'
updated_for_v2: true
---

# Setting Up Apple OSX Using Macports

Coming very soon!

These are the instructions to prepare your OSX machine for running Storyplayer. These instructions use [MacPorts](http://www.macports.org), a package manager that makes it easy to install open-source software onto OSX.

## Why Macports?

We recommend that you get your copy of PHP from either Homebrew or Macports, rather than use the version of PHP supplied with OSX. You'll end up with a more up-to-date version of PHP, and you'll find it much easier when you want to compile and install PHP extensions.

Storyplayer will work equally well with both, but if you're not sure which one to choose, Stuart uses Macports.

## Compilers

1. Install Xcode from the App Store. It's free. This gives you a C/C++ compiler and Git for version control.
1. Install the Xcode CLI tools:

        xcode-select --install

1. Agree to the Xcode license, so that the CLI tools work:

        sudo xcodebuild -license

## PHP Using MacPorts

1. [Install MacPorts](http://www.macports.org) if you don't already have it.
1. Run these commands in Terminal:

        # install PHP from Macports
        sudo port install php56 php56-curl php56-mcrypt php56-opcache php56-pcntl
        sudo port install php56-pear php56-posix php56-mysql
        sudo port install pkgconfig wget curl

        # replace Apple's PHP with a modern one
        sudo rm /usr/bin/phpize
        sudo ln -s /opt/local/bin/phpize56 /usr/bin/
        sudo rm /usr/bin/php-config
        sudo ln -s /opt/local/bin/php-config56 /usr/bin/
        sudo rm /usr/bin/php
        sudo ln -s /opt/local/bin/php56 /usr/bin/

        # fix Macports PEAR / PECL support
        sudo ln -s /opt/local/lib/php/pear/bin/pear /opt/local/bin/
        sudo ln -s /opt/local/lib/php/pear/bin/pecl /opt/local/bin/

1. Make sure that `/opt/local/bin` and `/opt/local/sbin` are at the front of your PATH. You can check this by running:

        which php

    You should see `/opt/local/bin/php` as the answer.

1. Edit `/opt/local/var/db/php56/php.ini` and change the following settings:

        date.timezone = UTC

1. Install libzmq from Github:

        cd ~
        mkdir Sources
        cd Sources
        git clone https://github.com/zeromq/zeromq4-x.git
        cd zeromq4-x
        ./autogen.sh
        ./configure
        make
        sudo make install

1. Install PHP's ZMQ extension

        echo | pecl install zmq-1.1.2

1. Edit `/opt/local/var/db/php56/zmq.ini` and give it the following contents:

        [zmq]
        extension=zmq.so

## Other CLI Tools

1. Run this command in Terminal to install GNU Screen:

        sudo port install screen

   OSX does already include `screen`, but unfortunately it doesn't behave quite the same as the original GNU Screen. Storyplayer needs the original!

## Virtual Machine Tools

1. Download and install [VirtualBox](http://virtualbox.org).

   VirtualBox is a free Virtual Machine (VM) solution from Oracle.  Because it is free, it is widely used in the software development community.

1. Download and install [Vagrant](http://vagrantup.com).

   Vagrant is a CLI tool to manage creating and destroying virtual machines.  It's most often used with VirtualBox.

## Web Browsers

1. Download and install [a Java VM for OSX](http://www.java.com).

   Storyplayer uses [Selenium v2 aka WebDriver](http://www.seleniumhq.org) to control real web browsers. Selenium is written in Java.

1. Download and install [Composer](https://getcomposer.org/download/)

   Composer is the modern package manager for PHP libraries. You'll use Composer to install Storyplayer into your projects.

1. Download and install [Google Chrome](https://www.google.com/chrome/) and [Mozilla Firefox](https://www.mozilla.org).

   Storyplayer can use these browser for testing websites.

   At the time of writing (January 2015), Selenium WebDriver doesn't work out-of-the-box with Apple's Safari. More details can be found [here](../../using/web-browsers/osx-safari.html).

## All Done

When you get to here, your Apple laptop or desktop should be all setup for Storyplayer, and any other CLI apps written in PHP. [Test your setup](testing-your-setup.html) to make sure!
