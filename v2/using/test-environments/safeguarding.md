---
layout: v2/using-test-environments
title: Safeguarding Environments
prev: '<a href="../../using/test-environments/vagrant.html">Prev: Creating Test Environments Using Vagrant</a>'
next: '<a href="../../tips/index.html">Next: Tips For Using Storyplayer</a>'
---

# Safeguarding Environments

Storyplayer is designed to allow you to run the same tests against each of your environments, so that you don't have to write different tests for your development and production environments.  However, there'll be some tests that simply aren't safe to run against production (for example, a _Delete User_ function).

## Safeguarding An Environment

To protect an environment against dangerous tests, add the `mustBeWhitelisted` config option to your environment:

{% highlight json %}
{
	"environments": {
		"production": {
			"mustBeWhitelisted": true
		}
	}
}
{% endhighlight %}

All stories are automatically barred from running against any environment with the `mustBeWhitelisted` config option.

To mark a story as safe to run against a safeguarded environment, use `$story->runsOn()`:

{% highlight php startinline %}
$story->runsOn("production")
      ->andOn("sales-demo");
{% endhighlight %}

`$story->runsOn()` tells Storyplayer that you've decided it is safe for the test to run against the named safeguarded environments.  After that, it's up to you to make sure that no-one in your team uses `$story->runsOn()` inside a dangerous test.