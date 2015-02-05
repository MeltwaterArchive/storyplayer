---
layout: v2/learn-getting-setup
title: Testing Your Setup
prev: '<a href="../../learn/getting-setup/setting-up-ubuntu-desktop.html">Prev: Setting Up Ubuntu Desktop</a>'
next: '<a href="../../learn/fundamentals/index.html">Next: Fundamentals Of Storyplayer</a>'
---

# Testing Your Setup

## Running Storyplayer's Own Test Suite

The best way to test that everything is installed is to run Storyplayer's own test suite:

    cd $HOME
    mkdir Projects
    cd Projects
    git clone https://github.com/datasift/storyplayer.git
    cd storyplayer
    composer.phar install
    src/bin/storyplayer install
    vendor/bin/browsermob-proxy.sh start
    vendor/bin/selenium-server.sh start
    src/bin/storyplayer

Afterwards, you can delete the `$HOME/Projects/storyplayer` folder.