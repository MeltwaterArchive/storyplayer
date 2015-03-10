# Changelog

New features and non-urgent bug fixes will go into the `develop` branch. The `develop` branch will become the next minor release of Storyplayer.

the `develop` branch will become:

* v2.1.0
* v2.2.0
* v2.3.0

... and so on.

Urgent bugfixes will go into their own `hotfix` branch, and be immediate released as a patch level release of Storyplayer. As long as you're using Hubflow, the hotfix branches will be automatically merged back into `develop` to also be part of the next minor release of Storyplayer.

## 2.2.0 - `develop` branch

### New:

* [fromStoryplayer() module](https://datasift.github.io/storyplayer/v2/modules/storyplayer/index.html)

### Fixes:

* [fromConfig() module](https://datasift.github.io/storyplayer/v2/modules/config/index.html) is now an internal module. Use the _fromStoryplayer()_ module instead.

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
