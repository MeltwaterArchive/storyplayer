---
layout: stories
title: Test Environment Setup / Teardown Phases
prev: '<a href="../stories/the-checkpoint.html">Prev: The Checkpoint</a>'
next: '<a href="../stories/test-setup-teardown.html">Next: Test Setup / Teardown Phases</a>'
---

# Test Environment Setup / Teardown Phases

Where you test your software is just as important as what your test does.  Storyplayer supports automated creation and destruction of test environments for _each and every story test_ to make your tests as repeatable as possible.

## Representative Environments

Your software should be tested in an environment that's as close to your production environment as possible:

* Same operating system
* Same supporting software - web server, database server, system libraries
* Same runtime engines - scripting engines, JDK version

"Close enough" is only good enough for the most trivial and simplistic of apps.  Why?  Your app interacts with, and relies on, this environment.  That's millions of lines of code, with bugs of its own, and its own unique behaviour.  Substitute any of it for something else, and you run the risk of your app working in test but not working in production.

If your app is deployed across multiple servers, then the right parts of your app must be co-located on the right test boxes, to take into account:

* network traffic between each box
* cpu usage on each box
* disk I/O bottlenecks on each box

If you test all of your app's parts on a single box, you might miss that your app seems to go fast enough because it doesn't have to communicate over the network (which is much slower).  If you split things up in a different way to production, you might miss that you've actually got two disk I/O-heavy parts on the same box in production.

Make your test environments look like the production environment.

## A Discussion About Hardware And Virtual Machines

Should you test on physical hardware, or are virtual machines good enough?

There are many advantages to using virtual machines to test on.  They're fast enough for most testing.  You can run them directly on your dev box.  If you've got a laptop, you can run them on your laptop, which is great if you're working out of the office.  You can create and destroy them whenever you want, and it won't affect anyone else.  There are some great tools out there to help you work with virtual machines.  All you need is enough RAM and disk space.

And, it has to be said, most apps simply aren't busy enough to need anything else.

Once your business starts to scale, then testing on physical hardware becomes more important.  The hardware - your server choice, your network topology, your switches, your gateways - becomes part of your app, because it becomes part of how your app works at scale.  Here, testing inside a virtual machine doesn't test all of your app any more, and won't catch all of reasons why a test will fail.

When we got to this point, we adopted a dual testing strategy:

* virtual machines for the functional tests - making sure our stories worked at all
* dedicated test hardware for the non-functional tests - making sure our stories worked at scale

With this approach, anyone can still run tests at any time inside virtual machines - which is good, because you don't want your developers to have excuses not to run tests.  And, our quality assurance folks can run larger, more demanding tests in parallel to catch any problems before new builds go out to production.

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

There are times when you'll want to run (some!) of your tests against an existing environment:

* Testing against a complex, multi-host environment (e.g. OpenStack) that Storyplayer currently doesn't have a module for
* Testing against your staging or [production environment](../environments/production/index.html)

The best way to do this is to temporarily disable the test environment setup and teardown phases.

## Disabling The Test Environment Setup / Teardown Phases

Sometimes, you'll want to skip either the test environment setup phase, or the test environment teardown phase:

* once the test environment setup phase works, you'll want to disable both phases whilst you get the test itself working reliably
* if you're running against multiple environments, or an existing environment, you won't need either phase

To do this, see [how to configure test phases](../configuration/test-phases.html).