---
layout: v2/modules-iterators
title: foreach(firstHostWithRole())
updated_for_v2: true
prev: '<a href="../../modules/iterators/hostWithRole.html">Prev: foreach(hostWithRole())</a>'
next: '<a href="../../modules/iterators/lastHostWithRole.html">Next: foreach(lastHostWithRole())</a>'
---

# foreach(firstHostWithRole())

## The Iterator

`firstHostWithRole()` allows you to easily perform actions against just one host in your test environment without having to hard-code the host IDs or hostnames into your story.

{% highlight php startinline %}
foreach(firstHostWithRole($roleName) as $hostId) {
    fromHost($hostId)->getStorySetting('pages');
}
{% endhighlight %}

where:

* `$roleName` is the name of the role to search for
* `$hostId` gets set to the host ID of each matching host in turn

## Error Handling

If there are no hosts that match `$roleName`, an exception is thrown. Do not attempt to catch this exception yourself. Let it go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every Storyplayer module action will succeed.

## Discussion

You need to call this iterator in a standard PHP _[foreach()](http://www.php.net/foreach)_ loop. `firstHostWithRole()` is a generator that returns a single matching host ID.