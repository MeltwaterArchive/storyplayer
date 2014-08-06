---
layout: v1/modules-provisioning
title: Adding Additional Provisioning Engines
prev: '<a href="../../modules/provisioning/usingProvisioningEngine.html">Prev: usingProvisioningEngine()</a>'
next: '<a href="../../modules/savaged/index.html">Next: The SavageD Module</a>'
---

# Adding Additional Provisioning Engines

Storyplayer ships with support for the following provisioning engine(s):

* [Ansible](http://ansible.cc/)

If you'd like to add support for your favourite provisioning engine, here's what you need to know.

## Extending The Provisioning Definition

When users create and build up a [provisioning definition](provisioning-definition.html), it is important that they do so using the right terminology for whatever provisioning engine that they are going to use.

For example:

{% highlight php %}
$st->usingProvisioningDefinition($def)->addVars($params)->toHost($hostName);
$st->usingProvisioningDefinition($def)->addVars($params)->toGroup($groupName);
{% endhighlight %}

is natural to someone using Ansible, because Ansible users should be familiar with the concept of both *host\_vars* and *group\_vars*.  These terms, however, might not mean anything to someone who is working with a different provisioning engine.

You'll probably need to add extra methods to the class _DataSift\Storyplayer\Prose\ProvisioningDefinitionActions_, and possibly too to _DataSift\Storyplayer\ProvisioningLib\DelayedProvisioningDefinitionActions_ to support new terminology.

## Provisioners

Each supported provisioning engine needs a class inside the _DataSift\Storyplayer\ProvisioningLib\Provisioners_ namespace.

Storyplayer loads these classes dynamically, based on the `$engineName` parameter passed to _[usingProvisioningEngine()](usingProvisioningEngine.html)_:

{% highlight php %}
$className = ucfirst($engineName) . "Provisioner";
$fqClassName = 'DataSift\Storyplayer\ProvisioningLib\Provisioners\\' . $className;

$obj = new fqClassName($st);
{% endhighlight %}

For example, the `$engineName` of "ansible" becomes the fully-qualified class name of _DataSift\Storyplayer\ProvisioningLib\Provisioners\AnsibleProvisioner_.

Each provisioner needs to implement just one public method, _provisionHosts()_.  This method accepts a single _ProvisioningDefinition_ object, and executes all of the steps required to provision every host listed in the provisioning definition.