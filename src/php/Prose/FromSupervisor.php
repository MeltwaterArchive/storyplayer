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
 * @package   Storyplayer/Prose
 * @author    Shweta Saikumar <shweta.saikumar@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

use DataSift\Storyplayer\HostLib;
use DataSift\Storyplayer\OsLib;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\ObjectLib\BaseObject;

use GanbaroDigital\TextTools\Filters\FilterColumns;
use GanbaroDigital\TextTools\Filters\FilterForMatchingRegex;

use Storyplayer\SPv2\Modules\Host\HostBase;

/**
 * get information about a program running under supervisor
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Shweta Saikumar <shweta.saikumar@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromSupervisor extends HostBase
{
    public function getProgramIsRunning($programName)
    {
        // what are we doing?
        $log = usingLog()->startAction("is program '{$programName}' running under supervisor on host '{$this->args[0]}'?");

        // get the host details
        $hostDetails = $this->getHostDetails();

        //run the supervisorctl command
        $result = usingHost($hostDetails->hostId)->runCommandAndIgnoreErrors("sudo supervisorctl status");
        // |egrep '^$programName' | awk '{print \\$2}'");

        // did the command succeed?
        if ($result->didCommandFail()) {
            $msg = "command failed with return code '{$result->returnCode}' and output '{$result->output}'";
            $log->endAction($msg);
            throw Exceptions::newActionFailedException(__METHOD__);
        }

        // reduce the output down
        $lines = explode("\n", $result->output);
        $lines = FilterForMatchingRegex::against($lines, "/^$programName /");
        $lines = FilterColumns::from($lines, "1", ' ');

        if (empty($lines)) {
            $log->endAction("supervisor does not know about '{$programName}'");
            return false;
        }

        // what happened?
        if ($lines[0] == 'RUNNING') {
            $log->endAction('current status is RUNNING');
            return true;
        }

        // if we get here, then the program is not RUNNING, and we
        // treat that as a failure
        $log->endAction('current status is ' . $lines[0]);
        return false;
    }
}
