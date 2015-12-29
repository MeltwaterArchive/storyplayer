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
 * @package   Storyplayer/CommandLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\CommandLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use Phix_Project\ContractLib2\Contract;
use Storyplayer\SPv2\Modules\Log;

/**
 * helper for running a command and managing its output
 *
 * @category  Libraries
 * @package   Storyplayer/CommandLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class CommandRunner
{
    public function runSilently($cmd)
    {
        // enforce our inputs
        Contract::RequiresValue($cmd, is_string($cmd));

        // what are we doing?
        $log = Log::usingLog()->startAction("run command: $cmd");

        // the output that we will return to the caller
        $output = '';

        // how we will talk with the command
        $pipesSpec = [
            [ 'file', 'php://stdin', 'r' ],
            [ 'pipe', 'w' ],
            [ 'pipe', 'w' ]
        ];
        $pipes = [];

        // start the process
        $process = proc_open($cmd, $pipesSpec, $pipes);

        // was there a problem?
        //
        // NOTE: this only occurs when something like a fork() failure
        // happens, which makes it very difficult to test for in a
        // unit test

        // @codeCoverageIgnoreStart
        if (!$process) {
            $return = new CommandResult(255, '');
            return $return;
        }
        // @codeCoverageIgnoreEnd

        // we do not want to block whilst reading from the child process's
        // stdout and stderr
        stream_set_blocking($pipes[1], 0);
        stream_set_blocking($pipes[2], 0);

        // at this point, our command may be running ...
        // OR our command may have failed with an error
        //
        // best thing to do is to keep reading from our pipes until
        // the pipes no longer exist
        while (!feof($pipes[1]) || !feof($pipes[2]))
        {
            // block until there is something to read, or until the
            // timeout has happened
            //
            // this makes sure that we do not burn CPU for the sake of it
            $readable = [ $pipes[1], $pipes[2] ];
            $writeable = $except = [];
            stream_select($readable, $writeable, $except, 1);

            // check all the streams for output
            if ($line = fgets($pipes[1])) {
                $log->captureOutput(rtrim($line));
                $output = $output . $line;
            }
            if ($line = fgets($pipes[2])) {
                $log->captureOutput(rtrim($line));
                $output = $output . $line;
            }
        }

        // at this point, our pipes have been closed
        // we can assume that the child process has finished
        $retval = proc_close($process);

        // all done
        $log->endAction("return code is '$retval'");
        $result = new CommandResult($retval, $output);
        return $result;
    }
}
