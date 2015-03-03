---
layout: v2/learn-test-your-code
title: Writing Component Tests
prev: '<a href="../../learn/test-your-code/designing-component-tests.html">Prev: Designing Component Tests</a>'
next: '<a href="../../learn/test-your-code/final-thoughts.html">Next: Final Thoughts</a>'
---
# Writing Component Tests

After designing your tests, it's time to sit down and write your tests for Storyplayer.

## The Steps To Follow

Every component test follows this 4-step plan:

1. Create the conditions for your test
1. Perform an action
1. Check to see if the action did what it was expected to
1. Undo any changes done in Step 1.

## Step 1: Create The Test Conditions

The first step in any component test is to create the conditions that the test itself needs. You do this by adding a `TestSetup` phase to your story.

{% highlight php startinline %}
$story->addTestSetup(function() {
    // cleanup after ourselves
    foreach (hostWithRole('upload_target') as $hostId) {
        usingHost($hostId)->uploadFile(__DIR__ . '/testfile.txt', "testfile.txt");
    }
});
{% endhighlight %}

In this example, the story uploads a data file to each machine in the test environment that has the `upload_target` role.  Later on, our test will use this data file.

<div class="callout info" markdown="1">
#### TestSetup Is Optional

If your test doesn't need any specific changes, you do not need to add a `TestSetup` phase to your story.
</div>

## Step 2: Perform An Action

The next step is to do something - perform an action that is expected to work. You do this by adding an `Action` phase to your story:

{% highlight php startinline %}
// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    foreach (hostWithRole('upload_target') as $hostId) {
        fromHost($hostId)->downloadFile('testfile.txt', "/tmp/testfile-{$hostId}.txt");
    }
});
{% endhighlight %}

In this example, we download a data file from each machine in the test environment that has the `upload_target` role.

<div class="callout info" markdown="1">
#### Errors Cause Exceptions

Notice how we didn't check whether the file download was successful? That's because the Storyplayer modules do this for us.

If the file download failed, the [Host module](../../modules/host/index.html) would have thrown an exception. Storyplayer would then have caught the exception, and marked the `Action` phase as having failed.

__Always write your tests assuming no errors have occurred.__ This makes your tests really clean and easy to read.
</div>

## Step 3: Inspect Afterwards

Assuming that the `Action` phase was successful, the next step in your component test is to double-check everything to make sure that the `Action` phase actually did something. This is the _deep inspection_ that we talk more about in our [Belt and Braces Test Philosophy](../fundamentals/belt-and-braces-testing.html) discussion.

{% highlight php startinline %}
$story->addPostTestInspection(function() {
    // we should have a file for each host in the configuration
    foreach (hostWithRole('upload_target') as $hostId) {
        $filename = '/tmp/testfile-' . $hostId . '.txt';
        if (!file_exists($filename)) {
            usingLog()->writeToLog("file not downloaded from host '$hostId'");
            usingErrors()->throwException("file '{$filename}' not downloaded");
        }
    }
});
{% endhighlight %}

In our `Action` example, we downloaded a data file from each host with the role `upload_target` in the test environment. Now we need to make sure that the file was actually downloaded.

<div class="callout info" markdown="1">
#### Your Tests Are Plain Old PHP

Did you notice that we call PHP's `file_exists()` function to check whether or not our downloaded file has actually been downloaded? This is an example of one of Storyplayer's key features.

__All of your tests are valid PHP code.__ They just happen to use Storyplayer's [many modules](../../modules/index.html) to save time and trouble, and to handle errors for you. But, if there's no Storyplayer module that does what you need, you can do anything that PHP lets you do.

That said, if you find yourself repeating the same code across many tests, you should [write your own Storyplayer module](../writing-a-module/index.html).
</div>

## Step 4: Undo Step 1

Finally, if you added a `TestSetup` phase to your story, you need to undo whatever the `TestSetup` phase did to your test environment. You need to reset your test environment back to however it was before your test started.

This is done by adding a `TestTeardown` phase to your story.

{% highlight php startinline %}
$story->addTestTeardown(function() {
    // cleanup after ourselves
    foreach (hostWithRole('upload_target') as $hostId) {
    	// remove the file from the test environment
        usingHost($hostId)->runCommand("if [[ -e testfile.txt ]] ; then rm -f testfile.txt ; fi");

        // remove the file from our computer too
        $filename = '/tmp/testfile-' . $hostId . '.txt';
        if (file_exists($filename)) {
            // tidy up
            unlink($filename);
        }
    }
});
{% endhighlight %}

In this example, `TestTeardown` does two things:

1. it logs into the test environment, and removes the data files uploaded in the `TestSetup` phase
1. it checks the computer where Storyplayer is running, and removes any data files successfully downloaded by the `Action` phase

<div class="callout info" markdown="1">
#### TestTeardown Always Runs

If your `Action` phase failed with an error, Storyplayer marks the test as having failed, and does not bother running your `PostTestInspection` phase. But it will still call any `TestTeardown` phase that you've added to your story.

This ensures that you can always clean up after a test, even if the test itself failed.

In our example, that's why it is our `TestTeardown` phase that removes the downloaded data files.

* We can't remove them in the `Action` phase, because we want to check them in the `PostTestInspection` phase.
* We can't remove them in the `PostTestInspection` phase, because `PostTestInspection` might not get called at all.
</div>

<div class="callout info" markdown="1">
#### Test Environments Persist Between Stories

Why do we have a `TestTeardown` phase at all?

Most of the time, you won't be using Storyplayer to run a single story (although you can if you want to). You'll be running several stories - perhaps even all of your stories in a single run.  Storyplayer builds the test environment at the start of each run, and destroys the test environment only when the run is complete.

If your tests make a mess of the test environment, then the next test (or the one after that) might fail simply because the test environment is no longer in a useful condition.

That's why it's important that every test that has a `TestSetup` phase also has a `TestTeardown` phase to put the test environment back to however it was before that test ran.
</div>

## Putting It All Together

Here's what our final test looks like, in full:

{% highlight php startinline linenos %}
<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Hosts')
         ->called('Can download a file');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
    // cleanup after ourselves
    foreach (hostWithRole('upload_target') as $hostId) {
        usingHost($hostId)->uploadFile(__DIR__ . '/testfile.txt', "testfile.txt");
    }
});

$story->addTestTeardown(function() {
    // cleanup after ourselves
    foreach (hostWithRole('upload_target') as $hostId) {
    	// remove the file from the test environment
        usingHost($hostId)->runCommand("if [[ -e testfile.txt ]] ; then rm -f testfile.txt ; fi");

        // remove the file from our computer too
        $filename = '/tmp/testfile-' . $hostId . '.txt';
        if (file_exists($filename)) {
            // tidy up
            unlink($filename);
        }
    }
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
    foreach (hostWithRole('upload_target') as $hostId) {
        fromHost($hostId)->downloadFile('testfile.txt', "/tmp/testfile-{$hostId}.txt");
    }
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
    // we should have a file for each host in the configuration
    foreach (hostWithRole('upload_target') as $hostId) {
        $filename = '/tmp/testfile-' . $hostId . '.txt';
        if (!file_exists($filename)) {
            usingLog()->writeToLog("file not downloaded from host '$hostId'");
            usingErrors()->throwException("file '{$filename}' not downloaded");
        }
    }
});
{% endhighlight %}