---
layout: devices
title: Testing With Locally Running Web Browsers
prev: '<a href="../devices/how-it-works.html">Prev: How We Control Web Browsers</a>'
next: '<a href="../devices/saucelabs.html">Next: Testing Multiple Browsers Using SauceLabs</a>'
---

# Testing With Locally Running Web Browsers

By far the simplest setup for testing your website / web-based app is to run Storyplayer on your desktop computer or laptop, and let it control a web browser that's installed on the same machine.

* You can see exactly what is happening as your test runs.
* You get the shortest possible time between iterations of your test.

Once you've got the tests running locally, you can then look at running the test with other browser / platform / device combinations using either [Sauce Labs integration](saucelabs.html) or your own test lab via the [remote WebDriver support](remotewebdriver.html).

## Getting Started

You need to install the web browser that you're going to use.  At the moment, we support:

* Google Chrome, Firefox, and Safari on OS X
* Google Chrome and Firefox on Linux

(We don't use Windows ourselves, which is the only reason why we're not able to support running Storyplayer on Windows at the moment.  If you want to help us and become the Windows maintainer of Storyplayer, please get in touch!)

Make sure you've installed the latest version of Storyplayer (to get the latest features and bug fixes), and that you're run `storyplayer install` to download dependencies such as _browsermob-proxy_ and _Selenium Standalone Server_.  You'll also need to start _browsermob-proxy_ and _Selenium Server_.

{% highlight php %}
storyplayer install
vendor/bin/browsermob-proxy.sh start
vendor/bin/selenium-server.sh start
{% endhighlight %}

This will start _browsermob-proxy_ and _selenium_ in _[screen](http://www.gnu.org/software/screen/)_ sessions in the background.

We've looked at the possibility of having Storyplayer start these for you, but decided that this was too slow (both are Java applications, which take time to start) and too unreliable (both applications need a few seconds before they have finished initialising, and that time depends on the speed of your computer).  It's also sometimes handy to be able to look at the output from Selenium to understand why an operation isn't working.

## Running A Test

Running a test against a browser on your own desktop is very straight forward:

* use the `-b` switch to tell Sauce Labs which browser you want, if you don't want the default browser

For example, here's how to run a test using Chrome:

{% highlight bash %}
storyplayer stories/registration/signup/RegisterUsingRegistrationFormStory.php
{% endhighlight %}

and here's how to run the same test using Firefox:

{% highlight bash %}
storyplayer -b firefox stories/registration/signup/RegisterUsingRegistrationFormStory.php
{% endhighlight %}

and, if you're on OS X, here's how to run the same test using Safari:

{% highlight bash %}
storyplayer -b safari stories/registration/signup/RegisterUsingRegistrationFormStory.php
{% endhighlight %}

## Testing Websites Behind HTTP Basic Auth

At the time of writing, Selenium WebDriver (the technology Storyplayer uses to control web browsers) does not provide working support for authenticating via HTTP Basic Auth.  To get around this, when you run tests against a browser on your own desktop, we proxy the web browser through browsermob-proxy, which can inject the HTTP Basic Auth credentials for us.

If your website requires HTTP Basic Auth, then your test will need to tell Storyplayer which HTTP Basic Auth credentials to use, by calling [$st->usingBrowser()->setHttpBasicAuthForHost()](../../modules/browser/usingBrowser.html#sethttpbasicauthforhost).