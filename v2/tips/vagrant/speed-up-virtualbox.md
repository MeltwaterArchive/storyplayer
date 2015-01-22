---
layout: v2/tips-vagrant
title: How To Speed Up Vagrant VMs
prev: '<a href="../../tips/vagrant/index.html">Prev: Tips For Vagrant</a>'
next: '<a href="../../modules/index.html">Next: Storyplayer Modules</a>'
---

# How To Speed Up Vagrant VMs

If you find that your VirtualBox VMs seem slow when you try to SSH into them or when you point a web browser at them, then try adding these lines to your `Vagrantfile`:

    Vagrant.configure("2") do |config|
      config.vm.provider :virtualbox do |vb|

        # change the network card hardware for better performance
        vb.customize ["modifyvm", :id, "--nictype1", "virtio" ]
        vb.customize ["modifyvm", :id, "--nictype2", "virtio" ]

        # suggested fix for slow network performance
        # see https://github.com/mitchellh/vagrant/issues/1807
        vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
      end
    end