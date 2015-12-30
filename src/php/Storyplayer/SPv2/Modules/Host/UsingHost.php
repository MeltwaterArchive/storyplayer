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
 * @package   Storyplayer/Modules/Host
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules\Host;

use DataSift\Storyplayer\OsLib;
use DataSift\Stone\ObjectLib\BaseObject;

use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Filesystem;
use Storyplayer\SPv2\Modules\Host;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Modules\Screen;
use Storyplayer\SPv2\Modules\Shell;

/**
 * do things with vagrant
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Host
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingHost extends HostAwareModule
{
    /**
     * @deprecated since v2.4.0
     */
    public function runCommand($command)
    {
        return Shell::onHost($this->args[0])->runCommand($command);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function runCommandAsUser($command, $user)
    {
        return Shell::onHost($this->args[0])->runCommandAsUser($command, $user);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function runCommandAndIgnoreErrors($command)
    {
        return Shell::onHost($this->args[0])->runCommandAndIgnoreErrors($command);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function runCommandAsUserAndIgnoreErrors($command, $user)
    {
        return Shell::onHost($this->args[0])->runCommandAsUserAndIgnoreErrors($command, $user);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function startInScreen($sessionName, $commandLine)
    {
        return Screen::onHost($this->args[0])->startScreen($sessionName, $commandLine);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function stopInScreen($sessionName)
    {
        return Screen::onHost($this->args[0])->stopScreen($sessionName);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function stopAllScreens()
    {
        Deprecated::fireDeprecated(__METHOD__, "v2.4.0", ManualUrls::HOST_MODULE_BREAKUP);
        return Screen::onHost($this->args[0])->stopAllScreens();
    }

    public function stopProcess($pid, $grace = 5)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("stop process '{$pid}' on host '{$this->args[0]}'");

        // is the process running at all?
        if (!Host::fromHost($this->args[0])->getPidIsRunning($pid)) {
            $log->endAction("process is not running");
            return;
        }

        // yes it is, so stop it
        // send a TERM signal to the screen session
        $log->addStep("send SIGTERM to process '{$pid}'", function() use ($pid) {
            if ($this->getIsLocalhost()) {
                posix_kill($pid, SIGTERM);
            }
            else {
                Shell::onHost($this->args[0])->runCommand("kill {$pid}");
            }
        });

        // has this worked?
        $isStopped = $log->addStep("wait for process to terminate", function() use($pid, $grace, $log) {
            for($i = 0; $i < $grace; $i++) {
                if (!Host::fromHost($this->args[0])->getPidIsRunning($pid)) {
                    return true;
                }

                // process still exists
                sleep(1);
            }

            return false;
        });

        // did the process stop?
        if ($isStopped) {
            $log->endAction();
            return;
        }

        $log->addStep("send SIGKILL to process '{$pid}'", function() use($pid) {
            if ($this->getIsLocalhost()) {
                posix_kill($pid, SIGKILL);
            }
            else {
                Shell::onHost($this->args[0])->runCommand("kill -9 {$pid}");
            }
            sleep(1);
        });

        // success?
        if (Host::fromHost($this->args[0])->getProcessIsRunning($pid)) {
            $log->endAction("process is still running :(");
            throw Exceptions::newActionFailedException(__METHOD__);
        }

        // all done
        $log->endAction("process has finished");
    }

    /**
     * @deprecated since v2.4.0
     */
    public function uploadFile($sourceFilename, $destFilename)
    {
        return Filesystem::onHost($this->args[0])->uploadFile($sourceFilename, $destFilename);
    }
}
