---
layout: v2/learn-getting-setup
title: Setting Up Apple OSX Using Homebrew
prev: '<a href="../../learn/getting-setup/index.html">Prev: Getting Setup For Storyplayer</a>'
next: '<a href="../../learn/getting-setup/setting-up-osx-with-macports.html">Next: Setting Up Apple OSX Using Macports</a>'
---

# Setting Up Apple OSX Using Homebrew

These are the instructions to prepare your OSX machine for running Storyplayer. These instructions use [Homebrew](http://brew.sh), a package manager that makes it easy to install open-source software onto OSX.

## Why Homebrew?

We recommend that you get your copy of PHP from either Homebrew or Macports, rather than use the version of PHP supplied with OSX. You'll end up with a more up-to-date version of PHP, and you'll find it much easier when you want to compile and install PHP extensions.

Storyplayer will work equally well with both, but if you're not sure which one to choose, Stuart uses Macports.

## Compilers

1. Install Xcode from the App Store. It's free. This gives you a C/C++ compiler and Git for version control.
1. Install the Xcode CLI tools:

        xcode-select --install

1. Agree to the Xcode license, so that the CLI tools work:

        sudo xcodebuild -license

## PHP Using Homebrew

1. [Install Homebrew](http://brew.sh) if you don't already have it.
1. Run these commands in Terminal:

        brew doctor
        brew install openssl
        brew install homebrew/php/php56 --with-brewed-openssl
        brew install homebrew/php/php56-uuid
        brew install homebrew/php/php56-zmq

1. Make sure that `/usr/local/bin` and `/usr/local/sbin` are at the front of your PATH. (This is the default behaviour on OSX Yosemite.)  You can check this by running:

        which php

    You should see `/usr/local/bin/php` as the answer.

1. Edit `/usr/local/etc/php/5.6/php.ini` and change the following settings:

        date.timezone = UTC

1. Create the file `/usr/local/etc/php/5.6/conf.d/ext-opcache.ini`, with this content:

        [opcache]
        zend_extension=/usr/local/Cellar/php56/5.6.4/lib/php/extensions/no-debug-non-zts-20131226/opcache.so

## Other CLI Tools

1. Run this command in Terminal to install GNU Screen:

        brew install screen

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
