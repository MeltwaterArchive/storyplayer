---
layout: v2/tips-vagrant
title: Exhausted DHCP Pool
---

# Exhausted DHCP Pool

If you find that your Vagrant VMs cannot get a public IP address, and you're using bridged network in your Vagrantfile, chances are that your network's DHCP server has no free IP addresses to give out.

This can happen if you have a small DHCP pool on the DHCP server, and are creating and destroying test environment very frequently.

Possible solutions include:

1. Use the -P and -R switches to reuse your test environment

1. Switch from bridged networking to private networking

1. Increase the size of the DHCP pool on your DHCP server

1. Decrease the lease time on your DHCP server