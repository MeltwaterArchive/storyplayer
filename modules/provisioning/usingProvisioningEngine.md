---
layout: modules-provisioning
title: useProvisioningEngine()
prev: '<a href="../../modules/provisioning/provisioning-definition.html">Prev: Creating The Provisioning Definition</a>'
next: '<a href="../../modules/provisioning/adding-more-engines.html">Next: Adding Additional Provisioning Engines</a>'
---
# useProvisioningEngine()

_usingProvisioningEngine()_ allows you to take a [provisioning definition](provisioning-definition.html) and apply it to one or more hosts.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingProvisioningEngine_.

## Behaviour And Return Codes

If the action succeeds, the action returns control to your code, and does not return a value.

If the action fails, the action throws an exception. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## provisionHosts()

Use `$st->usingProvisioningEngine()->provisionHosts()` to apply a provisioning definition.

{% highlight php %}
$st->usingProvisioningEngine($engineName)->provisionHosts($def);
{% endhighlight %}

where:

* _$engineName_ is a supported provisioning engine (currently only `ansible` is supported)
* _$def_ is the provisioning definition that you've already built