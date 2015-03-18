---
layout: v2/using-configuration
title: Logging
next: '<a href="../../using/configuration/test-phases.html">Next: Test Phases Configuration</a>'
prev: '<a href="../../using/configuration/module-settings.html">Prev: Module Settings</a>'
---

# Logging

By default, Storyplayer is very verbose; it tells you everything that it is doing in great detail, so that you can see each step of each test that you run, and so that you can immediately see why any test failed.

If you'd prefer Storyplayer to output less information when it runs, you can add a _logger_ section to your [storyplayer.json](storyplayer-json.html) or [per-user config](user-config.html) files to switch off some of the output that is shown.

All logging config settings go under the _logger_ section inside your config file.

## Supported Log Levels

Storyplayer supports the following log levels:

* __EMERGENCY__ : currently unused
* __ALERT__ : currently unused
* __CRITICAL__ : used for fatal errors
* __ERROR__ : currently unused
* __WARNING__ : appears when an Storyplayer action fails for some reason
* __NOTICE__ : used for test phase banners and test results
* __INFO__ : used for every step that your test takes
* __DEBUG__ : used for every sub-step that your test takes
* __TRACE__ : used for every sub-step that your sub-steps take

You can toggle each setting on or off individually, as _logger->levels->LOG\_\*_ in your config file.

## Supported Log Outputs

Storyplayer can write its log messages to any output supported by Stone's LogLib.  At the time of writing, Stone provides the following outputs:

* _StdOutWriter_ - write log messages to stdout
* _StdErrWriter_ - write log messages to stderr (default value)

You can set the log output of your choice by changing the _logger->writer_ setting in your config file.

## An Example Config

Here's what Storyplayer's default settings for logging would look like, if they were listed in your [storyplayer.json](storyplayer-json.html) file:

{% highlight json %}
{
	"logger": {
		"writer": "StdErrWriter",
		"levels": {
			"LOG_EMERGENCY": true,
			"LOG_ALERT": true,
			"LOG_CRITICAL": true,
			"LOG_ERROR": true,
			"LOG_WARNING": true,
			"LOG_NOTICE": true,
			"LOG_INFO": true,
			"LOG_DEBUG": true,
			"LOG_TRACE": true
		}
	}
}
{% endhighlight %}