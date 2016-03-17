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
 * Tell Storyplayer which test environment to test against; for when there
 * is more than one test environment defined
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Feature_TestEnvironmentConfigSwitch extends CliSwitch
{
    public function __construct()
    {
        // define our name, and our description
        $this->setName('target');
        $this->setShortDescription('load a test environment setup/teardown script');
        $this->setLongDesc(
            "Use this switch to load the setup/teardown script for your test environment."
            . PHP_EOL
            . PHP_EOL
            . "You can use test environment scripts to describe any of your development, "
            . "test, pre-production / staging, and production environments. A test "
            . "environment script can also create and destroy dynamic test environments."
            . PHP_EOL
            . PHP_EOL
            . "Your test environment script's filename must end in 'Env.php'."
            . PHP_EOL
            . PHP_EOL
            . "If you omit this switch, Storyplayer will load a default test environment "
            . "config. The default config assumes that you are testing against your local "
            . "computer."
        );

        // what are the short switches?
        $this->addShortSwitch('t');

        // what are the long switches?
        $this->addLongSwitch('target');
        $this->addLongSwitch('test-environment');

        // where is our default script?
        $defaultTargetScript = realpath(__DIR__ . "/../../../../StoryplayerInternals/SPv3/Defaults/defaultEnv.php");

        // what is the required argument?
        $this->setRequiredArg('<testEnv.php>', "path to a test environment setup/teardown script");
        $this->setArgValidator(new Feature_TestEnvironmentConfigValidator());
        $this->setArgHasDefaultValueOf($defaultTargetScript);

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
        // strip off .json if it is there
        $params[0] = basename($params[0], '.json');

        // remember the setting
        $engine->options->testEnvironmentName = $params[0];

        // tell the engine that it is done
        return new CliResult(CliResult::PROCESS_CONTINUE);
    }
}
