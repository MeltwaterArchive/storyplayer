---
layout: v2/modules-iterators
title: Iterators
prev: '<a href="../../modules/imap/index.html">Prev: The IMAP Module</a>'
next: '<a href="../../modules/iterators/hostWithRole.html">Next: foreach(hostWithRole())</a>'
updated_for_v2: true
---

# Iterators

## What Are Iterators?

_Iterators_ help you write stories that work with multiple test environments. You use iterators to discover hosts by their assigned role, so that your story does not need to have hostnames or host IDs hardcoded in.

{% highlight php startinline %}
foreach(hostWithRole('frontend') as $hostId) {
    expectsHost($hostId)->packageIsInstalled("my-app");
}
{% endhighlight %}

## Why Do We Need Iterators?

In Storyplayer v1, it was very difficult to run the same story against multiple test environments. Our stories ended up with a hard-coded list of hostnames, and fragile `switch` statements where the story acted differently depending on the test environment in use. These stories proved difficult to maintain and extend - exactly the opposite of what we set out to achieve.

To fix this, Storyplayer v2 introduced several important changes:

* test environments are no longer created by stories
* hosts in test environments have IDs as their primary key, and roles for discovery purposes
* stories now use a host's role to perform actions

_Iterators_ are how a story searches for hosts by the host's assigned role.

You can learn more about when to use iterators in our [How To Test Your Platform](../../learn/test-your-platform/index.html) guide, and in our [Worked Examples](../../learn/worked-examples/index.html).

## Performing An Action Against One Host Out Of Many

When you need to work with just a single host, use one of these iterators:

* _[foreach(firstHostWithRole())](firstHostWithRole.html)_
* _[foreach(lastHostWithRole())](lastHostWithRole.html)_

## Performing An Action Against Many Hosts

When you need to work with all the hosts that have a given role, use this iterator:

* _[foreach(hostWithRole())](hostWithRole.html)_