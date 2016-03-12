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
 * @package   Storyplayer/Modules/Users
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

use Storyplayer\SPv3\Modules\Users\FromUsers;
use Storyplayer\SPv3\Modules\Users\UsingUsers;

class Users
{
    /**
     * returns the FromUsers module
     *
     * This module allows you to retrieve specific user(s) from the test users
     * you loaded with the --users command-line switch. Each user is a plain
     * PHP object created using json_decode().
     *
     * Any changes you make to these users will be saved back to disk when
     * Storyplayer finishes the current test run. You can prevent this by using
     * the --readonly-users command-line switch.
     *
     * @return \Storyplayer\SPv3\Module\Users\FromUsers
     */
    public static function fromUsers()
    {
        return new FromUsers(StoryTeller::instance());
    }

    /**
     * returns the UsingUsers module
     *
     * This module provides support for working with the test users loaded via
     * the --users switch.
     *
     * Storyplayer uses this module internally to load and save the test users
     * file. You can also load your own files directly from your stories if you
     * need to.
     *
     * @return \Storyplayer\SPv3\Modules\Users\UsingUsers
     */
    public static function usingUsers()
    {
        return new UsingUsers(StoryTeller::instance());
    }

}
