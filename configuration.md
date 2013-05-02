---
layout: top-level
title: Configuring Storyplayer
---

# Configuring Storyplayer

Storyplayer depends on a number of external software packages that must exist on the system it is supposed to run from.

## Installing Storyplayer

### Prerequisites

Clone the [Storyplayer repository](https://github.com/datasift/storyplayer).

Install the following software packages:

* Install [Python](http://python.org) 2.7.3 (or later)
* Install Python `netifaces`:
 
    `pip install netifaces`

* Install [PHP](http://php.net) 5.3.10
* Install PHP5 cURL support (the following command works on Ubuntu, other systems will use differetn installation tools): 

    `sudo apt-get install php5-curl`
    
* Install [Phinx](http://phix-project.org) (follow the instructions on the Phinx site):
* Configure Pear

    `phing pear-package`
    
    `phing install-vendor`

## Testing Storyplayer

Change the working directory to `storyplayer`:

    $ cd storyplayer

Run our first test:

    bin/storyplayer example src/tests/examples/twitter-ui/CanOpenTwitterHomePageStory.php

The output you will see should end with a message similar to the one below:

Now performing: Final Results

    ...
    [2013-05-01 14:48:57] [storyplayer:7646] 6: NOTICE:    expected: SUCCESS         ; action: COMPLETED ; actual: SUCCESS         ; result: PASS
