---
layout: v2/learn-test-your-code
title: Sample Layout For Source Code Repo
prev: '<a href="../../learn/test-your-code/what-are-you-testing.html">Prev: What Are You Testing?</a>'
next: '<a href="../../learn/test-your-code/defining-your-test-environment.html">Next: Designing Your Test Environment</a>'
updated_for_v2: true
---
# Sample Layout For Source Code Repo

## Your Tests Live With Your Code

Keep your component tests in the same source code repository as the code that you're testing. That way, as you tag and release new versions of your component, you're also tagging the tests too. You're also more likely to use the tests if they're conveniently to hand.

Here's an example project layout, looking at the root folder of your code project:

<pre>
.storyplayer/
    systems-under-test/
        &lt;system-under-test&gt;-&lt;version&gt;.json
    test-environments/
        vagrant-centos6.json

src/
    ...

tests/
    stories/
        01-provisioning/
            01-CanDeployStory.php
            10-CanStartStory.php
            11-CanStopStory.php
            12-CanRestartStory.php
            ...

        .../

vendor/
    ...

composer.json
dsbuildfile
phpunit.xml.dist
storyplayer.json.dist
Vagrantfile
</pre>

With this layout, you can open a terminal at the root of your project, and all of these commands will work:

{% highlight bash %}
composer install
composer update
vendor/bin/phpunit
vendor/bin/storyplayer
vagrant up
vagrant halt
{% endhighlight %}

It's really handy to have all of your tools work from the same place :)

## .storyplayer/

`.storyplayer/` is the folder where you put your Storyplayer config files for systems under test and test environments.

We'll look at these files in more detail in the next couple of chapters.

## src/

`src/` is where your project's source code already goes.  It might have a different name to `src/`, and that's okay.

Storyplayer has nothing to do with this folder.

## tests/stories/

`tests/stories/` is the folder where you put the tests that Storyplayer will run.

Under here, create a folder for each group of tests.  That way, you can easily run any one group of tests at a time.

For example, the command:

    vendor/bin/storyplayer tests/stories/01-provisioning/

will find all `*Story.php` files in `tests/stories/01-provisioning/`, and run them. Storyplayer uses the filenames to determine the order to run them in.

## vendor/

`vendor/` is the folder where [Composer](http://getcomposer.org) downloads dependencies to.

If you are testing a PHP project, then `vendor/` will contain:

* your project's dependencies
* Storyplayer
* and Storyplayer's dependencies

If you're testing a non-PHP project (e.g. a Java project), you still need to [use Composer](../fundamentals/installing-storyplayer.html) to create the `vendor/` folder. Composer is the de-facto package manager for PHP projects.

## composer.json

`composer.json` is the Composer config file for your project.

If you don't already have one (for example, because your project isn't a PHP project), you still need to [add one in order to install Storyplayer](../fundamentals/installing-storyplayer.html).

## dsbuildfile

`dsbuildfile` is a shell script that is used to build your test environment.

Storyplayer runs this script inside your Vagrant virtual machine during the `Creating Test Environment` step.

## storyplayer.json

`storyplayer.json` or `storyplayer.json.dist` is your main Storyplayer config file.

Use your `storyplayer.json.dist` file config file to avoid typing in parameters on the command-line every time you want to run Storyplayer:

{% highlight json %}
{
    "defaults": [
        "--system-under-test", "&lt;system-under-test&gt;&lt;version&gt;",
        "--test-environment", "vagrant-centos6",
        "play-story", "tests/stories/"
    ]
}
{% endhighlight %}

Save this as `storyplayer.json.dist`, and then you can run Storyplayer like this:

    vendor/bin/storyplayer

and it is exactly the same as

    vendor/bin/storyplayer --system-under-test &lt;system-under-test&gt;&lt;version&gt;
        --test-environment vagrant-centos6
        play-story tests/stories/

## Vagrantfile

`Vagrantfile` is the config file for [Vagrant](http://www.vagrantup.com).

Here's an example `Vagrantfile`, taken from Storyplayer's own code repository:

{% highlight ruby %}
# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::Config.run do |config|
  config.vm.box = "centos-6.3-20140826-1333"
  config.vm.box_url = "https://s3-eu-west-1.amazonaws.com/ds-vagrant-images/centos-6.3-20140826-1333.box"

  # Boot with a GUI so you can see the screen. (Default is headless)
  config.vm.boot_mode = :gui

  # enable bridged networking
  config.vm.network :bridged, :bridge=>ENV["VIRTUALBOX_BRIDGE_ADAPTER"]
end

# provider-specfic configuration
Vagrant.configure("2") do |config|
  config.vm.provider :virtualbox do |vb|
    # enable 4 CPUs by default
    vb.customize ["modifyvm", :id, "--cpus", "2", "--memory", "2048"]

    # change the network card hardware for better performance
    vb.customize ["modifyvm", :id, "--nictype1", "virtio" ]
    vb.customize ["modifyvm", :id, "--nictype2", "virtio" ]

    # suggested fix for slow network performance
    # see https://github.com/mitchellh/vagrant/issues/1807
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
  end
end
{% endhighlight %}
