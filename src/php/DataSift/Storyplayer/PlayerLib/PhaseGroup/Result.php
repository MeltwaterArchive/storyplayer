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
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Storyplayer\PlayerLib\Story;

/**
 * a record of what happened with a phase group
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class PhaseGroup_Result
{
    /**
     * the name of the group that we are reporting on
     *
     * this is an end-user name (e.g. the name of the story) rather than
     * the name of the internal class
     *
     * @var string
     */
    public $name = null;

    /**
     * the reason that the PhaseGroup is running at all
     *
     * @var string
     */
    public $activity = null;

    /**
     *
     * @var PhaseResult
     */
    public $failedPhase     = null;

    /**
     * did the story pass, fail, or otherwise go horribly wrong?
     * @var integer
     */
    public $resultCode     = 0;

    /**
     * when did we start playing this story?
     * @var float
     */
    public $startTime       = null;

    /**
     * when did we finish playing this story?
     * @var float
     */
    public $endTime         = null;

    /**
     * how long did the story take to play?
     * @var float
     */
    public $durationTime    = null;

    /**
     * which file is this PhaseGroup_Result associated with?
     * @var string
     */
    public $filename        = null;

    const UNKNOWN     = 0;
    const OKAY        = 1;
    const FAIL        = 2;
    const ERROR       = 3;
    const INCOMPLETE  = 4;
    const BLACKLISTED = 5;
    const SKIPPED     = 6;

    public $resultStrings = [
        'UNKNOWN',
        'OKAY',
        'FAIL',
        'ERROR',
        'INCOMPLETE',
        'BLACKLISTED',
        'SKIPPED',
    ];

    public function __construct($name)
    {
        // remember our name - we're going to need it when writing
        // out reports
        $this->name = $name;

        // remember when we were created - we're going to treat that
        // as the start time for this story!
        $this->startTime = microtime(true);
    }

    /**
     * @return void
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
    }

    /**
     * @return void
     */
    public function setPhaseGroupHasSucceeded()
    {
        $this->resultCode  = self::OKAY;
        $this->failedPhase = null;
        $this->setEndTime();
    }

    /**
     * @return void
     */
    public function setPhaseGroupHasBeenSkipped()
    {
        $this->resultCode  = self::SKIPPED;
        $this->failedPhase = null;
        $this->setEndTime();
    }

    /**
     * @return void
     */
    public function setPhaseGroupHasBeenBlacklisted($phaseResult)
    {
        $this->resultCode  = self::BLACKLISTED;
        $this->failedPhase = $phaseResult;
        $this->setEndTime();
    }

    /**
     * @return void
     */
    public function setPhaseGroupIsIncomplete($phaseResult)
    {
        $this->resultCode  = self::INCOMPLETE;
        $this->failedPhase = $phaseResult;
        $this->setEndTime();
    }

    /**
     * @return void
     */
    public function setPhaseGroupHasFailed($phaseResult)
    {
        $this->resultCode  = self::FAIL;
        $this->failedPhase = $phaseResult;
        $this->setEndTime();
    }

    /**
     * @return void
     */
    public function setPhaseGroupHasError($phaseResult)
    {
        $this->resultCode  = self::ERROR;
        $this->failedPhase = $phaseResult;
        $this->setEndTime();
    }

    /**
     * @return bool
     */
    public function getPhaseGroupSucceeded()
    {
        if (self::OKAY == $this->resultCode) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function getPhaseGroupFailed()
    {
        switch ($this->resultCode)
        {
            case self::FAIL:
            case self::ERROR:
            case self::INCOMPLETE:
                return true;

            default:
                return false;
        }
    }

    /**
     * @return bool
     */
    public function getPhaseGroupSkipped()
    {
        switch ($this->resultCode)
        {
            case self::BLACKLISTED:
            case self::SKIPPED:
                return true;

            default:
                return false;
        }
    }

    /**
     * @return void
     */
    protected function setEndTime()
    {
        $this->endTime = microtime(true);
        $this->durationTime = $this->endTime - $this->startTime;
    }

    /**
     * @return float
     */
    public function getDuration()
    {
        return $this->durationTime;
    }

    /**
     * @return string
     */
    public function getResultString()
    {
        if (isset($this->resultStrings[$this->resultCode])) {
            return $this->resultStrings[$this->resultCode];
        }

        // either we don't have a string, or the result code itself is
        // an unexpected value
        return 'UNKNOWN';
    }
}
