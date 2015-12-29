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
 * @package   Storyplayer/Modules/Screen
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules\Screen;

use DataSift\Storyplayer\HostLib;
use DataSift\Storyplayer\OsLib;

use DataSift\Stone\DataLib\DataPrinter;
use DataSift\Stone\ObjectLib\BaseObject;

use GanbaroDigital\TextTools\Filters\FilterColumns;
use GanbaroDigital\TextTools\Filters\FilterForMatchingRegex;
use GanbaroDigital\TextTools\Filters\FilterForMatchingString;

use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Host;
use Storyplayer\SPv2\Modules\Host\HostAwareModule;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Modules\Screen;
use Storyplayer\SPv2\Modules\Shell;

/**
 * get information about a given host
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Screen
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromScreen extends HostAwareModule
{
    /**
     * @param  string $sessionName
     * @return bool
     */
    public function getScreenIsRunning($sessionName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("check if screen session '{$sessionName}' is still running");

        // get the details
        $sessionData = Screen::fromHost($this->args[0])->getScreenSessionDetails($sessionName);

        // all done
        if ($sessionData) {
            $log->endAction("still running");
            return true;
        }
        else {
            $log->endAction("not running");
            return false;
        }
    }

    /**
     * @param  string $sessionName
     * @return BaseObject|null
     */
    public function getScreenSessionDetails($sessionName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get details about screen session '{$sessionName}' on host '{$this->args[0]}' from Storyplayer");

        // are there any details?
        $cmd = "screen -ls";
        $result = Shell::onHost($this->args[0])->runCommandAndIgnoreErrors($cmd);

        // NOTE:
        //
        // screen is not a well-behaved UNIX program, and its exit code
        // can be non-zero when everything is good
        if (empty($result->output)) {
            $msg = "unable to get list of screen sessions";
            $log->endAction($msg);
            return null;
        }

        // do we have the session we are looking for?
        $lines = explode("\n", $result->output);
        $lines = FilterForMatchingRegex::against($lines, "/[0-9]+\\.{$sessionName}\t/");
        $lines = FilterColumns::from($lines, "0", '.');

        if (empty($lines)) {
            $msg = "screen session '{$sessionName}' is not running";
            $log->endAction($msg);
            return null;
        }

        // there might be
        $processDetails = new BaseObject;
        $processDetails->hostId = $this->args[0];
        $processDetails->name = $sessionName;
        $processDetails->type = 'screen';
        $processDetails->pid = trim(rtrim($lines[0]));

        // all done
        $log->endAction("session is running as PID '{$processDetails->pid}'");
        return $processDetails;
    }

    /**
     * @return array<object>
     */
    public function getAllScreenSessions()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get details about all screen sessions on host '{$this->args[0]}'");

        // are there any details?
        $cmd = "screen -ls";
        $result = Shell::onHost($this->args[0])->runCommandAndIgnoreErrors($cmd);

        // NOTE:
        //
        // screen is not a well-behaved UNIX program, and its exit code
        // can be non-zero when everything is good
        if (empty($result->output)) {
            $msg = "unable to get list of screen sessions";
            $log->endAction($msg);
            return [];
        }

        // reduce the output down to a list of sessions
        $lines = explode("\n", $result->output);
        $lines = FilterForMatchingRegex::against($lines, "/[0-9]+.+\t/");

        if (empty($lines)) {
            $msg = "no screen processes running";
            $log->endAction($msg);
            return [];
        }

        $retval = [];
        foreach ($lines as $line) {
            $parts = explode('.', $line);

            $processDetails = new BaseObject;
            $processDetails->hostId = $this->args[0];
            $processDetails->type = 'screen';
            $processDetails->pid  = trim($parts[0]);
            $processDetails->name = rtrim($parts[1]);

            $retval[] = $processDetails;
        }

        // all done
        $log->endAction("found " . count($retval) . " screen process(es)");

        // all done
        return $retval;
    }
}
