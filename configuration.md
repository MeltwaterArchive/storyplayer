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

* [PHP](http://php.net) 5.3.10
* [Python](http://python.org) 2.7.3 (or later)
* `pip install netifaces`
* (On Ubuntu, other systems will use differetn installation tools): `sudo apt-get install php5-curl`
* [Phinx](http://phix-project.org)
* `phing pear-package`
* `phing install-vendor`
