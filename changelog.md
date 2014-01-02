---
layout: top-level
title: ChangeLog
prev: '<a href="modules/zeromq/usingZmq.html">Prev: usingZmq()</a>'
next: '<a href="copyright.html">Next: Legal Stuff</a>'
---

# ChangeLog

## v1.5.0

Released 2nd January 2014.

### New

* Per-device config files
* `deviceSetup()` / `deviceTeardown()` support
* Environment safeguarding
* Prose module shutdown hooks
* Iframe support in browsers
* Multi-window support in browsers
* Can close browser windows without closing the browser session
* `storyplayer show-environment` command
* Sauce Labs devices supported out of the box
* storyplayer.phar (experimental!)

### Fixed

* default environment is never the FQDN
* `tryTo()` works once again

## v1.4.2

Released 13th September 2013.

### New

* Added basic FromCurl module for making HTTP requests
* Added FromFacebook and UsingFacebookGraphApi modules

### Fixed

* Added ability to use a per-user config file without project specific config
* Moved to using call_user_func($callback) over $callback() for PHP 5.3 compatibility

## v1.4.1

Released 6th September 2013.

### Fixed

* Fixed bug where you couldn't run Storyplayer without creating an environment config file first.([#65](https://github.com/datasift/storyplayer/pull/65))

## v1.4.0

Released 6th September 2013.

### New

* `-D` switch for passing params into stories ([#37](https://github.com/datasift/storyplayer/pull/37))
* better sub-process handling ([#46](https://github.com/datasift/storyplayer/pull/46))
* create-story command ([#47](https://github.com/datasift/storyplayer/pull/47))
* [Firefox and Safari support](devices/localwebbrowsers.html) ([#45](https://github.com/datasift/storyplayer/pull/45))
* [Prose modules can have their own namespaces](prose/module-namespaces.html) ([#55](https://github.com/datasift/storyplayer/pull/55))
* [Remote WebDriver](devices/remotewebdriver.html) for advanced browser / device testing ([#62](https://github.com/datasift/storyplayer/pull/62))
* [Resize current browser window](modules/browser/usingBrowser.html#resizecurrentwindow) ([#52](https://github.com/datasift/storyplayer/pull/52))
* [Sauce Labs integration](devices/saucelabs.html) for cross-browser / cross-device testing ([#57](https://github.com/datasift/storyplayer/pull/57))
* [Simplified module loading](prose/module-loading.html) ([#56](https://github.com/datasift/storyplayer/pull/56))
* works without any config files ([#40](https://github.com/datasift/storyplayer/pull/40))

### Fixed

* Browser module: drop the on-screen / off-screen check ([#53](https://github.com/datasift/storyplayer/pull/53))
* usingBrowser()->getValue() now works with input fields too ([#54](https://github.com/datasift/storyplayer/pull/54))
* usingShell()->runCommand() now returns all lines of output, not just the last one ([#58](https://github.com/datasift/storyplayer/pull/58))
