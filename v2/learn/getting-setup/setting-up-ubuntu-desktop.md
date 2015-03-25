---
layout: v2/learn-getting-setup
title: Setting Up Ubuntu Desktop
prev: '<a href="../../learn/getting-setup/setting-up-osx-with-macports.html">Prev: Setting Up Apple OSX Using Macports</a>'
next: '<a href="../../learn/getting-setup/testing-your-setup.html">Next: Testing Your Setup</a>'
updated_for_v2: true
---

# Setting Up Ubuntu Desktop

These are the instructions to prepare your Ubuntu Desktop machine for running Storyplayer.

## Install Steps

<code>
# add the virtualbox repo
wget -q -O - http://download.virtualbox.org/virtualbox/debian/oracle_vbox.asc | sudo apt-key add -
sudo sh -c 'echo "deb http://download.virtualbox.org/virtualbox/debian utopic non-free contrib" >> /etc/apt/sources.list.d/virtualbox.org.list' 

# add the ansible repo
sudo apt-add-repository ppa:rquillo/ansible

# update the repos
sudo apt-get update

# install packages
sudo apt-get install virtualbox ansible php5-curl php5-json php5-mysql

# vagrant is downloaded from their website, there is no ppa or repo
sudo dpkg -i vagrant_1.7.2_x86_64.deb 

# clone storyplayer repo
cd ~/work
git clone git@github.com:datasift/storyplayer.git

# install composer
cd ~/work/storyplayer
curl -sS https://getcomposer.org/installer | php
./composer.phar install
</code>

