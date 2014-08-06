---
layout: v1/top-level
title: Running Storyplayer
prev: '<a href="installation.html">Prev: Installing Storyplayer</a>'
next: '<a href="example-test-repo.html">Next: An Example Test Repository</a>'
---

# Running Storyplayer

Storyplayer is a command-line utility.

_You can run `storyplayer --help` to get a list of all of the options and commands that Storyplayer supports._

## Running A Single Story

Running a single story is very straight-forward.

<pre>
vendor/bin/storyplayer [ -e &lt;environment&gt; ] &lt;path-to-story.php&gt;
</pre>

where:

* _environment_ is one of the environments listed in your [configuration file](configuration/index.html). If you only have one environment defined, the -e switch does nothing. Otherwise, the default &lt;environment&gt; is your computer's hostname.
* _path-to-story.php_ is the path to the PHP script containing the story that you want to run

## Running Batches Of Stories

Storyplayer can run a batch of stories too.  We call these batches _tales_, in keeping with the story telling theme.

<pre>
vendor/bin/storyplayer [ -e &lt;environment&gt; ] &lt;path-to-tale.json&gt;
</pre>

where:

* _environment_ is one of the environments listed in your [configuration file](configuration/index.html). If you only have one environment defined, the -e switch does nothing. Otherwise, the default &lt;environment&gt; is your computer's hostname.
* _path-to-tale.json_ is the path to the JSON file containing the list of stories that you want to run

### An Example tale.json File

{% highlight json %}
{
	"stories": [
		"stories/registration/signup/CanRegisterUsingRegistrationForm.php",
		"stories/registration/signup/CanRegisterUsingTwitterAuth.php",
		"stories/registration/signup/CanRegisterUsingFacebookAuth.php"
	],
	"options": {
		"reuseTestEnvironment": true
	}
}
{% endhighlight %}

### Structure Of A tale.json File

Your JSON file must contain the following sections:

* _stories_: an array of stories to run.  This is just an array of the &lt;path-to-story.php&gt; parameters that you'd use when just running a single story from the command line.

Your JSON file can also contain the following optional options:

* _reuseTestEnvironment_: (default is _false_) - when _true_, Storyplayer will run the TestEnvironmentSetup for the first story in the tale, and run the TestEnvironmentTeardown for the last story in the tale.  These steps will be skipped for all of the other stories.

## Getting A List Of Configured Test Environments

Storyplayer supports running the same tests against different test environments.  You can always get a list of the environments available by running:

<pre>
storyplayer list-environments
</pre>

## Other Storyplayer Commands

Storyplayer has other commands built-in too, and comprehensive built-in help.  You can always get a list of everything Storyplayer can do by running:

<pre>
storyplayer --help
</pre>

and comprehensive help about a specific command by running:

<pre>
storyplayer help &lt;command&gt;
</pre>