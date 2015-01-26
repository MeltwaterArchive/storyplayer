---
layout: v2/using-test-environments
title: Testing Against Multiple Environments
prev: '<a href="../environments/production.html">Prev: Testing Against Production</a>'
next: '<a href="../environments/ec2.html">Next: Creating Test Environments On Amazon EC2</a>'
---

# Testing Against Multiple Environments

One of the promises of Storyplayer is that you can write your story once, and then run it against as many different environments as you need to.

## Create Per-Environment Configurations

Create a [per-environment config](../configuration/environment-config.html) for each environment:

* Create a JSON config file for each of your environments.
* Put these files in your `etc` folder \(see [our example test repository structure](../example-test-repo.html)\)
* Use the `-e` switch when you run Storyplayer to choose the environment to test against

## Don't Hard-Code Settings In Your Stories

Anything that's likely to be different between environments (such as URLs) should be added to your configuration file:

{% highlight json %}
{
	"environments": {
		"staging": {
			"website": {
				"loginPage": "https://staging.example.com/login"
			}
		},
		"production": {
			"website": {
				"loginPage": "https://www.example.com/login"
			}
		}
	}
}
{% endhighlight %}

and then retrieved in your stories using _[$st->fromEnvironment()->getAppSettings()](../modules/environment/fromEnvironment.html#getappsettings)_:

{% highlight php %}
$story->addAction(function($st) {
	// where is our app?
	$wwwSettings = $st->fromEnvironment()->getAppSettings('website');

	// go to the login page
	$st->usingBrowser()->gotoPage($wwwSettings->loginPage);
});
{% endhighlight %}

Storyplayer makes sure that _[$st->fromEnvironment()->getAppSettings()](../modules/environment/fromEnvironment.html#getappsettings)_ always retrieves the settings for the environment you've selected using the `-e` switch.

## Get IP Addresses Dynamically

When you build a virtual machine, it's quite likely that the virtual machine will be allocated a dynamic IP address.  If you ever need to get the IP address of the virtual machine, use _[$st->fromHost()->getIpAddress()](../modules/host/fromHost.html#getipaddress)_:

{% highlight php %}
$story->addAction(function($st) {
	// where is our app installed?
	$ipAddress = $st->fromHost('myhost')->getIpAddress();
});
{% endhighlight %}

## Multiple Operating Systems

If you need to test against more than one operating system, take advantage of [story parameters](../stories/story-params.html) and use the `-P` / `--platform` switch:

{% highlight php %}
$story->addTestEnvironmentSetup(function($st) {
	// set our default params
	//
	// -P switch allows the user to override the 'platform' param
	$st->setParams(array (
		"platform" => "vagrant-centos6"
	));

	// get the final set of params
	$params = $st->getParams();

	// what are we setting up?
	switch ($params['platform']) {
		case "vagrant-centos6":
			// do stuff here
			break;

		case "ec2-centos6":
			// do different stuff here
			break;
	}
});
{% endhighlight %}