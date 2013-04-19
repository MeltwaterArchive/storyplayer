<?php

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\LogLib\Log;
use DataSift\Stone\TimeLib\DateInterval;

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
        // shorthand
        $st = $this->st;

        // what are we doing?
        $log = $st->startAction("run for exactly '{$duration}'");

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
        Log::write(Log::LOG_DEBUG, __METHOD__ . '() called');

        // try and terminate the running code
        $this->terminate = true;

        if ($this->cleanupAction) {
            $callback = $this->cleanupAction;
            $callback();
        }
    }

    public function getDuration()
    {
        return $this->duration;
    }
}
