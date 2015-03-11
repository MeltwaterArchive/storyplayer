---
title: Getting Setup For Storyplayer
layout: v2/learn-getting-setup
prev: '<a href="../../learn/index.html">Prev: Learning Storyplayer</a>'
next: '<a href="../../learn/getting-setup/setting-up-osx-with-homebrew.html">Next: Setting Up Apple OSX Using Homebrew</a>'
updated_for_v2: true
---
# Getting Setup For Storyplayer

It doesn't take long to get up and running with Storyplayer. You just need a working PHP development environment and some additional applications that Storyplayer calls when running tests.

## Operating System Requirements

Storyplayer is developed and tested on:

* Apple OSX Yosemite
* Ubuntu Linux Desktop 14.10

You should be able to use Storyplayer on any Linux distro which has the correct dependencies (listed below) installed.

At this time, Storyplayer probably doesn't work on Windows. Patches / maintainers most welcome!

## Step By Step Guides

The easiest way to setup your computer is to follow one of our step-by-step guides:

* [Setting Up OSX With Homebrew](setting-up-osx-with-homebrew.html)
* [Setting Up OSX With Macports](setting-up-osx-with-macports.html)
* [Setting Up Ubuntu Desktop](setting-up-ubuntu-desktop.html)

## Manual Setup

If you're installing everything by hand, here's a complete list of everything you need on your computer.

* [PHP 5.5](http://php.net) or later (PHP 5.6 recommended)
* The following PHP extensions:
  * cURL
  * JSON
  * MySQLi
  * OpenSSL
  * POSIX
  * PCNTL
  * ZMQ (optional)
* GNU Screen

If you are testing web pages, then you will need:

* A web browser that supports the WebDriver protocol (Google Chrome, Mozilla Firefox or Apple Safari)
* Java JRE 1.6 or later
* A working desktop (the browsers aren't run in headless mode)

If you are deploying software into a virtual machine on your computer, then you will need:

* Vagrant
* Virtualbox
* A supported server orchestration solution (Ansible)

Once you've installed everything, it's a good idea to [test your setup](testing-your-setup.html) to catch any problems.