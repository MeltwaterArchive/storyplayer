---
layout: v2/learn-test-your-code
title: Recommended First Tests
prev: '<a href="../../learn/test-your-code/defining-your-system-under-test.html">Prev: Defining Your System Under Test</a>'
next: '<a href="../../learn/test-your-code/designing-component-tests.html">Next: Designing Component Tests</a>'
---
# Recommended First Tests

Now that you have your test environment, it's time to write your first Storyplayer tests for your system under test.  Chances are that what you're testing is a networked component of some kind:

* it's an app that is installed via some kind of package manager
* it runs as a daemon, or under the control of a service such as Supervisord
* it accepts inbound network connections from other components / clients
* it writes log messages to a log file somewhere, perhaps via the syslog daemon

Your first tests should be to test all of these properties, to prove that your system under test is ready to be functionally tested.  These tests also verify that your test environment has been built correctly.

<div class="callout info" markdown="1">
#### Why These Tests?

The great thing about these tests is that they act like a canary down a mine. If all of your tests are failing, you can ignore your later tests until you've solved the problems caught by these tests.
</div>

## Does The Component Deploy Correctly?

### If Using An Operating System Package Manager

This test is the very first one that I write for any new system under test. It's also really quick to write, as there's no `Action` to perform, because your test environment setup will have already done the install for you. All you need to do is write a simple `PostTestInspection` to check that the system under test is installed:

{% highlight php startinline %}
$story = newStoryFor('My App')
       ->inGroup('Provisioning')
       ->called('Can Provision');

$story->requiresStoryplayerVersion(2);

$story->addAction(function() {
    // add an empty action
});

$story->addPostTestInspection(function() {
    // this checks every host in turn
    foreachHostWithRole('web-server')->expectsHost()->packageIsInstalled('my_app');
});
{% endhighlight %}

### If Not Using A Package Manager

Not using your operating system's package manager? You can still check that the system under test is installed. Identify the key files and folders that should exist, and check that they are present and correct on your test environment.

{% highlight php startinline %}
$story = newStoryFor('My App')
       ->inGroup('Provisioning')
       ->called('Can Provision');

$story->requiresStoryplayerVersion(2);

$story->addAction(function() {
    // add an empty action
});

$story->addPostTestInspection(function() {
    // iterate over all hosts that should have our app installed
    foreach(hostWithRole(, 'web-server') as $hostId) {
        // which user does Nginx run as on here?
        //
        // also, where should our web app be installed on here?
        $nginxSettings = fromHost($hostId)->getAppSettings('nginx');

        // make sure that our app is on the file system
        expectsHost($hostId)->hasFolderWithPermissions(
            $nginxSettings->appFolder . '/my_app',
            'root',
            'root',
            0755
        );

        // make sure that Nginx can see our app's public folder
        expectsHost($hostId)->hasFolderWithPermissions(
            // /var/www/my_app/public
            $nginxSettings->appFolder . '/my_app/public',
            // www-data
            $nginxSettings->username,
            $nginxSettings->group,
            0755
        );

        // make sure that Nginx has the config file for our app
        expectsHost($hostId)->hasFileWithPermissions(
            // /etc/nginx/sites-enabled/my_app
            $nginxSettings->sitesEnabledFolder . '/my_app',
            'root',
            'root',
            0644
        );

        // ... and so on
    }
});
{% endhighlight %}

Notice how I don't hard-code details about Nginx, such as folder paths, users and groups? These are details that might be different on different operating systems. I put them in an `appSettings` section in my test environment config:

{% highlight json %}
[
    "type": "LocalVagrantVms",
    "details": {
        "machines": {
            "default": {
                "roles": [
                    "web-server"
                ],
                "appSettings": {
                    "nginx": {
                        "appFolder": "/var/www",
                        "sitesEnabledFolder": "/etc/nginx/sites-enabled",
                        "user": "www-data",
                        "group": "www-data"
                    }
                }
            }
        }
    }
]
{% endhighlight %}

and get them using [`fromHost()`](../../modules/host/fromHost.html):

{% highlight php startinline %}
$nginxSettings = fromHost($hostId)->getAppSettings('nginx');
{% endhighlight %}

This way, if I ever switch operating systems - or start testing on multiple operating systems - the story itself doesn't have to change. I just have to put the right settings into the test environment config file.

## Does The Component Start Up / Shutdown / Restart Correctly?

Any system under test should be well-behaved. That includes:

* starting up successfully and without errors,
* shutting down quickly and cleanly,
* restarting when required

Your sysadmins will feel more confident in systems under test that are well-behaved.

### Checking For Starting Up Correctly

Here's a simple test that makes sure that the web server starts up:

{% highlight php startinline %}
$story = newStoryFor('My App')
       ->inGroup('Provisioning')
       ->called("Starts Up Cleanly");

$story->requiresStoryplayerVersion(2);

