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
 * @package   Storyplayer/Modules/Shell
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use Storyplayer\SPv3\Modules\Shell\ExpectsShell;
use Storyplayer\SPv3\Modules\Shell\FromShell;
use Storyplayer\SPv3\Modules\Shell\UsingShell;

class Shell
{
    /**
     * returns the ExpectsShell module
     *
     * This module provides support for running commands via the UNIX shell.
     * These commands will run on a computer in your test environment.
     *
     * @return \Storyplayer\SPv3\Modules\Shell\ExpectsShell
     */
    public static function expectsHost($hostId)
    {
        return new ExpectsShell(StoryTeller::instance(), [$hostId]);
    }

    /**
     * returns the ExpectsShell module
     *
     * This module provides support for running commands via the UNIX shell.
     * These commands will run on the same computer where Storyplayer is running.
     *
     * @return \Storyplayer\SPv3\Modules\Shell\ExpectsShell
     */
    public static function expectsLocalhost()
    {
        return new ExpectsShell(StoryTeller::instance(), ['localhost']);
    }

    /**
     * returns the FromShell module
     *
     * This module provides support for running commands via the UNIX shell.
     * These commands will run on a computer in your test environment.
     *
     * The commands available via this module are for looking up information.
     * They do not make any changes to the computer at all.
     *
     * @return \Storyplayer\SPv3\Modules\Shell\FromShell
     */
    public static function fromHost($hostId)
    {
        return new FromShell(StoryTeller::instance(), [$hostId]);
    }

    /**
     * returns the FromShell module
     *
     * This module provides support for running commands via the UNIX shell.
     * These commands will run on the same computer where Storyplayer is running.
     *
     * The commands available via this module are for looking up information.
     * They do not make any changes to the computer at all.
     *
     * @return \Storyplayer\SPv3\Modules\Shell\FromShell
     */
    public static function fromLocalhost()
    {
        return new FromShell(StoryTeller::instance(), ['localhost']);
    }

    /**
     * returns the UsingShell module
     *
     * This module provides support for running commands via the UNIX shell.
     * These commands will run on a computer in your test environment.
     *
     * @return \Storyplayer\SPv3\Modules\Shell\UsingShell
     */
    public static function onHost($hostId)
    {
        return new UsingShell(StoryTeller::instance(), [$hostId]);
    }

    /**
     * returns the UsingShell module
     *
     * This module provides support for running commands via the UNIX shell.
     * These commands will run on the same computer where Storyplayer is running.
     *
     * @return \Storyplayer\SPv3\Modules\Shell\UsingShell
     */
    public static function onLocalhost()
    {
        return new UsingShell(StoryTeller::instance(), ['localhost']);
    }
}
