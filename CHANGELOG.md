# Changelog

New features and non-urgent bug fixes will go into the `develop` branch. The `develop` branch will become the next minor release of Storyplayer.

the `develop` branch will become:

* v2.1.0
* v2.2.0
* v2.3.0

... and so on.

Urgent bugfixes will go into their own `hotfix` branch, and be immediate released as a patch level release of Storyplayer. As long as you're using Hubflow, the hotfix branches will be automatically merged back into `develop` to also be part of the next minor release of Storyplayer.

## 2.1.0 - `develop` branch

### New features:

* $story->inGroup() now supports an array, or a string using ' > ' as the delimiter

### Fixes:

* None yet

### Test coverage:

Stories:

* assertsArray() module now covered
* assertsBoolean() module now covered

Unit tests:

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