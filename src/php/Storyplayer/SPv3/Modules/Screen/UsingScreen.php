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

namespace Storyplayer\SPv3\Modules\Screen;

use DataSift\Storyplayer\OsLib;
use DataSift\Stone\ObjectLib\BaseObject;

use Storyplayer\SPv3\Modules\Exceptions;
use Storyplayer\SPv3\Modules\Host;
use Storyplayer\SPv3\Modules\Host\HostAwareModule;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Modules\Screen;
use Storyplayer\SPv3\Modules\Shell;

/**
 * do things with (possibly remote) screen sessions
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Screen
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingScreen extends HostAwareModule
{
    public function startScreen($sessionName, $commandLine)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("start screen session '{$sessionName}' ({$commandLine}) on host '{$this->args[0]}'");

        // do we already have this session running on the host?
        Screen::expectsHost($this->args[0])->screenIsNotRunning($sessionName);

        // build up our command to run
        $commandLine = 'screen -L -d -m -S "' . $sessionName . '" bash -c "' . $commandLine . '"';

        // run our command
        //
        // this creates a detached screen session called $sessionName
        Shell::onHost($this->args[0])->runCommand($commandLine);

        // find the PID of the screen session, for future use
        $sessionDetails = Screen::fromHost($this->args[0])->getScreenSessionDetails($sessionName);

        // did the process start, or has it already terminated?
        if (empty($sessionDetails->pid)) {
            $log->endAction("session failed to start, or command exited quickly");
            throw Exceptions::newActionFailedException(__METHOD__, "failed to start session '{$sessionName}'");
        }

        // all done
        $log->endAction("session running as PID {$sessionDetails->pid}");
    }

    public function stopScreen($sessionName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("stop screen session '{$sessionName}' on host '{$this->args[0]}'");

        // get the process details
        $processDetails = Screen::fromHost($this->args[0])->getScreenSessionDetails($sessionName);

        // stop the process
        Host::onHost($this->args[0])->stopProcess($processDetails->pid);

        // all done
        $log->endAction();
    }

    public function stopAllScreens()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("stop all running screen sessions on host '{$this->args[0]}'");

        // get the app details
        $processes = Screen::fromHost($this->args[0])->getAllScreenSessions();

        // stop the process
        foreach ($processes as $processDetails) {
            Host::onHost($this->args[0])->stopProcess($processDetails->pid);
            usingProcessesTable()->removeProcess($this->args[0], $processDetails);
        }

        // all done
        $log->endAction("stopped " . count($processes) . " session(s)");
    }
}
