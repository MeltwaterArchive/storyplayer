---
layout: v1/devices
title: How We Control Web Browsers
prev: '<a href="../devices/index.html">Prev: Testing With Different Devices</a>'
next: '<a href="../devices/localbrowsers.html">Next: Testing With Locally Running Web Browsers</a>'
---

# How We Control Web Browsers

## Selenium WebDriver

Storyplayer talks to web browsers via [Selenium WebDriver](http://docs.seleniumhq.org) and [the JSON Wire Protocol](https://code.google.com/p/selenium/wiki/JsonWireProtocol).

Selenium WebDriver is the successor to the popular Selenium browser-testing toolkit.  It's built around an automation API known as WebDriver.  Each browser provides support for WebDriver; some browsers have that support built-in, and some browsers need an app to act as a bridge between WebDriver and the browser's own automation API.  The Selenium Server acts as a bridge between apps such as Storyplayer and each browser (and their bridge, if they have one).  Storyplayer talks to the Selenium Server over the network using the JSON Wire Protocol.  It's a simple HTTP-based request/response API.

## Browsermob-Proxy

Selenium WebDriver's functionality is based around controlling a web browser, and inspecting the DOM.  It currently doesn't have a lot of functionality around the networking aspects of what a browser does.

Storyplayer tells every browser it controls to send all of their requests through a HTTP proxy called [browsermob-proxy](https://github.com/webmetrics/browsermob-proxy).  browsermob-proxy as an API of its own, and together with Selenium WebDriver, this gives Storyplayer all the functionality it needs to test websites in a meaningful way.

## Dependencies That We Install

The `storyplayer install` command downloads the following apps and libraries that we need to control web browsers:

* [browsermob-proxy](https://github.com/webmetrics/browsermob-proxy) - HTTP proxy with REST API
* [Selenium WebDriver](http://docs.seleniumhq.org/) - web browser remote control with REST API
* [ChromeDriver](https://code.google.com/p/selenium/wiki/ChromeDriver) - WebDriver bridge between Selenium and Google Chrome
* [PHP WebDriver Client](https://github.com/datasift/php_webdriver) - client library for talking to Selenium, originally by [Facebook](http://facebook.com)
* [Sauce Labs Sauce Connect](http://saucelabs.com) - network tunnel when using browsers hosted at Sauce Labs

