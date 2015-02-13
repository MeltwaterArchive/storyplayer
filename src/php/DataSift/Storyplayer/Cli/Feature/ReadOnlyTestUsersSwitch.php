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
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliResult;
use Phix_Project\CliEngine\CliSwitch;

/**
 * Tell Storyplayer to treat the test users file as read-only
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Feature_ReadOnlyTestUsersSwitch extends CliSwitch
{
    public function __construct()
    {
        // define our name, and our description
        $this->setName('read-only-users');
        $this->setShortDescription('do not save changes to test users');
        $this->setLongDesc(
            "If you're using the --users switch to load a list of test users, Storyplayer's "
            . "default behaviour is to save any changed test user data back to this file "
            . "when the tests have finished running"
            . PHP_EOL . PHP_EOL
            . "Use this switch to tell Storyplayer that it should never save data back to your "
            . " test users file."
            . PHP_EOL . PHP_EOL
            . "This is useful if your tests are running against an on-demand test environment, "
            . "where users are created at the start of your tests and destroyed before the tests "
            . "are repeated."
            . PHP_EOL . PHP_EOL
            . "You can also set this in your test environment config, using the 'users.readOnlyTestUsers' "
            . "module setting."
        );

        // what are the short switches?
        // there are none

        // what are the long switches?
        $this->addLongSwitch('read-only-users');

        // all done
    }

    /**
     *
     * @param  CliEngine $engine
     * @param  integer   $invokes
     * @param  array     $params
     * @param  boolean   $isDefaultParam
     * @return CliResult
     */
    public function process(CliEngine $engine, $invokes = 1, $params = array(), $isDefaultParam = false)
    {
        // remember the setting
        $engine->options->readOnlyTestUsers = true;

        // tell the engine that it is done
        return new CliResult(CliResult::PROCESS_CONTINUE);
    }
}
