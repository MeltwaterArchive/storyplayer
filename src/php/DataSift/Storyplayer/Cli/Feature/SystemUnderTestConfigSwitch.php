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
 * Tell Storyplayer which system we want to test
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Feature_SystemUnderTestConfigSwitch extends CliSwitch
{
    /**
     * @param \DataSift\Storyplayer\ConfigLib\SystemsUnderTestList $sutList
     * @param string $defaultSutName
     */
    public function __construct($sutList, $defaultSutName)
    {
        // define our name, and our description
        $this->setName('sut');
        $this->setShortDescription('set the system-under-test to test');
        $this->setLongDesc(
            "If you have a test repository that contains tests for multiple "
            . "pieces of software, you can use this switch to choose which "
            . "of those systems-under-test to deploy to the test environment."
            . PHP_EOL
            . PHP_EOL
            . "If you omit this switch, Storyplayer will use the default system-under-test "
            . "listed in your storyplayer.json[.dist] config file. And if you don't have "
            . "a default system-under-test in your config file, then Storyplayer will "
            . "remind you that you need to tell it which system-under-test to target."
            . PHP_EOL
            . PHP_EOL
            . "If you only have one system-under-test defined, then this switch has no "
            . "effect when used, and Storyplayer will always use the system-under-test "
            . "that you have defined."
            . PHP_EOL
            . PHP_EOL
            . "See http://datasift.github.io/storyplayer/ "
            . "for how to define systems-under-test."
        );

        // what are the short switches?
        $this->addShortSwitch('s');

        // what are the long switches?
        $this->addLongSwitch('sut');
        $this->addLongSwitch('system-under-test');

        // what is the required argument?
        $requiredArgMsg = "the system-under-test to test; one of:" . PHP_EOL . PHP_EOL;
        foreach($sutList->getEntryNames() as $sutName) {
            $requiredArgMsg .= "* $sutName" . PHP_EOL;
        }
        if ($defaultSutName) {
            $requiredArgMsg .= PHP_EOL. ' ';
        }
        $this->setRequiredArg('<system-under-test>', $requiredArgMsg);
        $this->setArgValidator(new Feature_SystemUnderTestConfigValidator($sutList, $defaultSutName));
        if ($defaultSutName) {
            $this->setArgHasDefaultValueOf($defaultSutName);
        }

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
        // strip off .json if it is there
        $params[0] = basename($params[0], '.json');

        // remember the setting
        $engine->options->sutName = $params[0];

        // tell the engine that it is done
        return new CliResult(CliResult::PROCESS_CONTINUE);
    }
}
