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
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\LogLib\Log;
use DataSift\Stone\TimeLib\DateInterval;

/**
 * Helper class for running an action for a precise period of time
 *
 * Longer term, we probably need to move into running the actions in
 * a subprocess of some kind, to make this much more robust than it is
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TimedAction
{
    protected $st;
    protected $action;
    protected $cleanupAction;
    protected $duration;

    public $terminate = false;

    public function __construct(StoryTeller $st, $action, $cleanupAction = null)
    {
        $this->st            = $st;
        $this->action        = $action;
        $this->cleanupAction = $cleanupAction;
    }

    public function forExactly($duration)
    {
        // what are we doing?
        $log = usingLog()->startAction("run for exactly '{$duration}'");

        // remember the duration
        //
        // the $action callback can then make use of it
        $this->duration = $duration;

        // convert the duration into seconds
        $interval = new DateInterval($duration);
        $seconds  = $interval->getTotalSeconds();

        // set the alarm
        pcntl_signal(SIGALRM, array($this, "handleSigAlarm"), FALSE);
        $log->addStep("setting SIGALRM for '{$seconds}' seconds", function() use($seconds) {
            pcntl_alarm($seconds);
        });

        declare(ticks=1);
        $callback = $this->action;
        $returnVal = $callback($this);

        // all done
        $log->endAction();
        return $returnVal;
    }

    public function handleSigAlarm()
    {
        // what are we doing?
        $log = usingLog()->startAction("SIGALRM received");

        // try and terminate the running code
        $this->terminate = true;

        if ($this->cleanupAction) {
            $callback = $this->cleanupAction;
            $callback();
        }

        // all done
        $log->endAction();
    }

    public function getDuration()
    {
        return $this->duration;
    }
}
