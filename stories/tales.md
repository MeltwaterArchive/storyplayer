---
layout: stories
title: Tales
prev: '<a href="../stories/test-users.html">Prev: Test Users</a>'
next: '<a href="../prose/index.html">Next: Introducing Prose</a>'
---

# Tales

As your collection of stories grows, running each story one at a time can get a bit tedious.  Whether it's checking nightly builds of your app, or checking the release candidate before it is installed in production, running all of your related stories as a batch can be a great help.

In keeping with the whole 'story' theme, we call these batches of stories _tales_.

## Creating A Tale

A _tale_ is a JSON file containing a list of stories to run in order:

{% highlight json %}
{
	"stories": [
		"path-to-story-1.php",
		"path-to-story-2.php"
	]
}
{% endhighlight %}

where:

* _stories_ is an array of Storyplayer story files to run.

## Running A Tale

Running a tale is very similiar to [running a single story](../running-storyplayer.html):

<pre>
vendor/bin/storyplayer [ -e &lt;environment&gt; ] &lt;path-to-tale.json&gt;
</pre>

## Reusing Test Environments In Your Tale

Creating and destroying test environments for each story in a tale can take a lot of time - sometimes longer than the actual tests take to run.  You can tell Storyplayer to avoid re-creating the test environment for each story by using the _reuseTestEnvironment_ option in your tale JSON:

{% highlight json %}
{
	"stories": [
		"path-to-story-1.php",
		"path-to-story-2.php"
	],
	"options": {
		"reuseTestEnvironment": true
	}
}
{% endhighlight %}

When you use the _reuseTestEnvironment_ option, Storyplayer will call the [TestEnvironmentSetup](test-environment-setup-teardown.html) in the first story (if it has one), and the TestEnvironmentTeardown in the last story in your tale (if it has one).

This option works best when all of the stories in a tale are based on the same [story template](story-templates.html).