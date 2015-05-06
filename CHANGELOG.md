# Changelog

New features and non-urgent bug fixes will go into the `develop` branch. The `develop` branch will become the next minor release of Storyplayer.

the `develop` branch will become:

* v2.1.0
* v2.2.0
* v2.3.0

... and so on.

Urgent bugfixes will go into their own `hotfix` branch, and be immediate released as a patch level release of Storyplayer. As long as you're using Hubflow, the hotfix branches will be automatically merged back into `develop` to also be part of the next minor release of Storyplayer.

## 2.4.0 - `develop` branch

The major focus for this release will be:

1. completing new-style test environment support for EC2 and Docker
1. completing new-style test environment support for Ansible
1. refactoring HostLib and OsLib away into our test environment adapters
1. more tests for Storyplayer itself

## 2.3.0 - Wednesday 6th May 2015

The major focus for this release has been building the new way of defining a test environment. This was necessary:

1. to make it possible to document how to define a test environment
1. to build a facade on top of our last piece of major technical debt (test environment adapters)

The older, JSON-based approach has not been dropped, but will remain undocumented. You are urged to migrate your test environment config files before SPv2.5 is released at the start of July.

### New:

* Centos 7.0 is now supported for test environment hosts.
* Ubuntu is now supported for test environment hosts :) Supported releases are:
  * Ubuntu 14.04 LTS
  * Ubuntu 14.10
  * Ubuntu 15.04
* Storyplayer now searches a `storyplayer` folder (without a dot at the front of the name) for your system-under-test and test environment config files
  * Falls back to searching the `.storyplayer` too.
* Test environments can now be defined in PHP.
  * Only Vagrant / Virtualbox is supported in this release
  * Support for all other test environment types will be added in SPv2.4.
* Support for multiple Vagrantfiles (one Vagrantfile per test environment)
* dsbuild files can now live in the same folder as the test environment config file
* `storyplayer/php` in your project is now automatically added to the PHP autoloader search path if it exists
  * use it for any local Storyplayer modules you want to publish
