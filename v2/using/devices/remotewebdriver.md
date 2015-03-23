---
layout: v2/using-devices
title: Testing Unusual Browsers Using The Remote WebDriver
prev: '<a href="../../using/devices/saucelabs.html">Prev: Testing Multiple Browsers Using SauceLabs</a>'
next: '<a href="../../using/devices/how-to-test.html">Next: How To Test With Browsers And Devices</a>'
---

# Testing Unusual Browsers Using The Remote WebDriver

_Please note that this support is currently experimental. Expect bugs._

Not every interesting web browser can run on your dev computer.  Sometimes, you'll want Storyplayer to control a web browser that's running somewhere else.  It might be a copy of Internet Explorer running on Windows XP in a virtual machine.  It might be a copy of mobile Safari running in Apple's iPhone simulator, even a UIWeb instance embedded in an iOS app.  It might be anywhere.

These are edge cases for Storyplayer - we recommend using [our SauceLabs integration where possible](saucelabs.html) - but when [someone asked us if we could do it](https://github.com/datasift/storyplayer/issues/51), we thought it was too cool not to.

## Getting Started

Your first step is to get WebDriver running on whatever virtual machine, server, device or emulator you want.  This isn't something we do ourselves, so apologies but you'll have to research this for yourself.  The key thing is that you need a WebDriver that supports the [JsonWireProtocol](https://code.google.com/p/selenium/wiki/JsonWireProtocol) accurately.

Once you've got your WebDriver instance up and running, you should have a URL for Storyplayer to connect for.  It should look something like this:

<pre>
http://localhost:4444/wd/hub
</pre>

except _localhost_ will be a hostname or IP address, and port _4444_ might well be different.  You need to add this URL to your config file:

{% highlight json %}
{
	"devices": {
		"<device-name>": {
			"adapter": "remotewebdriver",
			"url": "<webdriver url>",
			"desiredCapabilities": {
				...
			}
		}
	}
}
{% endhighlight %}

Make sure you've installed the latest version of Storyplayer (to get the latest features and bug fixes), and that you've run `storyplayer install` to download dependencies such as _browsermob-proxy_.  You'll need to start _browsermob-proxy_, as Storyplayer will tell your web browser to proxy all traffic through it:

<pre>
storyplayer install
vendor/bin/browsermob-proxy.sh start
</pre>

This will start _browsermob-proxy_ in a _[screen](http://www.gnu.org/software/screen/)_ session in the background.

We've looked at the possibility of having Storyplayer start browsermob-proxy for you, but decided that this was too slow (it's a Java application, which takes time to start) and too unreliable (it needs a few seconds before it has finished initialising, and that time depends on the speed of your computer).

## Running A Test

Running a test via the Remote WebDriver is very similar to running a test against a browser on your own desktop:

* use the `-d` switch to tell WebDriver which browser you want

## Testing Websites Behind HTTP Basic Auth

At the time of writing, Selenium WebDriver (the technology Storyplayer uses to control web browsers) does not provide working support for authenticating via HTTP Basic Auth.  To get around this, when you run tests against a browser on your own desktop, we proxy the web browser through browsermob-proxy, which can inject the HTTP Basic Auth credentials for us.

If your website requires HTTP Basic Auth, then your test will need to tell Storyplayer which HTTP Basic Auth credentials to use, by calling _[$st->usingBrowser()->setHttpBasicAuthForHost()](../../modules/browser/usingBrowser.html#sethttpbasicauthforhost)_.