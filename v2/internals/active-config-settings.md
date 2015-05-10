---
layout: v2/internals
title: Config Settings Available At Runtime
updated_for_v2: true
prev: '<a href="../internals/index.html">Prev: Storyplayer Internals</a>'
next: '<a href="../changelog.html">Next: ChangeLog</a>'
---
# Config Settings Available At Runtime

Your stories and custom modules have complete access to all of Storyplayer's internal configuration. This is a complete list (in [../configuration/dot.notation.support](dot.notation.support.html)) of the configuration available.

<div class="callout warning" markdown="1">
#### A Work In Progress

We're working to make Storyplayer even more modular than it already is. As a result, some of the configuration documented here has not yet been implemented.
</div>

## Prefixes

### Config Files

Active config files are available under these prefixes:

Prefix           | Loaded From File
-----------------|-----------------
storyplayer.     | [the storyplayer.json config file](../configuration/storyplayer-json.html)
systemundertest. | [the system under test config file](../configuration/systemd-under-test-config.html)
target.          | [the test environment config file](../configuration/test-environment-config.html)
user.            | [the user's .storyplayer.json config file](../configuration/user-dot-config.html)

### Modules

The config files for modules are available under these prefixes:

Prefix                          | Description
--------------------------------|------------
commands.&lt;name&gt;           | Config for each loaded command
commands.&lt;name&gt;.name      | Name of the loaded command
commands.&lt;name&gt;.args.     | Ordered list of arguments that the command accepts
commands.&lt;name&gt;.phases.   | Ordered list of steps to run for the command
commands.&lt;name&gt;.switches. | Unordered list of CLI switches that the command supports
consoles.&lt;name&gt;           | Config for each loaded console
reports.&lt;name&gt;            | Config for each loaded report
themes.&lt;name&gt;             | Colour scheme theme

## Settings

Setting                 | Description
------------------------|------------
storyplayer.currentDir  | The folder where Storyplayer is being run from
storyplayer.ipv4Address | The IPv4 address of the machine where Storyplayer is running
storyplayer.user.home   | The $HOME folder of the user who is running Storyplayer
