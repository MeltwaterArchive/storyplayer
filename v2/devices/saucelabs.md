---
layout: v2/devices
title: Testing Multiple Browsers Using SauceLabs
prev: '<a href="../devices/localbrowsers.html">Prev: Testing With Locally Running Web Browsers</a>'
next: '<a href="../devices/remotewebdriver.html">Next: Testing Unusual Browsers Using The Remote WebDriver</a>'
---

# Testing Multiple Browsers Using SauceLabs

If you run any sort of website, whether it's just for sales & marketing or as your main product, cross-browser testing - both desktop and mobile - is an essential part of your approach to high quality.  Your website is your shop window, and if potential customers run into problems _before_ they've spent any money with you, do you think they're likely to become paying customers?

Traditionally, cross-browser testing meant maintaining your own set of Windows images (which takes a fair bit of effort), and firing up each one to test your website by hand.  They were two time-consuming and error-prone processes.  Testing tools such as Storyplayer now make it easy to automate your browser tests, but that still leaves the overhead of maintaining your collection of browser versions and platforms, right?

That's where [Sauce Labs](http://saucelabs.com) comes in.

Sauce Labs provides [a hosted collection of browser versions and platforms](https://saucelabs.com/docs/platforms), so that you don't have to maintain these yourself.  Their collection includes the usual suspects (Internet Explorer, Firefox and Chrome) on various versions of Microsoft Windows, plus Safari on OS X, and browsers running on Linux too (handy if your target audience are software engineers).  They've also recently started providing mobile browsers too.  (They also have useful features such as recording videos of each test run, and recording screenshots too, which can be very helpful when a test works in one browser but fails in another).

Sauce Labs isn't free, but we use it here at DataSift because (for us) it's a lot cheaper than maintaining our own collection of browser versions and platforms to use in cross-browser testing.

## Getting Started

Here's what you need to run your tests using Sauce Lab's browsers instead of browsers running on your desktop machine:

* A Sauce Labs account ([sign up here](https://saucelabs.com/signup))
* Your Sauce Labs username
* Your Sauce Labs access key

Your _Sauce Labs username_ is the username that you use to log into the Sauce Labs website, and you'll find your _Sauce Labs access key_ in the left-hand sidebar on the _Accounts_ page.

You need to add your Sauce Labs details to your Storyplayer config file.

{% highlight json %}
{
	"environments": {
		"defaults": {
			"saucelabs": {
				"username": "<saucelabs-username>",
				"accesskey": "<saucelabs-accesskey>"
			}
		}
	}
}
{% endhighlight %}

(Tip: for private repos, it's fine to put them in the main [storyplayer.json](../../configuration/storyplayer-json.html) file, but for public repos, it's best to add them to your [per-user config file](../../configuration/user-config.html) instead).

Make sure you've installed the latest version of Storyplayer (to get the latest features and bug fixes), and that you have run `storyplayer install` to download dependencies such as _browsermob-proxy_ and _Sauce Connect_.

<pre>
storyplayer install
vendor/bin/browsermob-proxy.sh start
</pre>

This will start _browsermob-proxy_ in a _[screen](http://www.gnu.org/software/screen/)_ session in the background.

We've looked at the possibility of having Storyplayer start browsermob-proxy for you, but decided that this was too slow (it's a Java application, which takes time to start) and too unreliable (it needs a few seconds before it has finished initialising, and that time depends on the speed of your computer).

Finally, before you use Storyplayer's Sauce Labs integration for the first time, make sure that your test works with a web browser that is running on your own desktop.  This will save you a lot of time :)

## Running A Test

Running a test via Sauce Labs is very similar to running a test against a browser on your own desktop.  Just use the `-d` switch to tell Sauce Labs which browser you want.  You'll find the list of device names below.

For example, here's how to run a test using Internet Explorer 9 on Windows 7 via Sauce Labs:

<pre>
storyplayer -d sl_ie9_win7 stories/registration/signup/RegisterUsingRegistrationFormStory.php
</pre>

## Selecting A Browser

Storyplayer ships with support for the following browsers running at Sauce Labs.  To use the browser you want, use the corresponding `-d` switch on the command-line when you run Storyplayer.

Our list is based on the [Supported Device, OS and Browser Platforms list](https://saucelabs.com/docs/platforms) published by Sauce Labs.

### Android - Phone

* Android 4.0 (Portrait) - `-d sl_android_phone_4_0_portrait`

### Android - Tablet

* Android 4.0 (Portrait) - `-d sl_android_tablet_4_0_portrait`

### iOS - iPad

* Safari on iOS 6.1 (Portrait): `-d sl_safari_ipad_ios6_1_portrait`
* Safari on iOS 6.0 (Portrait): `-d sl_safari_ipad_ios6_0_portrait`
* Safari on iOS 5.1 (Portrait): `-d sl_safari_ipad_ios5_1_portrait`
* Safari on iOS 5.0 (Portrait): `-d sl_safari_ipad_ios5_0_portrait`
* Safari on iOS 4 (Portrait): `-d sl_safari_ipad_ios4_portrait`

### iOS - iPhone

* Safari on iOS 6.1 (Portrait): `-d sl_safari_iphone_ios6_1_portrait`
* Safari on iOS 6.0 (Portrait): `-d sl_safari_iphone_ios6_0_portrait`
* Safari on iOS 5.1 (Portrait): `-d sl_safari_iphone_ios5_1_portrait`
* Safari on iOS 5.0 (Portrait): `-d sl_safari_iphone_ios5_0_portrait`
* Safari on iOS 4 (Portrait): `-d sl_safari_iphone_ios4_portrait`

### Linux

* Chrome 31: `-d sl_chrome31_linux`
* Chrome 30: `-d sl_chrome30_linux`
* Chrome 29: `-d sl_chrome29_linux`
* Chrome 28: `-d sl_chrome28_linux`
* Chrome 27: `-d sl_chrome27_linux`
* Chrome 26: `-d sl_chrome26_linux`
* Firefox 25: `-d sl_firefox25_linux`
* Firefox 24: `-d sl_firefox24_linux`
* Firefox 23: `-d sl_firefox23_linux`
* Firefox 22: `-d sl_firefox22_linux`
* Firefox 21: `-d sl_firefox21_linux`
* Firefox 20: `-d sl_firefox20_linux`
* Firefox 19: `-d sl_firefox19_linux`
* Firefox 18: `-d sl_firefox18_linux`
* Firefox 17: `-d sl_firefox17_linux`
* Firefox 16: `-d sl_firefox16_linux`
* Firefox 15: `-d sl_firefox15_linux`
* Firefox 14: `-d sl_firefox14_linux`
* Firefox 13: `-d sl_firefox13_linux`
* Firefox 12: `-d sl_firefox12_linux`
* Firefox 11: `-d sl_firefox11_linux`
* Firefox 10: `-d sl_firefox10_linux`
* Firefox 9: `-d sl_firefox9_linux`
* Firefox 8: `-d sl_firefox8_linux`
* Firefox 7: `-d sl_firefox7_linux`
* Firefox 6: `-d sl_firefox6_linux`
* Firefox 5: `-d sl_firefox5_linux`
* Firefox 4: `-d sl_firefox4_linux`
* Opera 12: `-d sl_opera12_linux`

### OSX 10.6 Snow Leopard

* Chrome 28: `-d sl_chrome28_osx10_6`
* Firefox 25: `-d sl_firefox25_osx10_6`
* Firefox 24: `-d sl_firefox24_osx10_6`
* Firefox 23: `-d sl_firefox23_osx10_6`
* Firefox 22: `-d sl_firefox22_osx10_6`
* Firefox 21: `-d sl_firefox21_osx10_6`
* Firefox 20: `-d sl_firefox20_osx10_6`
* Firefox 19: `-d sl_firefox19_osx10_6`
* Firefox 18: `-d sl_firefox18_osx10_6`
* Firefox 17: `-d sl_firefox17_osx10_6`
* Firefox 16: `-d sl_firefox16_osx10_6`
* Firefox 15: `-d sl_firefox15_osx10_6`
* Firefox 14: `-d sl_firefox14_osx10_6`
* Firefox 13: `-d sl_firefox13_osx10_6`
* Firefox 12: `-d sl_firefox12_osx10_6`
* Firefox 11: `-d sl_firefox11_osx10_6`
* Firefox 10: `-d sl_firefox10_osx10_6`
* Firefox 9: `-d sl_firefox9_osx10_6`
* Firefox 8: `-d sl_firefox8_osx10_6`
* Firefox 7: `-d sl_firefox7_osx10_6`
* Firefox 6: `-d sl_firefox6_osx10_6`
* Firefox 5: `-d sl_firefox5_osx10_6`
* Firefox 4: `-d sl_firefox4_osx10_6`
* Internet Explorer 5: `-d sl_ie5_osx10_6`

### OSX 10.8 Mountain Lion

* Chrome 27: `-d sl_chrome27_osx10_8`
* Internet Explorer 6: `-d sl_ie6_osx10_8`

### Windows 7

* Chrome 31: `-d sl_chrome31_win7`
* Chrome 30: `-d sl_chrome30_win7`
* Chrome 29: `-d sl_chrome29_win7`
* Chrome 28: `-d sl_chrome28_win7`
* Chrome 27: `-d sl_chrome27_win7`
* Chrome 26: `-d sl_chrome26_win7`
* Firefox 25: `-d sl_firefox25_win7`
* Firefox 24: `-d sl_firefox24_win7`
* Firefox 23: `-d sl_firefox23_win7`
* Firefox 22: `-d sl_firefox22_win7`
* Firefox 21: `-d sl_firefox21_win7`
* Firefox 20: `-d sl_firefox20_win7`
* Firefox 19: `-d sl_firefox19_win7`
* Firefox 18: `-d sl_firefox18_win7`
* Firefox 17: `-d sl_firefox17_win7`
* Firefox 16: `-d sl_firefox16_win7`
* Firefox 15: `-d sl_firefox15_win7`
* Firefox 14: `-d sl_firefox14_win7`
* Firefox 13: `-d sl_firefox13_win7`
* Firefox 12: `-d sl_firefox12_win7`
* Firefox 11: `-d sl_firefox11_win7`
* Firefox 10: `-d sl_firefox10_win7`
* Firefox 9: `-d sl_firefox9_win7`
* Firefox 8: `-d sl_firefox8_win7`
* Firefox 7: `-d sl_firefox7_win7`
* Firefox 6: `-d sl_firefox6_win7`
* Firefox 5: `-d sl_firefox5_win7`
* Firefox 4: `-d sl_firefox4_win7`
* Internet Explorer 10: `-d sl_ie10_win7`
* Internet Explorer 9: `-d sl_ie9_win7`
* Internet Explorer 8: `-d sl_ie8_win7`
* Opera 12: `-d sl_opera12_win7`
* Opera 11: `-d sl_opera11_win7`
* Safari 5: `-d sl_safari5_win7`

### Windows 8

* Chrome 30: `-d sl_chrome30_win8`
* Chrome 29: `-d sl_chrome29_win8`
* Chrome 28: `-d sl_chrome28_win8`
* Chrome 27: `-d sl_chrome27_win8`
* Chrome 26: `-d sl_chrome26_win8`
* Firefox 25: `-d sl_firefox25_win8`
* Firefox 24: `-d sl_firefox24_win8`
* Firefox 23: `-d sl_firefox23_win8`
* Firefox 22: `-d sl_firefox22_win8`
* Firefox 21: `-d sl_firefox21_win8`
* Firefox 20: `-d sl_firefox20_win8`
* Firefox 19: `-d sl_firefox19_win8`
* Firefox 18: `-d sl_firefox18_win8`
* Firefox 17: `-d sl_firefox17_win8`
* Firefox 16: `-d sl_firefox16_win8`
* Firefox 15: `-d sl_firefox15_win8`
* Firefox 14: `-d sl_firefox14_win8`
* Firefox 13: `-d sl_firefox13_win8`
* Firefox 12: `-d sl_firefox12_win8`
* Firefox 11: `-d sl_firefox11_win8`
* Firefox 10: `-d sl_firefox10_win8`
* Firefox 9: `-d sl_firefox9_win8`
* Firefox 8: `-d sl_firefox8_win8`
* Firefox 7: `-d sl_firefox7_win8`
* Firefox 6: `-d sl_firefox6_win8`
* Firefox 5: `-d sl_firefox5_win8`
* Firefox 4: `-d sl_firefox4_win8`
* Internet Explorer 10: `-d sl_ie10_win8`

### Windows 8.1

* Chrome 31: `-d sl_chrome31_win8_1`
* Chrome 30: `-d sl_chrome30_win8_1`
* Chrome 29: `-d sl_chrome29_win8_1`
* Chrome 28: `-d sl_chrome28_win8_1`
* Chrome 27: `-d sl_chrome27_win8_1`
* Chrome 26: `-d sl_chrome26_win8_1`
* Firefox 25: `-d sl_firefox25_win8_1`
* Firefox 24: `-d sl_firefox24_win8_1`
* Firefox 23: `-d sl_firefox23_win8_1`
* Firefox 22: `-d sl_firefox22_win8_1`
* Firefox 21: `-d sl_firefox21_win8_1`
* Firefox 20: `-d sl_firefox20_win8_1`
* Firefox 19: `-d sl_firefox19_win8_1`
* Firefox 18: `-d sl_firefox18_win8_1`
* Firefox 17: `-d sl_firefox17_win8_1`
* Firefox 16: `-d sl_firefox16_win8_1`
* Firefox 15: `-d sl_firefox15_win8_1`
* Firefox 14: `-d sl_firefox14_win8_1`
* Firefox 13: `-d sl_firefox13_win8_1`
* Firefox 12: `-d sl_firefox12_win8_1`
* Firefox 11: `-d sl_firefox11_win8_1`
* Firefox 10: `-d sl_firefox10_win8_1`
* Firefox 9: `-d sl_firefox9_win8_1`
* Firefox 8: `-d sl_firefox8_win8_1`
* Firefox 7: `-d sl_firefox7_win8_1`
* Firefox 6: `-d sl_firefox6_win8_1`
* Firefox 5: `-d sl_firefox5_win8_1`
* Firefox 4: `-d sl_firefox4_win8_1`
* Internet Explorer 11: `-d sl_ie11_win8_1`

### Windows XP

* Chrome 31: `-d sl_chrome31_winxp`
* Chrome 30: `-d sl_chrome30_winxp`
* Chrome 29: `-d sl_chrome29_winxp`
* Chrome 28: `-d sl_chrome28_winxp`
* Chrome 27: `-d sl_chrome27_winxp`
* Chrome 26: `-d sl_chrome26_winxp`
* Firefox 25: `-d sl_firefox25_winxp`
* Firefox 24: `-d sl_firefox24_winxp`
* Firefox 23: `-d sl_firefox23_winxp`
* Firefox 22: `-d sl_firefox22_winxp`
* Firefox 21: `-d sl_firefox21_winxp`
* Firefox 20: `-d sl_firefox20_winxp`
* Firefox 19: `-d sl_firefox19_winxp`
* Firefox 18: `-d sl_firefox18_winxp`
* Firefox 17: `-d sl_firefox17_winxp`
* Firefox 16: `-d sl_firefox16_winxp`
* Firefox 15: `-d sl_firefox15_winxp`
* Firefox 14: `-d sl_firefox14_winxp`
* Firefox 13: `-d sl_firefox13_winxp`
* Firefox 12: `-d sl_firefox12_winxp`
* Firefox 11: `-d sl_firefox11_winxp`
* Firefox 10: `-d sl_firefox10_winxp`
* Firefox 9: `-d sl_firefox9_winxp`
* Firefox 8: `-d sl_firefox8_winxp`
* Firefox 7: `-d sl_firefox7_winxp`
* Firefox 6: `-d sl_firefox6_winxp`
* Firefox 5: `-d sl_firefox5_winxp`
* Firefox 4: `-d sl_firefox4_winxp`
* Internet Explorer 8: `-d sl_ie8_winxp`
* Internet Explorer 7: `-d sl_ie7_winxp`
* Internet Explorer 6: `-d sl_ie6_winxp`
* Opera 12: `-d sl_opera12_win7`
* Opera 11: `-d sl_opera11_win7`

## Testing Websites Behind Your Firewall

Sauce Lab's browsers normally connect to websites over the internet.  If you are trying to test a website that is firewalled off from the internet (or maybe on private IP addresses in your office), then you'll need to use _Sauce Connect_.

[Sauce Connect](https://saucelabs.com/docs/connect) is a network tunnel and HTTP proxy combined into a single JAR file.  Storyplayer will download it for you; you just need to start it up before you run any tests:

<pre>
vendor/bin/storyplayer install
vendor/bin/browsermob-proxy.sh start
java -jar vendor/bin/Sauce-Connect.jar &lt;saucelabs-username&gt; &lt;saucelabs-accesskey&gt; -p localhost:9091
</pre>

Just wait for it to say _Connected! You may start your tests._, and then you can run your Storyplayer tests, and Sauce Labs will automatically reconfigure its browsers to make their network connections through Sauce Connect.

If you don't use Sauce Connect for a period of time, it will automatically shut itself down.  It's also a good idea to restart it periodically (Sauce Labs call this keeping it 'fresh') to deal with memory leaks and the like.

## Testing Websites Behind HTTP Basic Auth

At the time of writing, Selenium WebDriver (the technology Storyplayer uses to control web browsers) does not provide working support for authenticating via HTTP Basic Auth.  To get around this, when you run tests against a browser on your own desktop, we proxy the web browser through browsermob-proxy, which can inject the HTTP Basic Auth credentials for us.

If your website requires HTTP Basic Auth, then you need to use the Sauce Connect tunnel to test your website.  This is true even if your website is on a public IP address.  Sauce Connect will use the locally-running browsermob-proxy, which allows us to inject the HTTP Basic Auth credentials.

<pre>
vendor/bin/browsermob-proxy.sh start
java -jar vendor/bin/Sauce-Connect.jar &lt;saucelabs-username&gt; &lt;saucelabs-accesskey&gt; -p localhost:9091
</pre>

Your test will need to tell Storyplayer which HTTP Basic Auth credentials to use, by calling [$st->usingBrowser()->setHttpBasicAuthForHost()](../../modules/browser/usingBrowser.html#sethttpbasicauthforhost).

## Known Differences Between Sauce Labs And Local Selenium WebDriver

We've observed a few differences between running tests using web browsers on your own desktop (ie using Selenium WebDriver locally) and running tests using web browsers running at Sauce Labs.  We'll keep this section up to date as we find more differences between the two.

### Browsers Can Persist Between Test Phases

When your test runs against a browser on your own desktop, every test phase starts with a completely fresh browser, with all cookies reset.  We do this for two reasons: to isolate your test phases properly (so that they are more reliable), and because (if you have a test that takes a long time to run) the browser may have timed out anyways.

However, with Sauce Labs, we've noticed that every test phase does not always start with a completely fresh browser.  As a result, you will need to adjust your test to cope with things such as cookies already being set, and potentially still being logged into your website.

### Performance Can Vary At Times

According to Sauce Labs documentation, their web browsers are running inside virtual machines on their infrastructure.  At busy times of the day, the hosted browsers can sometimes run slower than a browser running on your own desktop (especially if your desktop is a decent spec with tonnes of RAM and SSDs).  _This isn't a fault with Sauce Labs, just a natural consequence of using virtualised services._

This will affect any tests which have timeouts (such as waiting for a page to load).  For now, just adjust the timeouts.  A future version of Storyplayer will include some more help with this.

Our advice is to use Sauce Connect tunnels for functional testing, but to use Storyplayer in combination with a local web browser when you want to test that your website is fast enough.

### Using Sauce Connect Slows Down Your Tests

Sauce Lab's Sauce Connect is a network tunnel.  It allows web browsers running on their network to see web servers running on your network.  This means that network traffic goes like this:

<pre>
Sauce Labs     Internet         Your Network        Your Network and/or Internet
----------     --------         ------------        ----------------------------
web browser -&gt; Sauce Connect -&gt; browsermob-proxy -&gt; web server -&gt; -\
web browser &lt;- Sauce Connect &lt;- browsermob-proxy &lt;-----------------/
</pre>

If your connection to the internet is asymetrical (ie, you're on broadband, with different up and down speeds) or simply slow, then this is going to slow down your tests a bit.  How much depends on the speed of your connection to the internet, and how congested that is at the time that you run your tests.

Our advise is to use Sauce Connect tunnels for functional testing, but to use Storyplayer in combination with a local web browser when you want to test that your website is fast enough.