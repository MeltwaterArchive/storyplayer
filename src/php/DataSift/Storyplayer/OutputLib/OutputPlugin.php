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
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\OutputLib;

use DataSift\Storyplayer\PlayerLib\Story_Result;
use DataSift\Storyplayer\PlayerLib\PhaseGroup_Result;

/**
 * the base class for output plugins
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
abstract class OutputPlugin
{
    protected $writer = null;

    const COLOUR_MODE_OFF  = 1;
    const COLOUR_MODE_ON   = 2;
    const COLOUR_MODE_AUTO = 3;

    public function __construct()
    {
        $this->writer = new OutputWriter(self::COLOUR_MODE_AUTO);
    }

    // ==================================================================
    //
    // Support for outputting to various places
    //
    // ------------------------------------------------------------------

    /**
     * @return void
     */
    public function addOutputToStdout()
    {
        $this->writer->addOutputToStdout();
    }

    /**
     * @return void
     */
    public function addOutputToStderr()
    {
        $this->writer->addOutputToStderr();
    }

    /**
     * @param string $filename
     * @return void
     */
    public function addOutputToFile($filename)
    {
        // make sure $filename isn't a reserved name
        switch($filename)
        {
            case 'stdout':
            case 'stderr':
            case 'null':
                throw new E4xx_OutputFilenameIsAReservedName($filename);
        }

        $this->writer->addOutputToFile($filename);
    }

    /**
     * @param  string $output
     * @param  array|null $style
     * @return void
     */
    public function write($output, $style = null)
    {
        $this->writer->write($output, $style);
    }

    /**
     * @param  float $duration
     * @param  array|null $style
     * @return void
     */
    public function writeDuration($duration, $style = null)
    {
        // break down the duration into reportable units
        $hours = $mins = 0;

        // this gives us the ability to report on durations of
        // less than one second
        $secs = round($duration, 2);
        if ($duration > 59) {
            $mins  = (int)($duration / 60);
            // when things take a minute or more, we don't care
            // about sub-second accuracy any more
            $secs  = $duration % 60;
        }
        else {
        }
        if ($duration > 3600) {
            $hours = (int)($duration / 3600);
        }

        // turn the breakdown into a printable string
        $output = '';
        if ($hours) {
            if ($hours > 1) {
                $output = "{$hours} hours";
            }
            else {
                $output = "1 hour";
            }
        }
        if ($mins) {
            $minsUnit = 'mins';
            if ($mins == 1) {
                $minsUnit = 'min';
            }
            if ($hours && $secs) {
                $output .= ", {$mins} {$minsUnit}";
            }
            else if ($hours) {
                $output .= " and {$mins} {$minsUnit}";
            }
            else {
                $output = "{$mins} {$minsUnit}";
            }
        }

        $secsUnit = 'secs';
        if ($secs == 1) {
            $secsUnit = 'sec';
        }
        if (($hours || $mins) && $secs) {
            $output .= " and {$secs} {$secsUnit}";
        }
        else {
            $output = "{$secs} {$secsUnit}";
        }

        // are we using our default style?
        if (!$style) {
            $style = $this->writer->durationStyle;
        }

        // send the string out to the user
        $this->writer->write($output, $style);
    }

    /**
     * @return OutputWriter
     */
    public function getWriter()
    {
        return $this->writer;
    }

    // ==================================================================
    //
    // Colour support
    //
    // ------------------------------------------------------------------

    /**
     * @return void
     */
    public function disableColourSupport()
    {
        $this->writer->setColourMode(self::COLOUR_MODE_OFF);
    }

    /**
     * @return void
     */
    public function enableColourSupport()
    {
        $this->writer->setColourMode(self::COLOUR_MODE_AUTO);
    }

    /**
     * @return void
     */
    public function enforceColourSupport()
    {
        $this->writer->setColourMode(self::COLOUR_MODE_ON);
    }

    // ==================================================================
    //
    // These are the methods that Storyplayer will call as things
    // happen ...
    //
    // ------------------------------------------------------------------

    /**
     * @param string $version
     * @param string $url
     * @param string $copyright
     * @param string $license
     * @return void
     */
    abstract public function startStoryplayer($version, $url, $copyright, $license);

    /**
     * @return void
     */
    abstract public function endStoryplayer($duration);

    /**
     * @return void
     */
    abstract public function resetSilentMode();

    /**
     * @return void
     */
    abstract public function setSilentMode();

    /**
     * @return void
     */
    abstract public function startPhaseGroup($activity, $name);

    /**
     * @return void
     */
    abstract public function endPhaseGroup($result);

    /**
     * @return void
     */
    abstract public function startPhase($phase);

    /**
     * @return void
     */
    abstract public function endPhase($phase, $phaseResult);

    /**
     * @param string $msg
     * @return void
     */
    abstract public function logPhaseActivity($msg);

    /**
     * called when a story logs the (possibly partial) output from
     * running a subprocess
     *
     * @param  string $msg the output to log
     * @return void
     */
    abstract public function logPhaseSubprocessOutput($msg);

    /**
     * @param string $phaseName
     * @param string $msg
     * @return void
     */
    abstract public function logPhaseError($phaseName, $msg);

    /**
     * @param string $phaseName
     * @param string $msg
     * @return void
     */
    abstract public function logPhaseSkipped($phaseName, $msg);

    /**
     * @param string $msg
     *
     * @return void
     */
    abstract public function logCliWarning($msg);

    /**
     * @param string $msg
     *
     * @return void
     */
    abstract public function logCliError($msg);

    /**
     *
     * @param  string $msg
     * @param  Exception $e
     * @return void
     */
    abstract public function logCliErrorWithException($msg, $e);

    /**
     * @param string $msg
     *
     * @return void
     */
    abstract public function logCliInfo($msg);

    /**
     * @param string $name
     *
     * @return void
     */
    abstract public function logVardump($name, $var);
}
