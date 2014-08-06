---
layout: v2/environments
title: Testing On Your Machine
prev: '<a href="../environments/index.html">Prev: Test Environments</a>'
next: '<a href="../environments/local-vms.html">Next: Testing Against Local Virtual Machines</a>'
---

# Testing On Your Machine

Testing against software running on your dev box gives you the fastest workflow whilst you're developing new features.  If you're using dynamic languages, there's normally nothing to install at all - just save your files in your editor - and if you're using a compiled language, your IDE will normally give you incremental builds so that you're ready to test very quickly.

## Setting Up For Testing

To test locally, you need to be able to run your application on your dev box.

* Make sure you can start your application, and that it will run locally
* Use your web browser to manually test your application, to prove that it works well enough for you to start automating your tests
* Make sure you have [Storyplayer installed locally](../../installation.html)

That's pretty much it.

## Configuring Your Environment

For your first stories, you don't need any Storyplayer configuration at all.  As you build your test suite, you'll want to start building up your configuration, to share settings between multiple stories.  We recommend adding all of your configuration settings to [the 'defaults' environment section in your storyplayer.json file](../configuration/storyplayer-json.html) to begin with, and then breaking out any settings that are unique to your environment (URLs, paths and the like) into a [per-environment config file](../configuration/environment-config.html).

## Writing Your Stories

You don't need to create [TestEnvironmentSetup or Teardown phases](../stories/testenvironmentsetup-teardown.html) in your stories, as you'll be deploy / running your software manually before you start your tests.

## Running Your Stories

Running your stories will normally be as simple as:

<pre>
vendor/bin/storyplayer &lt;storyfile&gt;
</pre>

You normally won't need to pass any switches into the Storyplayer command at all.