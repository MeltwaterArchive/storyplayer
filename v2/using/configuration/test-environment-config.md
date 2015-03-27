---
layout: v2/using-configuration
title: Test Environment Configuration
prev: '<a href="../../using/configuration/system-under-test-config.html">Prev: System Under Test Configuration</a>'
next: '<a href="../../using/configuration/user-config.html">Next: Per-User Configuration</a>'
---

# Test Environment Configuration

Placeholder.

## Config File

{% highlight json%}
[
	{
		"type": "<group-adapter>",
		"details": {
			"machines": {
				"<machine-name>": {
					"osName": "<operating-system-adapter>"
					... machine details ...
					"storySettings": {
						...
					},
					"roles": [
						"role #1",
						"role #2",
						...
					]
				}
			}
		}
	}
]
{% endhighlight %}

## Group Adapters

A list of the different groups you can define.

## Operating System Adapters

A list of the different operating systems we know how to talk to.

## Provisioning Engines

Details about the different provisioners supported.

## Localhost

Details about the special case 'localhost' need adding.

* localhost is always added to your test environment
* Storyplayer also creates a 'localhost' target (which is the default target too)