* `storyplayer/php/functions.php` in your project is now autoloaded if it exists
* [fromHost()->getLocalFolder()](https://datasift.github.io/storyplayer/modules/host/fromHost.html#getlocalfolder) - the folder containing the host's supporting files

## 2.2.1 - Friday 24th April 2014

Fixes:
- Browser module: can now search for labels
- Browser module: fromBrowser()->has() works once more
- Iterators: fix 'exception not found' error

## 2.2.0 - Tuesday 31st March 2015

### Backwards-compatibility Breaks:

These are SPv1 features that have been upgraded to support SPv2's new features such as test environments.

* [SavageD module](https://datasift.github.io/storyplayer/v2/modules/savaged/index.html) overhauled to support SPv2 test environments and host IDs

Other changes you need to know about:

* `browsermob-proxy` is now optional, and switched off by default. You can switch it back on if you [add these config settings](https://datasift.github.io/storyplayer/using/configuration/browsermob-proxy.html).

### New:

* New methods for _expectsBrowser()_ and _expectsForm()_:
  * [expectsBrowser()->isBlank()](https://datasift.github.io/storyplayer/v2/modules/browser/expectsBrowser.html#isblank) / [expectsForm()->isBlank()](https://datasift.github.io/storyplayer/v2/modules/form/expectsForm.html#isblank)
  * [expectsBrowser()->isNotBlank()](https://datasift.github.io/storyplayer/v2/modules/browser/expectsBrowser.html#isnotblank) / [expectsForm()->isNotBlank()](https://datasift.github.io/storyplayer/v2/modules/form/expectsForm.html#isnotblank)
  * [expectsBrowser()->isChecked()](https://datasift.github.io/storyplayer/v2/modules/browser/expectsBrowser.html#ischecked) / [expectsForm()->isChecked()](https://datasift.github.io/storyplayer/v2/modules/form/expectsForm.html#ischecked)
  * [expectsBrowser()->isNotChecked()](https://datasift.github.io/storyplayer/v2/modules/browser/expectsBrowser.html#isnotchecked) / [expectsForm()->isNotChecked()](https://datasift.github.io/storyplayer/v2/modules/form/expectsForm.html#isnotchecked)
* New `usingBrowser()->click()->firstXXX` et al [ordinal prefix for search terms](https://datasift.github.io/storyplayer/v2/modules/browser/ordinal-prefixes.html)
* New `expectsBrowser()->has()->oneXXX` et al [ordinal prefix for search terms](https://datasift.github.io/storyplayer/v2/modules/browser/ordinal-prefixes.html)
* [fromConfig()->getModuleSetting()](https://datasift.github.io/storyplayer/v2/modules/config/fromConfig.html#getmodulesetting) is now the preferred way for a module to get any `moduleSettings` config.
* [fromConfig()->hasModuleSetting()](https://datasift.github.io/storyplayer/v2/modules/config/fromConfig.html#hasmodulesetting) added.
* [Host module](https://datasift.github.io/storyplayer/v2/modules/host/index.html) can now start/stop any screen session in your test environment
* You can now override the default grace period in `usingHost()->stopProcess()`
* [fromStoryplayer() module](https://datasift.github.io/storyplayer/v2/modules/storyplayer/index.html)
* [New ZeroMQ module](https://datasift.github.io/storyplayer/v2/modules/zeromq/index.html)
* The old ZeroMQ module is now known as the [ZMQ module](https://datasift.github.io/storyplayer/v2/modules/zmq/index.html)
* you can now throw a `Prose\E4xx_StoryShouldFail` exception in your PreTestPrediction when you predict that the story should fail
* `src/bin/storyplayer` now uses Composer to work out what its version number is

### Fixes:

* [fromConfig() module](https://datasift.github.io/storyplayer/v2/modules/config/index.html) is now an internal module. Use the _fromStoryplayer()_ module instead.
* the [Graphite module](https://datasift.github.io/storyplayer/v2/modules/graphite/index.html) now looks in your test environment config file for its settings. You can have different Graphite servers for different test environments now :)
* [usingHost()->delete()](https://datasift/github.io/storyplayer/v2/modules/http/usingHttp.html#delete) no longer takes a `$body` parameter (violated the HTTP protocol standard)
* the PreTestPrediction phase works once more

### Deprecated

The following are now deprecated, and will be removed in Storyplayer v3.0.

* [appSettings](https://datasift.github.io/storyplayer/v2/using/deprecated/appSettings.html)

Full details, including migration instructions, are included with each link above.

## 2.1.2 - Tuesday 10th March 2015

### Fixes:

* Use correct VM name in VagrantVm when checking if the box is running

## 2.1.1 - Friday 6th March 2015

### Fixes:

* Initial support for using Vagrant with something other than Virtualbox

## 2.1.0 - Monday 2nd March 2015

### New features:

* $story->inGroup() now supports an array, or a string using ' > ' as the delimiter
* fromHttp()->get() now supports optional timeout parameter
* usingHttp()->delete() now supports optional timeout parameter
* usingHttp()->post() now supports optional timeout parameter
* usingHttp()->put() now supports optional timeout parameter

### Fixes:

Amazon AWS Module:

* now expects your AWS keys to be in 'moduleSettings.aws' in your `storyplayer.json` file.

Asserts Module:

* assertsDouble() now works (required fix in datasift/stone-1.9.6)
* assertsObject() now works with IteratorAggregate objects
* assertsString() no longer throws fatal errors when fed arrays or objects
* much better log messages from the Asserts module, especially when assertions fail

Users Module:

* usingUsers::saveUsersToFile() now pretty-prints the JSON for easier maintenance

### Test coverage:

Stories:

* assertsArray() module now covered
* assertsBoolean() module now covered
* assertsDouble() module now covered
* assertsInteger() module now covered
* assertsObject() module now covered
* assertsString() module now covered

## 2.0.2 - Tue 17th Feb, 2015

### Fixes

The --users switch introduced in 2.0.0 should now work as originally intended.

* no more fatal errors if --users switch not used
* --users switch will create file if it does not exist
* --users switch will accept an empty file
* better warning and error messages around --users problems

## 2.0.1 - Tue 17th Feb, 2015

### Fixes

* Checkpoint: make sure each story starts with an empty checkpoint

## 2.0.0 - Sun 15th Feb, 2015

Considered feature-complete / stable.

Highlights (compared to Storyplayer v1) include:

* Much better support for running multiple tests
* Test environment separation
* System under test separation
* New console support for ease of use
* storyplayer.log
* No more $st required in tests (still supported!)
* Output report support
* dot.notation.support for reading the config from inside stories
* Support for stories self-blacklisting themselves
* dsbuild (shell script) provisioner
* Support for multiple machines in test environments
* and more besides
