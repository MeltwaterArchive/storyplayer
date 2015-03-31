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
 * @package   Storyplayer/Reports
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Reports;

use DataSift\Storyplayer\OutputLib\CodeFormatter;
use DataSift\Storyplayer\Phases\Phase;
use DataSift\Storyplayer\PlayerLib\Phase_Result;
use DataSift\Storyplayer\PlayerLib\PhaseGroup_Result;
use DataSift\Storyplayer\PlayerLib\Story_Result;
use DataSift\Storyplayer\PlayerLib\Story;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * writes a PASS / FAIL file out to disk
 *
 * @category  Libraries
 * @package   Storyplayer/Reports
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class JsonReport extends Report
{
    protected $currentPhase;
    protected $phaseNumber = 0;
    protected $phaseMessages = array();

    /**
     * a list of the results we have received from stories
     * @var array
     */
    protected $storyResults = [];

    /**
     * are we running totally silently?
     * @var boolean
     */
    protected $silentActivity = false;

    /**
     * where will we write our report to?
     * @var string
     */
    protected $filename = null;

    protected $results = null;

    protected $currentPhaseGroup;

    public function resetSilentMode()
    {
        $this->silentActivity = false;
    }

    public function setSilentMode()
    {
        $this->silentActivity = true;
    }

    public function __construct($args) {
        if (isset($args['filename'])) {
            $this->filename = $args['filename'];
        }

        $this->results = new BaseObject;
    }

    /**
     * called when storyplayer starts
     *
     * @param string $version
     * @param string $url
     * @param string $copyright
     * @param string $license
     * @return void
     */
    public function startStoryplayer($version, $url, $copyright, $license)
    {
        $engine = new BaseObject;
        $engine->name = "Storyplayer";
        $engine->version = $version;
        $engine->url = $url;
        $engine->copyright = $copyright;
        $engine->license = $license;
        $this->results->engine = $engine;

        $testrun = new BaseObject;
        $testrun->startTime = microtime(true);
        $testrun->endTime = 0;
        $testrun->duration = 0;
        $testrun->results = [];
        $this->results->testrun = $testrun;
    }

    public function endStoryplayer($duration)
    {
        $this->results->testrun->endTime = microtime(true);
        $this->results->testrun->duration = $this->results->testrun->endTime - $this->results->testrun->startTime;
        file_put_contents($this->filename, json_encode($this->results));
    }

    /**
     * called when we start a new set of phases
     *
     * @param  string $name
     * @return void
     */
    public function startPhaseGroup($activity, $name)
    {
        $o = new BaseObject;
        $o->name = $activity . ' ' . $name;
        $o->details = [];
        $this->currentPhaseGroup = $o;
    }

    /**
     * called when we end a set of phases
     *
     * @param  PhaseGroup_Result $result
     * @return void
     */
    public function endPhaseGroup($result)
    {
        $o = $this->currentPhaseGroup;
        $o->result = $result->getResultString();
        $o->duration = $result->getDuration();
        if (isset($result->filename)) {
            $o->filename = $result->filename;
            $o->shortFilename = basename($result->filename);
        }

        $this->results->testrun->results[] = clone $o;
    }

    /**
     * called when a story starts a new phase
     *
     * @return void
     */
    public function startPhase($phase)
    {
    }

    /**
     * called when a story ends a phase
     *
     * @return void
     */
    public function endPhase($phase, $phaseResult)
    {
        // we're only interested in telling the user about the
        // phases of a story
        if ($phase->getPhaseType() !== Phase::STORY_PHASE) {
            return;
        }

        // where to store the details
        $o = $this->currentPhaseGroup;

        $r = new BaseObject;
        $r->name = $phase->getPhaseName();
        $r->result = $phaseResult->getPhaseResultString();

        $o->details[$phase->getPhaseSequenceNo()] = $r;
    }

    /**
     * called when a story logs an action
     *
     * @param string $msg
     * @return void
     */
    public function logPhaseActivity($msg, $codeLine = null)
    {
    }

    /**
     * called when a story logs the (possibly partial) output from
     * running a subprocess
     *
     * @param  string $msg the output to log
     * @return void
     */
    public function logPhaseSubprocessOutput($msg)
    {
    }

    /**
     * called when a story logs an error
     *
     * @param string $phaseName
     * @param string $msg
     * @return void
     */
    public function logPhaseError($phaseName, $msg)
    {
    }

    /**
     * called when a story is skipped
     *
     * @param string $phaseName
     * @param string $msg
     * @return void
     */
    public function logPhaseSkipped($phaseName, $msg)
    {
    }

    public function logPhaseCodeLine($codeLine)
    {
        // this is a no-op for us
    }

    /**
     * called when the outer CLI shell encounters a fatal error
     *
     * @param  string $msg
     *         the error message to show the user
     *
     * @return void
     */
    public function logCliError($msg)
    {
    }

    /**
     *
     * @param  string $msg
     * @param  Exception $e
     * @return void
     */
    public function logCliErrorWithException($msg, $e)
    {
    }

    /**
     * called when the outer CLI shell needs to publish a warning
     *
     * @param  string $msg
     *         the warning message to show the user
     *
     * @return void
     */
    public function logCliWarning($msg)
    {
    }

    /**
     * called when the outer CLI shell needs to tell the user something
     *
     * @param  string $msg
     *         the message to show the user
     *
     * @return void
     */
    public function logCliInfo($msg)
    {
    }

    /**
     * an alternative to using PHP's built-in var_dump()
     *
     * @param  string $name
     *         a human-readable name to describe $var
     *
     * @param  mixed $var
     *         the variable to dump
     *
     * @return void
     */
    public function logVardump($name, $var)
    {
        // this is a no-op for us
    }
}
