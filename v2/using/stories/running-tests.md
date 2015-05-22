---
layout: v2/using-stories
title: Running Tests
prev: '<a href="../../using/stories/post-test-inspection.html">Prev: Post-Test Inspection Phase</a>'
next: '<a href="../../using/stories/grouping-stories.html">Next: Grouping Stories</a>'
updated_for_v2: true
---

# Running Tests

One of Storyplayer's strengths is that it's very easy to run as few, or as many, tests as you want to. You can run one test, several tests, a whole group of tests, a whole folder of tests, or all of your tests.

Use Storyplayer's [`play-story` command](../storyplayer-commands/play-story.html) to run your tests.

<div class="callout info" markdown="1">
#### Setting Up Default Parameters

You'll want to add a `defaults` section to your [storyplayer.json file](../configuration/storyplayer-json.html). You can set defaults for common command-line options (such as the system-under-test and the test environment to use) so that you don't have to type them out each time.
</div>

## Run A Single Test

Running a single test is very easy.

{% highlight bash %}
vendor/bin/storyplayer play-story <path-to-file>
{% endhighlight %}

where:

* __&lt;path-to-file&gt;__ is the path to your test

## Run Several Tests

You can run two or more tests at once. Storyplayer will run the tests in the order that you list them.

{% highlight bash %}
vendor/bin/storyplayer play-story <path-to-file-1> <path-to-file-2>
{% endhighlight %}

You can use your UNIX shell's wildcard support, for example:

{% highlight bash %}
vendor/bin/storyplayer play-story <path-to-folder>/10*-Stories.php
{% endhighlight %}

The `*` wildcard is expanded by your UNIX shell before Storyplayer is called.

## Run A Group Of Tests

You can tell Storyplayer to run all of the tests in a folder (and its subfolders):

{% highlight bash %}
vendor/bin/storyplayer play-story <path-to-folder>
{% endhighlight %}

## Run All Tests

To run all tests, point Storyplayer at the folder that contains all of your tests:

{% highlight bash %}
vendor/bin/storyplayer play-story <path-to-top-level-folder>
{% endhighlight %}
