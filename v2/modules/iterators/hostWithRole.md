---
layout: v2/modules-iterators
title: foreach(hostWithRole())
prev: '<a href="../../modules/iterators/index.html">Prev: Iterators</a>'
next: '<a href="../../modules/log/index.html">Next: The Log Module</a>'
updated_for_v2: true
---

# foreach(hostWithRole())

## The Iterator

`foreach(hostWithRole())` allows you to easily perform actions against all hosts in your test environment without having to hard-code the host IDs or hostnames into your story.

{% highlight php startinline %}
foreach(hostWithRole($roleName) as $hostId) {
    expectsHost($hostId)->packageIsInstalled("my-app");
}
{% endhighlight %}

where:

* `$roleName` is the name of the role to search for
* `$hostId` gets set to the host ID of each matching host in turn

## Error Handling

If there are no hosts that match `$roleName`, an exception is thrown. Do not attempt to catch this exception yourself. Let it go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every Storyplayer module action will succeed.

## Discussion

You need to call this iterator in a standard PHP _[foreach()](http://www.php.net/foreach)_ loop. `hostWithRole()` is a generator that returns each matching host ID in turn.