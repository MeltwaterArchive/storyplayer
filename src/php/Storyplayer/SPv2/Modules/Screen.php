<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Storyplayer
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use Storyplayer\SPv2\Modules\Screen\ExpectsScreen;
use Storyplayer\SPv2\Modules\Screen\FromScreen;
use Storyplayer\SPv2\Modules\Screen\UsingScreen;

class Screen
{
    /**
     * returns the ExpectsScreen module
     *
     * This module provides support for checking on screen sessions running
     * on a (possibly) remote machine.  If the check fails, an exception is
     * thrown for you.
     *
     * In SPv1, it was common to call this module directly from your own stories.
     * In SPv2, you're much more likely to use one of our multi-host modules or
     * helpers (such as usingFirstHostWithRole) so that your stories are as
     * test-environment-independent as possible.
     *
     * @param  string $hostId
     *         the ID of the host to use
     * @return ExpectsScreen
     */
    public static function expectsHost($hostId)
    {
        return new ExpectsScreen(StoryTeller::instance(), [$hostId]);
    }

    /**
     * returns the ExpectsScreen module
     *
     * This module provides support for checking on screen sessions running
     * on the machine where Storyplayer is running.  If the check fails, an
     * exception is thrown for you.
     *
     * @return ExpectsScreen
     */
    public static function expectsLocalhost()
    {
        return new ExpectsScreen(StoryTeller::instance(), ['localhost']);
    }

    /**
     * returns the FromScreen module
     *
     * This module provides support for checking on the state of screen
     * sessions on computers in your test environment.
     *
     * In SPv1, it was common to call this module directly from your own stories.
     * In SPv2, you're much more likely to use one of our multi-host modules or
     * helpers (such as usingFirstHostWithRole) so that your stories are as
     * test-environment-independent as possible.
     *
     * @param  string $hostId
     *         the ID of the host to use
     * @return FromScreen
     */
    public static function fromHost($hostId)
    {
        return new FromScreen(StoryTeller::instance(), [$hostId]);
    }

    /**
     * returns the FromScreen module
     *
     * This module provides support for checking on the state of screen
     * sessions running on the same computer that Storyplayer is running on.
     *
     * @return FromScreen
     */
    public static function fromLocalhost()
    {
        return new FromScreen(StoryTeller::instance(), ['localhost']);
    }

    /**
     * returns the UsingScreen module
     *
     * This module provides support for running commands on a computer in your
     * test environment inside a screen session.
     *
     * In SPv1, it was common to call this module directly from your own stories.
     * In SPv2, you're much more likely to use one of our multi-host modules or
     * helpers (such as usingFirstHostWithRole) so that your stories are as
     * test-environment-independent as possible.
     *
     * @param  string $hostId
     *         the ID of the host to use
     * @return UsingScreen
     */
    public static function onHost($hostId)
    {
        return new UsingScreen(StoryTeller::instance(), [$hostId]);
    }

    /**
     * returns the UsingScreen module
     *
     * This module provides support for running commands on the same computer
     * that Storyplayer is running on, inside a screen session.
     *
     * @return UsingScreen
     */
    public static function onLocalhost()
    {
        return new UsingScreen(StoryTeller::instance(), ['localhost']);
    }
}
