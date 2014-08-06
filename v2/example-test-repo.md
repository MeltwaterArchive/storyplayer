---
layout: top-level
title: An Example Test Repository
prev: '<a href="running-storyplayer.html">Prev: Running Storyplayer</a>'
next: '<a href="configuration/index.html">Next: Configuring Storyplayer</a>'
---

# An Example Test Repository

To get started with Storyplayer, we recommend setting up a separate VCS repository to keep all of your tests in one place.

## Example Structure

A typical project structure would look like this:

<pre>
storyplayer.json.dist
bin/
    - contains test tools for our tests
etc/
    - contains storyplayer config files
php/
    DataSift/
        - contains DataSift's test environment setup/teardown classes
    Prose/
        - contains additional Storyplayer modules
stories/
    account-management/
        settings/
    billing/
        credit-cards/
        subscriptions/
    registration/
        login/
        signup/
            - each of these contain Storyplayer test scripts
tales/
    account-management/
        overnight-tests.json
        post-deployment-tests.json
        smoke-tests.json
    billing/
        overnight-tests.json
        post-deployment-tests.json
        smoke-tests.json
    registration/
        login/
            overnight-tests.json
            post-deployment-tests.json
            smoke-tests.json
        signup/
            overnight-tests.json
            post-deployment-tests.json
            smoke-tests.json
</pre>

### The storyplayer.json File

When you run Storyplayer, the first thing that it does is look for the [storyplayer.json](configuration/storyplayer-json.html) config file.  This file contains all of [the default settings](configuration/app-settings.html) for your tests.  If Storyplayer cannot find it, Storyplayer will halt with an error.

### Additional Config Files

For larger teams, it can sometimes be very helpful to split out some of the configuration into [per-environment override files](configuration/environment-config.html).

By default, Storyplayer looks for these inside the _etc/_ folder in your structure.  We'll make this location configurable in a future release.

### Additional Test Tools

For larger applications, it can sometimes be very helpful to have Storyplayer start and stop additional test tools.  You'll need to do this the moment you want your tests to do two or more things simultaneously, as PHP has no multi-threading support at this time.  We recommend putting these tools inside the _bin/_ folder in your structure.

Storyplayer does __not__ add this folder to your PATH.

### Your PHP Code

The great thing about Storyplayer is that each test is a real PHP script.  There's nothing stopping you creating your own PHP classes to help with the testing, such as [your own Prose modules](prose/creating-prose-modules.html) or a PHP class to share your [test environment setup/teardown steps](stories/test-environment-setup-teardown.html) between stories.

By default, Storyplayer treats the _php/_ folder as containing a PSR-0-compliant code tree.  Our autoloader will look inside that folder for your additional classes.  We'll make this location configurable in a future release.

### Your Tests

Each test file that you create is for a [user](stories/user-stories.html) or a [service](stories/service-stories.html) story, and has two levels of categorisation.  We recommend creating folders for each of these categories, and putting your stories in there.  It keeps things organised in the long run.

By convention, your stories should go inside the _stories/_ folder.

### Batches Of Tests

When you have a new release of your app to test, the thing you'll probably want to do is to re-run your existing tests first, to make sure that no bugs have been introduced in the new release.  Running each test by hand, one at a time, isn't much fun.  That's why we added support for [running batches of stories](running-storyplayer.html#running_batches_of_stories) in version 1.2.

We call these batches 'tales' (to keep with the story theme), and by convention, they should go inside the _tales_ folder.