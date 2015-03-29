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
 * @author    Nicola Asuni <nicola.asuni@datasift.com>
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Reports;

/**
 * Plugin for JUnit
 *
 * @category  Libraries
 * @package   Storyplayer/Reports
 * @author    Nicola Asuni <nicola.asuni@datasift.com>
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class JUnitReport extends Report
{
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

    /**
     * JUnit testsuite maps to the whole story
     * @var array
     */
    protected $testsuite = array();

    /**
     * JUnit testcase maps to the phase groups
     * @var array
     */
    protected $testcase = array();


    public function resetSilentMode()
    {
        $this->silentActivity = false;
    }

    public function setSilentMode()
    {
        $this->silentActivity = true;
    }

    public function __construct($args)
    {
        $this->filename = $args['filename'];
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
        $this->testsuite = array(
            'id'        => 0, // testsuite ID
            'name'      => 'StoryPlayer', // testsuite name
            'tests'     => 0, // the total number of tests in the suite, required
            'disabled'  => 0, // the total number of disabled tests in the suite
            'errors'    => 0, // the total number of tests in the suite that errored
            'failures'  => 0, // the total number of tests in the suite that failed
            'skipped'   => 0, // the total number of skipped tests
            'timestamp' => gmdate('Y-m-d\TH:i:s'), // when the test was executed in ISO 8601 format (2014-01-21T16:17:18)
            'testcase'  => array(), // test cases data
        );
    }

    /**
     * called when storyplayer ends
     *
     * @param  float $duration duration in seconds
     * @return void
     */
    public function endStoryplayer($duration)
    {
        // generate the XML
        $junitxml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $junitxml .= '<testsuite'
            .' id="'.$this->testsuite['id'].'"'
            .' name="'.$this->testsuite['name'].'"'
            .' tests="'.$this->testsuite['tests'].'"'
            .' disabled="'.$this->testsuite['disabled'].'"'
            .' errors="'.$this->testsuite['errors'].'"'
            .' failures="'.$this->testsuite['failures'].'"'
            .' skipped="'.$this->testsuite['skipped'].'"'
            .' time="'.round($duration, 6).'"'
            .' timestamp="'.$this->testsuite['timestamp'].'">'.PHP_EOL;

        foreach ($this->testsuite['testcase'] as $testcase) {
            $junitxml .= "\t".'<testcase'
                .' name="'.$testcase['name'].'"'
                .' assertions="'.$testcase['assertions'].'"'
                .' classname="'.$testcase['classname'].'"'
                .' status="'.$testcase['status'].'"'
                .' time="'.round($testcase['time'], 6).'">'.PHP_EOL;

            if ($testcase['skipped']) {
                $junitxml .= "\t\t".'<skipped/>'.PHP_EOL;
            }
            if ($testcase['failure']) {
                $junitxml .= "\t\t".'<failure/>'.PHP_EOL;
            }
            if ($testcase['error']) {
                $junitxml .= "\t\t".'<error/>'.PHP_EOL;
            }

            $junitxml .= "\t".'</testcase>'.PHP_EOL;
        }

        $junitxml .= '</testsuite>'.PHP_EOL;

        file_put_contents($this->filename, $junitxml);
    }

    /**
     * called when we start a new set of phases
     *
     * @param  string $name
     * @return void
     */
    public function startPhaseGroup($activity, $name)
    {
        // encode name for XML
        $name = htmlspecialchars($name, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $this->testcase = array(
            'name'       => $name,
            'assertions' => 0,     // number of assertions in the test case
            'classname'  => $name, // full class name for the class the test method is in
            'status'     => '',    // result status string
            'time'       => 0,     // time taken (in seconds) to execute the test
            'skipped'    => false,
            'failure'    => false,
            'error'      => false,
        );
    }

    /**
     * called when we end a set of phases
     *
     * @param  PhaseGroup_Result $result
     * @return void
     */
    public function endPhaseGroup($result)
    {
        $this->testcase['time'] = $result->getDuration();
        $this->testcase['status'] = htmlspecialchars($result->getResultString(), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $this->testcase['skipped'] = $result->getPhaseGroupSkipped();
        $this->testcase['failure'] = ($result->resultCode === $result::FAIL);
        $this->testcase['error'] = ($result->resultCode === $result::ERROR);
        // parent
        $this->testsuite['testcase'][] = $this->testcase;
        $this->testsuite['tests'] += 1;
        $this->testsuite['disabled'] += intval($result->resultCode === $result::BLACKLISTED);
        $this->testsuite['errors'] += intval($this->testcase['error']);
        $this->testsuite['failures'] += intval($this->testcase['failure']);
        $this->testsuite['skipped'] += intval($result->resultCode === $result::SKIPPED);
    }

    /**
     * called when a story starts a new phase
     *
     * @return void
     */
    public function startPhase($phase)
    {
        $this->testcase['assertions'] += 1;
    }

    /**
     * called when a story ends a phase
     *
     * @return void
     */
    public function endPhase($phase, $phaseResult)
    {
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
