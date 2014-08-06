---
layout: v2/modules-hoststable
title: How Hosts Are Remembered
prev: '<a href="../../modules/hoststable/index.html">Prev: The HostsTable Module</a>'
next: '<a href="../../modules/hoststable/fromHostsTable.html">Next: fromHostsTable()</a>'
---

# How Hosts Are Remembered

Most test data is discarded when a test finishes executing, but _hosts_ - computers that Storyplayer knows about - are treated as a special case.

## The Storyplayer Hosts Table

When Storyplayer learns about a new host (for example, [when a new Vagrant virtual machine is created](../vagrant/usingVagrant.html#createvm)), Storyplayer adds the details about that host to an internal data structure that we call the __hosts table__.

To see the hosts table, you can use the following code inside one of your stories:

{% highlight php %}
var_dump($st->fromHostsTable()->getHostsTable());
{% endhighlight %}

The hosts table is a PHP object.  It contains an attribute for every host that we currently know about, and each of these attributes is also a PHP object containing information about that host.

Here's an example *var_dump()* of the hosts table:

{% highlight php %}
class DataSift\Stone\ObjectLib\BaseObject#21 (1) {
  public $ogre =>
  class DataSift\Stone\ObjectLib\BaseObject#94 (9) {
    public $name =>
    string(4) "ogre"
    public $osName =>
    string(7) "centos6"
    public $homeFolder =>
    string(18) "qa/ogre-centos-6.3"
    public $type =>
    string(9) "VagrantVm"
    public $sshUsername =>
    string(7) "vagrant"
    public $sshOptions =>
    array(1) {
      [0] =>
      string(49) "-i '/home/stuart/.vagrant.d/insecure_private_key'"
    }
    public $dir =>
    string(67) "/home/stuart/Devel/datasift/vagrant-environments/qa/ogre-centos-6.3"
    public $ipAddress =>
    string(14) "172.16.218.184"
    public $provisioned =>
    bool(true)
  }
}
{% endhighlight %}

This table contains one host, 'ogre', which is a Vagrant virtual machine.  The virtual machine is running some version of CentOS 6.x.  We also know the path to the machine's Vagrantfile, the machine's current IP address, and we have a dedicated SSH key to use to allow us to SSH into the machine to run commands.

The hosts table is persistent; it is preserved between runs of `storyplayer`, because it is stored inside Storyplayer's [runtime config](../../configuration/runtime-config.html).  The _runtime config_ is a data structure that gets saved out to disk after every run of `storyplayer`.

Why do we save the hosts table between executions?  It makes it a lot easier to run _smoke tests_, and it saves a lot of time when creating complex new tests.

## Smoke Testing

[Smoke tests](http://en.wikipedia.org/wiki/Smoke_testing) are the first tests that you run after a new build of a piece of software.  They're chosen to quickly catch the most basic of problems (such as failing to deploy or run), and they're normally run first to save you time if you've been given a broken build.

During smoke tests, it's normally fine to re-use the virtual machine created by the first test that you execute, instead of re-creating each test in turn.  Yes, this makes the individual test non-deterministic, but the set of tests as a whole remains deterministic, as the tests run in strict order.

Running batches of tests will be supported by Storyplayer soon.

## Creating New Tests

Even with the latest SSDs, creating and provisioning a virtual machine doesn't happen instantly.  If you're writing a test that runs against a virtual machine, you can waste a lot of time waiting for the virtual machine to be created every time you run your test.

You can save yourself time, and skip the virtual machine being created, by disabling the TestEnvironmentSetup and TestEnvironmentTeardown phases:

1. First run, just disable TestEnvironmentTeardown.  The virtual machine will be created as normal during the TestEnvironmentSetup phase, and it will continue to run after your test exits or crashes.
1. Subsequent runs, disable both TestEnvironmentSetup and TestEnvironmentTeardown.  Your tests will continue to run against the virtual machine that you created during your first run.
1. When you've finished writing your test, enable both TestEnvironmentSetup and TestEnvironmentTeardown, and run the test once more to prove that it works against a newly-created virtual machine.

To disable test phases, you can edit the [phases section in storyplayer.json](../../configuration/storyplayer.json#phases), or simply comment out the phase in your test.