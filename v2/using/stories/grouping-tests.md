---
layout: v2/using-stories
title: Grouping Tests
prev: '<a href="../../using/stories/running-stories.html">Prev: Running Stories</a>'
next: '<a href="../../using/stories/story-templates.html">Next: Story Templates</a>'
updated_for_v2: true
---

# Grouping Tests

A [user story](../../learn/fundamentals/user-stories.html) is a description of _what_ a user can do - not _how_ the user can do it. Your tests describe the _how_. They will also describe different ways that the story can fail, and make sure that your application handles this properly.

There'll normally be more than one test for each user story. As your test coverage grows, you'll want to group your tests together to make them easier to run.

Use your project's folder structure to organise your tests. For example, here's part of Storyplayer's own tests at the time of writing:

<pre>
storyplayer/
  - src/
    - tests/
      - stories/
        - config/
          - 10a-CanGetPerUserModuleSettingsStory.php
        - modules/
          - asserts/
            - 10a-CanAssertIsArrayStory.php
            - 10b-CanAssertArrayContainsKeyStory.php
            - 50a-CanAssertIsObjectStory.php
          - host/
            - 20-CanDetectWhenScreenSessionFailsToStartStory.php
</pre>

## Have a Top-Level Folder For All Your Tests

In the example above, all tests are under a single folder `src/tests/stories`. If I want to run all of Storyplayer's tests in one go, I simply tell Storyplayer:

{% highlight bash %}
vendor/bin/storyplayer play-story src/tests/stories
{% endhighlight %}

Storyplayer will find all the files that end in `Story.php`, and run them.

## Use Sub-Folders For Different Groups

In the example above, all the tests for Storyplayer's config are in the `src/tests/stories/config` folder. I can run only those tests using this command:

{% highlight bash %}
vendor/bin/storyplayer play-story src/tests/stories/config
{% endhighlight %}

## Use Nested Sub-Folders As Your Test Coverage Grows

In the example above, all tests for Storyplayer's modules are in the `src/tests/stories/modules` folder. There's a sub-folder for each module.

If I want to run just the tests for the [Asserts module](../../modules/asserts/index.html), I can run this command:

{% highlight bash %}
vendor/bin/storyplayer play-story src/tests/stories/modules/asserts
{% endhighlight %}

and if I want to test all of the modules in one go, I can run this command:

{% highlight bash %}
vendor/bin/storyplayer play-story src/tests/stories/modules
{% endhighlight %}

## Further Reading

You can find more examples of how you can group Storyplayer tests in our [worked examples](../../learn/worked-examples/index.html) section.