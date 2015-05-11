---
layout: v2/using-stories
title: Story Templates
prev: '<a href="../../using/stories/grouping-stories.html">Prev: Grouping Stories</a>'
next: '<a href="../../using/stories/story-params.html">Next: Story Parameters</a>'
---

# Story Templates

As you build up a larger body of tests - and as your app grows from a single website to also include API services and internal services - you'll find that some of your tests have duplicate phases, especially `TestEnvironmentSetup()` and `TestEnvironmentTeardown()` phases.  You can avoid duplicating code by using Storyplayer's story templating.

## Creating A Story Template

A _story template_ is a PHP class that inherits the `DataSift\Storyplayer\PlayerLib\StoryTemplate` class:

{% highlight php startinline %}
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\StoryTemplate;

class MyTemplate extends StoryTemplate
{
	...
}
{% endhighlight %}

You can put your story template into any PHP namespace that you like.  The only requirement is that Storyplayer's autoloader needs to find it.  Storyplayer will look in the following folders for your PSR0-compliant code:

1. ./php/
1. ./src/php/

Alternatively, you can keep your story templates in a Composer project that is installed via `composer install`, just like Storyplayer is.

For example, at [DataSift](http://datasift.com) our story templates live in the PHP namespace `DataSift\QA\StoryTemplates`, and on disk they live in the folder `php/DataSift/QA/StoryTemplates/`.

## Adding A Phase To A Story Template

Each phase is a PHP method that accepts [the $st object](../prose/the-st-object.html) as the only parameter.

{% highlight php startinline %}
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\StoryTemplate;

class MyTemplate extends StoryTemplate
{
	public function testEnvironmentSetup(StoryTeller $st)
	{
		...
	}

	public function testEnvironmentTeardown(StoryTeller $st)
	{
		...
	}

	public function testSetup(StoryTeller $st)
	{
		...
	}

	public function testTeardown(StoryTeller $st)
	{
		...
	}

	public function preTestPrediction(StoryTeller $st)
	{
		...
	}

	public function preTestInspection(StoryTeller $st)
	{
		...
	}

	public function postTestInspection(StoryTeller $st)
	{
		...
	}
}
{% endhighlight %}

Each method is __optional__.

Inside the method, you can use exactly the same code that you're already using in your story's phases.

## Adding A Template To A Story

Use the `basedOn()` method to make your story use the methods defined in your story template:

{% highlight php startinline %}
use DataSift\QA\StoryTemplates\WebsiteTemplate;

$story = newStoryFor('My App')
         ->inGroup('Registration & Login')
         ->called('Can register a new account')
         ->basedOn(new WebsiteTemplate);
{% endhighlight %}

`basedOn()` takes 1 parameter, which is a `StoryTemplate` object.

## Adding Multiple Templates To A Story

If you ever need a story to use more than one story template, you can use the `andBasedOn()` method:

{% highlight php startinline %}
use DataSift\QA\StoryTemplates\ApiTemplate;
use DataSift\QA\StoryTemplates\WebsiteTemplate;

$story = newStoryFor('My App')
         ->inGroup('Registration & Login')
         ->called('Can register a new account')
         ->basedOn(new WebsiteTemplate)
         ->andBasedOn(new ApiTemplate);
{% endhighlight %}

Storyplayer will use the templates in the order that you add them to the story.