$story->addTestSetup(function() {
    // if the app is running, we need to stop it, otherwise we can't test
    // that it starts correctly!
    foreach(hostWithRole('web-server') as $hostId) {
        // get our nginx settings for this host
        $nginxSettings = fromHost($hostId)->getAppSettings('nginx');

        // stop nginx
        if (fromHost($hostId)->getProcessIsRunning('nginx')) {
            usingHost($hostId)->runCommand($nginxSettings->shutdownCommand);

            // make sure it has stayed dead
            expectsHost($hostId)->processIsNotRunning('nginx');
        }
    }
});

$story->addAction(function() {
    foreach(hostWithRole('web-server') as $hostId) {
        // get our nginx settings
        $nginxSettings = fromHost($hostId)->getAppSettings('nginx');

        // start nginx
        usingHost($hostId)->runCommand($nginxSettings->startCommand);
    }
});

$story->addPostTestInspection(function() {
    // make sure nginx is running everywhere
    foreachHostWithRole('web-server')->expectsHost()->processIsRunning('nginx');
});
{% endhighlight %}

This test introduces the `TestSetup` phase. `TestSetup` creates the pre-conditions for the test. In this case, it makes sure that `nginx` isn't running, so that we can start it in the `Action` phase.

Once again, the test uses `appSettings` from the test environment config. We've added two new settings, `nginx.startCommand` and `nginx.shutdownCommand`.

{% highlight json %}
[
    "type": "LocalVagrantVms",
    "details": {
        "machines": {
            "default": {
                "roles": [
                    "web-server"
                ],
                "appSettings": {
                    "nginx": {
                        "appFolder": "/var/www",
                        "sitesEnabledFolder": "/etc/nginx/sites-enabled",
                        "user": "www-data",
                        "group": "www-data",
                        "startupCommand": "/etc/init.d/nginx start",
                        "shutdownCommand": "/etc/init.d/nginx shutdown"
                    }
                }
            }
        }
    }
]
{% endhighlight %}

You can write equivalent tests for proving that the system under test shuts down and restarts as expected.

<div class="callout info" markdown="1">
#### Expanding On These Tests

If the system under test is your own daemon, having startup / shutdown / restart tests is even more important. There are plenty of things that can go wrong, and which are easy to overlook when developing the code.

In particular, look for things like:

* does my system under test become a UNIX daemon (parent process is 1)?
* does my system under test successfully start when data on disk is corrupted?
* does my system under test shutdown before init(1) sends a KILL signal?
* does my system under test kill off all child processes when shutting down?
* does my system under test really restart when we tell it to?

These are all things that people take for granted, and so assume that they work as expected. Don't assume. Verify.
</div>

## Does The Component Listen On The Correct Network Ports?

None of your subsequent functional tests will pass if the component isn't listening on the network correctly.

### Check Your Healthcheck Page

It's a good idea to build a _healthcheck page_ into your system under test. This is a web page that reports on whether the system under test is working or not. Once you have one, you can download it to prove that your system under test is listening correctly on the network.

{% highlight php startinline %}
$story = newStoryFor('My App')
       ->inGroup('Provisioning')
       ->called('Listens On The Network');

$story->requiresStoryplayerVersion(2);

$story->addAction(function() {
    // an empty action, because we are not changing anything at all
});

$story->addPostTestInspection(function() {
    // what is the address of our healthcheck page?
    $healthcheckPage = fromSystemUnderTest()->getAppSetting('my_app.pages.healthcheck');

    // what do we expect the healthcheck page to say?
    $expectedBody = file_get_contents(__DIR__ . '/200-healthcheck-page.html');

    // check each host for what we want
    foreach(hostWithRole('web-server') as $hostId) {
        // what is the name of this server on the network?
        $fqdn = fromHost($hostId)->getHostname();

        // make sure we can get our healthcheck page
        $response = fromHttp()->get("http://{$fqdn}/{$healthcheckPage}");
        expectsHttpResponse($response)->hasStatusCode(200);
        expectsHttpResponse($response)->hasBody($expectedBody);
    }
});
{% endhighlight %}

Once again, we avoid hard-coding settings into the test.

The URL of the healthcheck page is part of the `appSettings` for the system under test:

{% highlight json %}
{
    "appSettings": {
        "my_app": {
            "pages": {
                "healthcheck": "/healthcheck.php"
            }
        }
    }
}
{% endhighlight %}

Until now, I've been putting `appSettings` into the test environment config file. This setting goes in the system under test config file because it doesn't change from test environment to test environment.

Secondly, this test shows a really clean way to check that we have the correct webpage. It simply loads a pre-cached copy of what the page should look like from disk, and then checks the downloaded page to make sure it matches.

## Does The Component Write To Its Logs Correctly?

Finally, I recommend testing that your system under test is logging correctly.

* Your functional tests will need to check the logs to prove that what you expected to happen actually did happen with no surprises. (This is part of the [belt and braces approach to testing](../fundamentals/belt-and-braces.html) that Storyplayer enables.)
* When (not if!) a test fails, you're going to need logs to attach to any bug report that you raise.

