---
layout: v2/using-test-environments
title: Testing Against Dedicated Environments
prev: '<a href="../../using/test-environments/local-vms.html">Prev: Testing Against Local Virtual Machines</a>'
next: '<a href="../../using/test-environments/production.html">Next: Testing Against Production</a>'
---

# Testing Against Dedicated Environments

If you are working as part of a larger team or organisation, then you probably have an internal copy of your production environment available.  It might be called _integration_, or _staging_, for example.  It's an environment where the team deploys new builds of the code, and tests it by using it before deciding it's safe to ship to production or your customers.

Testing against a dedicated environment isn't much different to testing against [code running on your local machine](your-machine.html).

## Setting Up For Testing

You are going to run Storyplayer on your local computer, and use it to test software already installed inside your dedicated environment.

* Use your web browser to make sure that you can access the dedicated environment from your local computer
* Make sure that you have [Storyplayer installed locally](../installation.html)

That should be all you need to do.

## Configuring Your Environment

You will need to create a [per-environment configuration file](../configuration/environment-config.html) that contains the settings for the dedicated environment that you are going to test against.

You might find that your environment configs are getting quite large, because they contain duplicate config settings.  Identify all of the duplicated config settings, and move them into the _environments->defaults_ section of your [storyplayer.json](../configuration/storyplayer-json.html) file.  That way, each environment config ends up containing only the settings that are unique to that environment.

## Writing Your Stories

By this stage, your stories and story templates may have [TestEnvironmentSetup and Teardown](../stories/test-environment-setup-teardown.html) phases.  You don't want to run these phases against dedicated environments, because these environments aren't owned by Storyplayer in the way that [your local test VMs](local-vms.html) are.

How can you avoid this happening?

Your stories are plain old PHP files, so the easiest way to avoid this is to add a [PHP switch() statement](http://www.php.net/manual/en/control-structures.switch.php) in your `TestEnvironmentSetup()` and `TestEnvironmentTeardown()` functions:

{% highlight php startinline %}
$story->addTestEnvironmentSetup(function($st) {
	// which environment are we running against?
	$envName = $st->getEnvironmentName();

	// which environments do we deploy in?
	switch ($envName) {
		case 'integration':
		case 'staging':
			// do nothing
			break;

		default:
			// put all the provisioning code here
	}
});

$story->addTestEnvironmentTeardown(function($st) {
	// which environment are we running against?
	$envName = $st->getEnvironmentName();

	// which environments do we teardown?
	switch ($envName) {
		case 'integration':
		case 'staging':
			// do nothing
			break;

		default:
			// put all the teardown code here
	}
});
{% endhighlight %}

## Running Your Stories

Running your stories against a dedicated environment is very straight-forward: use the `-e` switch to tell Storyplayer about the environment:

<pre>
vendor/bin/storyplayer -e integration &lt;storyfile&gt;
</pre>