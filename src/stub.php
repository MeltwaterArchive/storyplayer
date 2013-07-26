#!/usr/bin/env php
<?php
// Alias the phar to phar://storyplayer for include paths
Phar::mapPhar('storyplayer');
// Boot the app
require 'phar://storyplayer/src/bin/storyplayer';
__HALT_COMPILER();
