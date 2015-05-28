---
layout: v2/using-stories
title: Story Templates
prev: '<a href="../../using/stories/running-tests.html">Prev: Running Tests</a>'
next: '<a href="../../using/stories/test-users.html">Next: Test Users</a>'
updated_for_v2: true
---

# Story Templates

As you build up a larger body of tests - and as your app grows from a single website to also include API services and internal services - you'll find that some of your tests have duplicate phases, especially [the `TestSetup()` and `TestTeardown()` phases](test-setup-teardown.html).

You can move this duplicate code into a story template.

## Creating A Story Template

A _story template_ is a PHP class that inherits the `DataSift\Storyplayer\PlayerLib\StoryTemplate` class:

{% highlight php startinline %}
use DataSift\Storyplayer\PlayerLib\StoryTemplate;

class MyTemplate extends StoryTemplate
{
    ...
}
{% endhighlight %}

You can put your story template into any PHP namespace that you like.  The only requirement is that Storyplayer's autoloader needs to find it.  Storyplayer will look in the following folders for your PSR0-compliant code:

1. ./storyplayer/php/
1. ./src/php/

Alternatively, you can keep your story templates in a Composer project that is installed via `composer install`, just like Storyplayer is.

For example, at [DataSift](http://datasift.com) our story templates live in the PHP namespace `DataSift\QA\StoryTemplates`, and on disk they live in the folder `php/DataSift/QA/StoryTemplates/`.

## Adding A Phase To A Story Template

Each phase is a PHP method with no parameters.

{% highlight php startinline %}
use DataSift\Storyplayer\PlayerLib\StoryTemplate;

class MyTemplate extends StoryTemplate
{
    /**
     * tell Storyplayer if the test can run, or if it needs to be skipped
     *
     * @return bool
     *         FALSE if the test needs to be skipped
     *         TRUE otherwise
     */
    public function testCanRunCheck()
    {
        //  code goes here ...
    }

    /**
     * create the pre-conditions for the test to run
     *
     * this might include loading specific test data into the system under
     * test, or making changes to the test environment, or registering a
     * test user
     *
     * @return void
     */
    public function testSetup()
    {
        //  code goes here ...
    }

    /**
     * undo all the changes made in testSetup()
     *
     * @return void
     */
    public function testTeardown()
    {
        //  code goes here ...
    }

    /**
     * is this test expected to pass, or is it expected to fail?
     *
     * @return bool
     *         FALSE if the test is expected to fail
     *         TRUE otherwise
     */
    public function preTestPrediction()
    {
        //  code goes here ...
    }

    /**
     * capture the current state of the system under test in the checkpoint
     * before we perform any actions
     *
     * this data is used in the postTestInspection to see if our actions have
     * actually made any permanent changes to the system under test and/or
     * the test environment
     *
     * @return void
     */
    public function preTestInspection()
    {
        //  code goes here ...
    }

    /**
     * check the current state of the system under test, to work out if our
     * actions have actually made any permanent changes to the system under
     * test and/or the test environment
     *
     * @return void
     */
    public function postTestInspection()
    {
        //  code goes here ...
    }
}
{% endhighlight %}

Each method is __optional__.

Inside the method, you can use exactly the same code that you're already using in your story's phases.

## Adding A Template To A Test

Use the `basedOn()` method to make your test use the methods defined in your story template:

{% highlight php startinline %}
use DataSift\QA\StoryTemplates\WebsiteTemplate;

$story = newStoryFor('My App')
         ->inGroup('Registration & Login')
         ->called('Can register a new account')
         ->basedOn(new WebsiteTemplate);
{% endhighlight %}

`basedOn()` takes 1 parameter, which is a `StoryTemplate` object.

## Adding Multiple Templates To A Test

If you ever need a test to use more than one story template, you can use the `andBasedOn()` method:

{% highlight php startinline %}
use DataSift\QA\StoryTemplates\ApiTemplate;
use DataSift\QA\StoryTemplates\WebsiteTemplate;

$story = newStoryFor('My App')
         ->inGroup('Registration & Login')
         ->called('Can register a new account')
         ->basedOn(new WebsiteTemplate)
         ->andBasedOn(new ApiTemplate);
{% endhighlight %}

Storyplayer will use the templates in the order that you add them to the test.