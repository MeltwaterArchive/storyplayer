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
 * @package   Storyplayer/Modules/Timing
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules\Timing;

use Exception;
use DataSift\Stone\TimeLib\DateInterval;
use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Log;

/**
 * perform delayed actions
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingTimer extends Prose
{
    public function waitFor($callback, $timeout = 'PT5S')
    {
        // how long do we wait for?
        if (is_string($timeout)) {
            $interval = new DateInterval($timeout);
            $seconds  = $interval->getTotalSeconds();
        }
        else {
            $seconds = $timeout;
        }

        // when does this end?
        $now = time();
        $end = $now + $seconds;

        // what are we doing?
        $log = Log::usingLog()->startAction("polling for up to {$seconds} seconds");

        while ($now < $end) {
            try {
                $result = $callback($this->st);

                // if we get here, the actions inside the callback
                // must have worked
                $log->endAction();
                return;
            }
            catch (Exception $e) {
                // do nothing
                $log->closeAllOpenSubActions();
            }

            // has the action actually failed?
            if (isset($result) && !$result) {
                $log->endAction();
                throw Exceptions::newActionFailedException(__METHOD__);
            }

            // we don't want to use all the CPU resources
            sleep(1);

            // update the timeout
            $now = time();
        }

        // if we get here, then the timeout happened
        throw Exceptions::newActionFailedException('timer()->waitFor()');
    }

    public function waitWhile($callback, $timeout = 'PT5S')
    {
        if (is_string($timeout)) {
            $interval = new DateInterval($timeout);
            $seconds  = $interval->getTotalSeconds();
        }
        else {
            $seconds = $timeout;
        }

        $now = time();
        $end = $now + $seconds;

        // what are we doing?
        $log = Log::usingLog()->startAction("polling for up to '{$seconds}' seconds");

        while ($now < $end) {
            try {
                $result = $callback($this->st);

                // if we get here, the actions inside the callback
                // must have worked
                //
                // that means whatever we're waiting for hasn't happened
                // yet
                $log->closeAllOpenSubActions();
            }
            catch (Exception $e) {
                // the conditions have changed - we can go ahead now
                $log->endAction();
                return;
            }

            // has the action actually failed?
            if (isset($result) && !$result) {
                $log->endAction();
                throw Exceptions::newActionFailedException(__METHOD__);
            }

            // we don't want to use all the CPU resources
            sleep(1);

            // update the timeout
            $now = time();
        }

        // if we get here, then the timeout happened
        $log->endAction();
        throw Exceptions::newActionFailedException('timer()->waitWhile()');
    }

    public function wait($timeout = 'PT01M', $reason = "waiting for everything to catch up")
    {
        if (is_string($timeout)) {
            $interval = new DateInterval($timeout);
            $seconds  = $interval->getTotalSeconds();
        }
        else {
            $seconds = $timeout;
        }

        // what are we doing?
        $log = Log::usingLog()->startAction("sleeping for {$timeout}; reason is: '{$reason}'");

        // zzzzz
        sleep($seconds);

        // all done
        $log->endAction("finished sleeping");
    }
}
