---
layout: stories
title: Test Environment Setup / Teardown Phases
prev: '<a href="../stories/the-environment.html">Prev: The Environment</a>'
next: '<a href="../stories/test-setup-teardown.html">Next: Test Setup / Teardown Phases</a>'
---

# Test Environment Setup / Teardown Phases

Where you test your software is just as important as what your test does.  Storyplayer supports automated creation and destruction of test environments for _each and every story test_ to make your tests as repeatable as possible.

*These phases are optional.*

## Running Order

Creating and destroying test environments are the first and last phases of a story:

1. __Test Environment Setup__
1. Test Setup
1. Pre-test Prediction
1. Pre-test Inspection
1. Action
1. Post-test Inspection
1. Test Teardown
1. __Test Environment Teardown__

## Creating Your Test Environment

To create your test environment, add a _TestEnvironmentSetup_ function to your story:

{% highlight php %}
$story->addTestEnvironmentSetup(function(StoryTeller $st) {
    // steps go here
});
{% endhighlight %}

Useful modules to use here include:

* [Amazon EC2](../modules/ec2/index.html)
* [Vagrant](../modules/vagrant/index.html)
* [Physical Hosts](../modules/physical-hosts/index.html)
* [Provisioning](../modules/provisioning/index.html)

The [Environments section](../environments/index.html) of this manual looks at test environments in detail.

## Destroying The Test Environment

If your test creates a test environment, add a _TestEnvironmentTeardown_ function to your story to undo everything you created in the _TestEnvironmentSetup_ function:

{% highlight php %}
$story->addTestEnvironmentTeardown(function(StoryTeller $st) {
    // steps go here
});
{% endhighlight %}

## Templating Your Test Environment

Once you've written a few tests, you'll probably find that they have the same test environment setup and teardown steps. Storyplayer's [story templating](story-templates.html) feature was originally added to avoid having to duplicate the same steps across multiple tests.

{% highlight php %}
use DataSift\Storyplayer\PlayerLib\StoryTemplate;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

class MyStoryTemplate extends StoryTemplate
{
    public function testEnvironmentSetup(StoryTeller $st)
    {
        // steps go here
    }

    public function testEnvironmentTeardown(StoryTeller $st)
    {
        // steps go here
    }
}
{% endhighlight %}

You can put exactly the same code into the template that you would put into the _TestEnvironmentSetup_ and _TestEnvironmentTeardown_ functions in the story script.

## Testing Against Multiple Types Of Environments

You may need to run the exact same test against multiple types of environments, such as:

* a local environment running in a virtual machine on your desktop (quick & cheap to create and destroy)
* a remote environment running in a virtual machine on Amazon EC2 (the location where your app will finally run)
* a local environment running on dedicated hardware (for performance testing)

You can do this by adding a simple `switch` statement to your _TestEnvironmentSetup_ function:

{% highlight php %}
$st->addTestEnvironmentSetup(function(StoryTeller $st) {
    // set the defaults for this story / template
    $st->setParams(array(
        'platform' => 'vagrant'
        // any additional settings go here
    ));

    // get the final params for this story / template
    // $st will merge in anything overridden from the command-line
    $params = $st->getParams();

    // pick a platform
    switch($params['platform'])
    {
        case 'ec2':
            // add steps here to build on EC2
            break;

        case 'vagrant':
        default:
            // add steps here to build locally
    }
});
{% endhighlight %}

To pick an alternative platform, use the `-P` flag to override the 'platform' parameter:

{% highlight php %}
storyplayer -P ec2 <your story>
{% endhighlight %}

__Notes:__

* The `-P` flag is completely independent from `-e`.  `-e` tells Storyplayer to load a specific config for your test; `-P` is simply information passed into your test for you to support in any way that suits.
* You can give your test platforms whatever names you like.

## Testing Against Existing Environments

You might want to run your tests against environments that Storyplayer does not manage.  There are a couple of strategies for this:

* Don't add _TestEnvironmentSetup_ and _TestEnvironmentTeardown_ functions to your story - best approach if the test is always going to run against environments that Storyplayer does not manage
* Or, if you need to deploy an environment some of the time, take advantage of the `-P` switch.  Simply add a setting to your [per-environment config file](../configuration/environment-config.html) to tell Storyplayer what the default platform should be for your environment, and make one of the platforms a no-operation:

{% highlight php %}
$st->addTestEnvironmentSetup(function(StoryTeller $st) {
    // get the settings for this environment
    $settings = $st->fromEnvironment()->getAppSettings('testEnvSetup')

    // set the defaults for this story / template
    $st->setParams(array(
        'platform' => $settings->platform
        // any additional settings go here
    ));

    // get the final params for this story / template
    // $st will merge in anything overridden from the command-line
    $params = $st->getParams();

    // pick a platform
    switch($params['platform'])
    {
        case 'none':
            // do nothing, because we do not own this platform
            break;

        case 'vagrant':
        default:
            // add steps here to build locally
    }
});
{% endhighlight %}

## Disabling The Test Environment Setup / Teardown Phases

Sometimes, you'll want to skip either the test environment setup phase, or the test environment teardown phase:

* once the test environment setup phase works, you'll want to disable both phases whilst you get the test itself working reliably
* if you're running against multiple environments, or an existing environment, you won't need either phase

To do this, see [how to configure test phases](../configuration/test-phases.html).