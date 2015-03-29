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
    /**
     * @param DataSift\Storyplayer\ConfigLib\TestEnvironmentsList $envList
     * @param string $defaultEnvName
     */
    public function __construct($envList, $defaultEnvName)
    {
        // define our name, and our description
        $this->setName('target');
        $this->setShortDescription('set the environment to test against');
        $this->setLongDesc(
            "If you have multiple test environments listed in your configuration files, "
            . "you can use this switch to choose which test environment to run the test(s) "
            . "against. If you omit this switch, Storyplayer will default to using your "
            . "computer's hostname as the value for <environment>."
            . PHP_EOL
            . PHP_EOL
            . "If you only have one test environment listed, then this switch has no "
            . "effect when used, and Storyplayer will always use the test environment "
            . "from your configuration file."
            . PHP_EOL
            . PHP_EOL
            . "See http://datasift.github.io/storyplayer/ "
            . "for how to configure and use multiple test environments."
        );

        // what are the short switches?
        $this->addShortSwitch('t');

        // what are the long switches?
        $this->addLongSwitch('target');
        $this->addLongSwitch('test-environment');

        // what is the required argument?
        $requiredArgMsg = "the environment to test against; one of:" . PHP_EOL . PHP_EOL;
        foreach($envList->getEntryNames() as $envName) {
            $requiredArgMsg .= "* $envName" . PHP_EOL;
        }
        $requiredArgMsg .= PHP_EOL. ' ';
        $this->setRequiredArg('<environment>', $requiredArgMsg);
        $this->setArgValidator(new Feature_TestEnvironmentConfigValidator($envList, $defaultEnvName));
        $this->setArgHasDefaultValueOf($defaultEnvName);

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
