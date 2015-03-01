---
layout: v2/modules-provisioning
title: The Provisioning Module
prev: '<a href="../../modules/processestable/index.html">Prev: The Processes Table Module</a>'
next: '<a href="../../modules/provisioning/provisioning-definition.html">Next: Creating The Provisioning Definition</a>'
---

# The Provisioning Module

The __Provisioning__ module allows you to deploy exactly the software that you need to a real or virtual machine, to increase how repeatable your tests are each time they are run.

The source code for this Prose module can be found in this PHP class:

* DataSift\Storyplayer\Prose\UsingProvisioner
* DataSift\Storyplayer\Prose\UsingProvisioning
* DataSift\Storyplayer\Prose\UsingProvisioningDefinition

## Building Test Environments

We have [a whole section of this manual devoted to test environments](../../environments/index.html) - __that's how important we believe test environments are.__

## Dependencies

You need to choose a provisioning engine, and install it.  You also need to prepare and test your provisioning instructions (be it Ansible playbooks, Chef recipies, or Puppet manifests) in advance.

At the moment, we support [Ansible](http://ansible.cc/) the best (because it's what we use internally for our test environments), but we're keen to support [Chef](http://www.opscode.com/chef/) and [Puppet](https://puppetlabs.com/) equally - pull requests are most welcome if code changes are needed.

## Configuring The Provisioning Module

This is an example configuration for using [Ansible](http://ansible.cc) as your provisioning engine:

{% highlight json %}
{
    "environments": {
        "thor": {
            "ansible": {
                "dir": "/home/stuart/Devel/datasift/qa-ansible-playbooks",
                "playbook": "main-vagrant.yml",
                "privateKey": "/home/stuart/.vagrant.d/insecure_private_key"
            }
        }
    }
}
{% endhighlight %}

## Using The Provisioning Module

The general flow of building a test environment is:

1. Define any host-specific parameters that you need to inject into your provisioning engine
1. Create a physical or virtual host (e.g. using the [Vagrant](../vagrant/index.html) module)
1. Create a [provisioning definition](provisioning-definition.html)
1. [Use a provisioning engine](usingProvisioningEngine.html) to deploy your software

## An Example

Here's a `testEnvironmentSetup()` method from one of our internal [story templates](../../stories/templates.html), which handles setting up a test environment for our Pickle engine:

{% highlight php %}
    public function testEnvironmentSetup(StoryTeller $st)
    {
        // what params has the caller passed in?
        $params = $this->getParams();

        // what is our public IP address?
        $ourIpAddress = $st->getEnvironment()->host->ipAddress;

        // create the parameters to inject into the test box
        $vmParams = array (
            // picklenode parameters
            "pickle_node_prism_endpoint" => "tcp://${ourIpAddress}:5093",
            "pickle_node_acl_endpoint" => "tcp://{$ourIpAddress}:5090",
            "pickle_node_connection_manager_command_endpoint" => "tcp://{$ourIpAddress}:5002",
            "pickle_node_connection_manager_acknowledgement_endpoint" => "tcp://{$ourIpAddress}:5003",
            "pickle_node_connection_manager_request_endpoint" => "tcp://{$ourIpAddress}:5004",
            "pickle_node_connection_manager_http_host" => "{$ourIpAddress}",
            "pickle_node_connection_manager_http_port" => "8101",
            "pickle_node_definition_manager_http_host" => "pndebug.reh.favsys.net",
            "pickle_node_definition_manager_http_port" => "88",
            "user" => "vagrant"
        );

        // we need a VM to host a real ACL
        $st->usingVagrant()->createVm('pickle-node', $params['platform'], $params['vagrantDir']);

        // build up the provisioning definition
        $def = $st->usingProvisioning()->createDefinition();
        $st->usingProvisioningDefinition($def)->addRole('qa-pickle-node')->toHost('pickle-node');
        $st->usingProvisioningDefinition($def)->addParams($vmParams)->toHost('pickle-node');

        // provision our VM
        $st->usingProvisioner('ansible')->provisionHosts($def);

        // make sure the ACL is installed and running
        $st->expectsHost('pickle-node')->packageIsInstalled('ms-service-picklenode');
        $st->expectsHost('pickle-node')->processIsRunning('pickle-node');
    }
{% endhighlight %}