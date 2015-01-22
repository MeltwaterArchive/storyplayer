---
layout: v2/using-stories
title: Story Parameters
prev: '<a href="../../using/stories/story-templates.html">Prev: Story Templates</a>'
next: '<a href="../../using/stories/test-users.html">Next: Test Users</a>'
---

# Story Parameters

Story parameters are configuration that are defined by your story (and / or by your story templates).  They can be overridden on the command-line when Storyplayer is run, without having to edit any code at all.  They help make your tests flexible and adaptable.

## Defining Story Parameters

Call `$st->setParams()` in your stories and / or `$this->setParams()` in your story templates to set the parameters for your story:

{% highlight php %}
//
// example of how to set story parameters from
// inside a story
//

$story->addTestEnvironmentSetup(function(StoryTeller $st) {
	// build up the list of settings
	//
	// these can be overridden from the command-line
	$st->setParams(array(
		'platform' => 'vagrant-centos6'
	));
});
{% endhighlight %}

{% highlight php %}
//
// example of how to set story parameters from
// inside a story template
//
class MyTemplate extends StoryTemplate
{
	public function testEnvironmentSetup()
	{
		// build up the list of settings
		//
		// these can be overridden from the story,
		// and from the command-line
		$this->setParams(array(
			'platform' => 'vagrant-centos6'
		));
	}
}
{% endhighlight %}

`setParams()` takes 1 parameter: an array of named parameters.

## Retrieving Story Parameters

When you want to get the definitive list of parameters for your story, call `$st->getParams()`:

{% highlight php %}
$story->addTestEnvironmentSetup(function(StoryTeller $st) {
	// build up the list of settings
	//
	// these can be overridden from the command-line
	$st->setParams(array(
		'platform' => 'vagrant-centos6'
	));

	// get the final list of params
	// this will include any changes made from the command-line
	$params = $st->getParams();
});
{% endhighlight %}

`$st->getParams()` will merge (in this order of precidence):

1. any params defined by any of your story templates
1. any params defined by your story
1. any params overridden from the command-line

and return them as a single array for you to use in your code.

## Overriding Story Parameters From The Command-Line

Use the `-D` switch to override any params from the command-line:

<pre>
vendor/bin/storyplayer -D platform=ec2-centos6 ...
</pre>

The `-p / --platform` switch is the same as the `-D platform=` switch:

<pre>
vendor/bin/storyplayer -P ec2-centos6
vendor/bin/storyplayer --platform=ec2-centos6
vendor/bin/storyplayer -D platform=ec2-centos6
</pre>

## Uses Of Story Parameters

Many of our stories support deploying our code onto several alternative platforms, such as CentOS 5 using Vagrant, CentOS 6 using Vagrant, and CentOS 6 on Amazon EC2.  We add support for all of these platforms in our `TestEnvironmentSetup()` functions, and use story parameters to set which platform we want to use:

{% highlight php %}
class DeployableServiceTemplate extends StoryTemplate
{
	public function testEnvironmentSetup(StoryTeller $st)
	{
		// our params
		$this->setParams(array(
			'platform' => 'vagrant-centos6'
		));

		// get the final list of params
		$params = $st->getParams();

		// which platform are we deploying to?
		switch (strtolower($params['platform']))
		{
			case 'ec2-centos6':
				...
				break;

			case 'vagrant-centos5':
				...
				break;

			case 'vagrant-centos6':
				...
				break;
		}
	}
}
{% endhighlight %}

The call to `$this->setParams()` sets the default platform (in this case, _vagrant-centos6_), but we can use Amazon EC2 instead simply by using '-D platform=ec2-centos6' when we run Storyplayer.