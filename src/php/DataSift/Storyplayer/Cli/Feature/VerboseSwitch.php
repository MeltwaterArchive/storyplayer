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
 * Tell Storyplayer when to print longer strings to the log file
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Feature_VerboseSwitch extends CliSwitch
{
    public function __construct()
    {
        // define our name, and our description
        $this->setName('verbose');
        $this->setShortDescription('increase the amount of raw data written to storyplayer.log');
        $this->setLongDesc(
            "Both storyplayer.log and --dev mode capture raw data, to help make it easier to see "
            . "exactly what your test is sending and receiving from the system-under-test. Feedback "
            . "from users has been that can make it more difficult to read the --dev mode output "
            . "when all data is displayed. As a result, by default, Storyplayer only logs some "
            . "of the data that it sees, and will truncate long strings too."
            . PHP_EOL . PHP_EOL
            . "Unfortunately, that does make it hard to see why your test has failed without adding "
            . "more output and then running your test again."
            . PHP_EOL . PHP_EOL
            . "Use this switch to tell Storyplayer to log *all* data to storyplayer.log (and to the "
            . "console if you're using --dev mode). This is *highly recommended* when running your tests "
            . "using a CI solution."
        );

        // what are the switches?
        $this->addShortSwitch('V');
        $this->addLongSwitch('verbose');

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
        $engine->options->verbose = true;

        // tell the engine that it is done
        return new CliResult(CliResult::PROCESS_CONTINUE);
    }
}