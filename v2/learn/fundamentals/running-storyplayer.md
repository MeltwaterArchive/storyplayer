---
layout: v2/learn-fundamentals
title: Running Storyplayer
prev: '<a href="../../learn/fundamentals/installing-storyplayer.html">Prev: Installing Storyplayer</a>'
next: '<a href="../../learn/fundamentals/understanding-stories.html">Next: Understanding Stories</a>'
---
# Running Storyplayer

## From The vendor/ Folder

If you've [installed Storyplayer using Composer](installing-storyplayer.html), then Storyplayer will be installed into your project's `vendor/` folder.

The general structure for running Storyplayer is:

    vendor/bin/storyplayer -s <system-under-test> -t <target> <path-to-stories>

That will run any files ending in `Story.php` in the folder `<path-to-stories>`. It will run those stories against the test environment called `target`, with any settings that you've configured for `<system-under-test>`.  We'll look at the [system-under-test](understanding-system-under-test.html) and [test environments](understanding-test-environments.html) shortly.

## Setting Defaults For Storyplayer

If you use the same command-line arguments all the time, you can put them into a config file called `storyplayer.json`:

    {
    	"defaults": [
    		"-s", "<system-under-test>",
    		"-t", "<target>",
    		"play-story", "<path-to-stories>"
    	]
    }

`defaults` is an array of command-line parameters - one entry per command-line parameter. Anything you can put on the command-line, you can put into this array :)

Put `storyplayer.json` in the same folder as your `composer.json` file.

## storyplayer.log

Whenever you run Storyplayer, it creates a file called `storyplayer.log` which contains a detailed log of everything that it has done.

By default, each line in the log is truncated to 100 characters.  This is to prevent pages and pages of PHP output filling the logs when there are errors.  If you need to switch off this protection, use the `--verbose` flag:

    vendor/bin/storyplayer --verbose ...

## Getting Help

Storyplayer has built-in help for all of its commands.

    vendor/bin/storyplayer help

We also have [documentation for each Storyplayer command](../../using/storyplayer-commands/index.html) here in the manual.