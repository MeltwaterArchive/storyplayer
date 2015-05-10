---
layout: v2/using-configuration
title: Overriding From The Command-Line
prev: '<a href="../../using/configuration/dot.notation.support.html">Prev: dot.notation.support</a>'
next: '<a href="../../using/configuration/device-config.html">Next: Per-Device Configuration</a>'
updated_for_v2: true
---

# Overriding From The Command-Line

In Storyplayer v1, you could use the `-D` command-line switch to override config file settings.

Although the switch is still there in SPv2, at the moment it currently does nothing. That's partly because we haven't wired it yet into the new internals that handle config files, and partly because no-one needs it since we separated out the system under test and test environment config files.

Are we going to bring it back? Possibly. If there's a compelling use for it, then we will update it to work fully with SPv2. Let us know if you need it.