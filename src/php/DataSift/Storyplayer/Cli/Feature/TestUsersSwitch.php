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
 * Tell Storyplayer which file to use to load/save test user credentials to
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Feature_TestUsersSwitch extends CliSwitch
{
    public function __construct()
    {
        // define our name, and our description
        $this->setName('users');
        $this->setShortDescription('choose a file for loading/saving test users from');
        $this->setLongDesc(
            "If you're testing a web-based application or an API, your tests will probably"
            . " need to be given details about test users to create or re-use."
            . PHP_EOL . PHP_EOL
            . "Use this switch to tell Storyplayer which JSON file has the details of your "
            . "test users. Storyplayer will load this file on startup and make the users "
            . "available through the built-in Users module."
            . PHP_EOL . PHP_EOL
            . "Any changes will be saved back to the file when the story terminates. You can "
            . "tell Storyplayer to treat the file as read-only using the --read-only-users "
            . "switch"
        );

        // what are the short switches?
        $this->addShortSwitch('u');

        // what are the long switches?
        $this->addLongSwitch('users');

        // what is the required argument?
        $requiredArgMsg = "the JSON file containing the users";
        $this->setRequiredArg('<users-file>', $requiredArgMsg);

        // all done
    }

    /**
     *
     * @param CliEngine $engine
     * @param integer   $invokes
     * @param array     $params
     * @param boolean   $isDefaultParam
     * @param mixed     $additionalContext
     *
     * @return CliResult
     */
    public function process(CliEngine $engine, $invokes = 1, $params = array(), $isDefaultParam = false,
        $additionalContext = null)
    {
        // remember the setting
        $engine->options->testUsersFile = $params[0];

        // tell the engine that it is done
        return new CliResult(CliResult::PROCESS_CONTINUE);
    }
}
