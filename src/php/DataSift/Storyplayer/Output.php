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

namespace DataSift\Storyplayer;

use Exception;
use DataSift\Storyplayer\Phases\Phase;
use DataSift\Storyplayer\PlayerLib\Phase_Result;
use DataSift\Storyplayer\PlayerLib\PhaseGroup_Result;
use DataSift\Storyplayer\PlayerLib\Story_Result;
use DataSift\Storyplayer\OutputLib\OutputPlugin;
use DataSift\Storyplayer\Console\DefaultConsole;
use DataSift\Storyplayer\Console\Console;

use Phix_Project\ContractLib2\Contract;

/**
 * all output goes through here
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Output extends OutputPlugin
{
    /**
     * a list of the plugins that are currently active
     *
     * @var array
     */
    protected $plugins = [];

    /**
     * a list of the log messages that we have been asked to output
     *
     * this is used for producing detailed error reports when something
     * has gone badly wrong
     *
     * @var array
     */
    protected $activityLog = [];

    /**
     * constructor
     *
     * ensures we have a default console that is connected to stdout
     */
    public function __construct()
    {
        // we need a default output for the console
        $console = new DefaultConsole();
        $console->addOutputToStdout();

        $this->usePluginAsConsole($console);
    }

    /**
     * make a plugin the one that we use when writing to the user's
     * console
     *
     * @param  Console $plugin
     *         the plugin that we want
     *
     * @return void
     */
    public function usePluginAsConsole(Console $plugin)
    {
        $this->plugins['console'] = $plugin;
    }

    /**
     * set the plugin for a named output slot
     *
     * @param OutputPlugin $plugin
     *        the plugin to use in the slot
     * @param string       $slotName
     *        the name of the slot to use for this plugin
     */
    public function usePluginInSlot(OutputPlugin $plugin, $slotName)
    {
        // enforce our inputs
        Contract::RequiresValue($slotName, is_string($slotName));

        // put the plugin in the required slot
        $this->plugins[$slotName] = $plugin;
    }

    /**
     * get the array of all plugins
     *
     * @return array
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * return the active plugin in the 'console' slot
     *
     * @return Console|null
     */
    public function getActiveConsolePlugin()
    {
        // we ALWAYS have a console plugin :)
        return $this->plugins['console'];
    }

    /**
     * return the active plugin in the named slot
     *
     * @param  string $slotName
     * @return OutputPlugin|null
     */
    public function getActivePluginInSlot($slotName)
    {
        // enforce our inputs
        Contract::RequiresValue($slotName, is_string($slotName));

        // do we have a plugin in this slot?
        if (isset($this->plugins[$slotName])) {
            return $this->plugins[$slotName];
        }

        // no, we do not
        return null;
    }

    /**
     * disable 'silent' mode
     *
     * NOTE: it is up to each plugin in turn whether or not to support
     * 'silent' mode at all
     *
     * @return void
     */
    public function resetSilentMode()
    {
        foreach ($this->plugins as $plugin)
        {
            $plugin->resetSilentMode();
        }
    }

    /**
     * switches 'silent' mode on
     *
     * in 'silent' mode, we do not write log activity to the output writer
     * at all.  HOWEVER, the plugin may still add the log activity to any
     * internal cache it has (can be useful for error reports etc)
     *
     * @return void
     */
    public function setSilentMode()
    {
        foreach ($this->plugins as $plugin)
        {
            $plugin->setSilentMode();
        }
    }

    /**
     * disables any colour output
     *
     * @return void
     */
    public function disableColourSupport()
    {
        foreach ($this->plugins as $plugin)
        {
            $plugin->disableColourSupport();
        }
    }

    /**
     * forces switching on colour support
     *
     * @return void
     */
    public function enforceColourSupport()
    {
        foreach ($this->plugins as $plugin)
        {
            $plugin->enforceColourSupport();
        }
    }

    /**
     * asks each active plugin to switch on colour support if possible
     *
     * a plugin may still choose to not output colour. one example of this
     * are consoles. they're happy to output colour if talking to a terminal,
     * but choose not to output colour if they're only writing to log files
     * or to a pipe into another UNIX process.
     *
     * @return void
     */
    public function enableColourSupport()
    {
        foreach ($this->plugins as $plugin)
        {
            $plugin->enableColourSupport();
        }
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
        // enforce our inputs
        Contract::RequiresValue($version,   is_string($version));
        Contract::RequiresValue($url,       is_string($url));
        Contract::RequiresValue($copyright, is_string($copyright));
        Contract::RequiresValue($license,   is_string($license));

        // call all of our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->startStoryplayer($version, $url, $copyright, $license);
        }
    }

    /**
     * called when Storyplayer exits
     *
     * @param  float $duration
     *         how long did storyplayer take to run (in seconds)?
     * @return void
     */
    public function endStoryplayer($duration)
    {
        foreach ($this->plugins as $plugin)
        {
            $plugin->endStoryplayer($duration);
        }
    }

    /**
     * called when we start playing a new PhaseGroup
     *
     * @param  string $activity
     *         what are we doing? (e.g. 'creating', 'running')
     * @param  string $name
     *         the name of the phase group
     * @return void
     */
    public function startPhaseGroup($activity, $name)
    {
        // ensure our inputs!
        Contract::RequiresValue($activity, is_string($activity));
        Contract::RequiresValue($name,     is_string($name));

        // call our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->startPhaseGroup($activity, $name);
        }
    }

    /**
     * called when we have finished playing a PhaseGroup
     *
     * NOTE: we cannot use a type-hint for $result here. we may pass in
     * a class that inherits from PhaseGroup_Result, and (annoyingly)
     * this isn't allowed if we use a type-hint (grrrr)
     *
     * @param  PhaseGroup_Result $result
     * @return void
     */
    public function endPhaseGroup($result)
    {
        // enforce our input type
        Contract::Requires($result instanceof PhaseGroup_Result);

        // call our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->endPhaseGroup($result);
        }
    }

    /**
     * called when a story starts a new phase
     *
     * $param  Phase $phase
     *         the phase that we are executing
     * @return void
     */
    public function startPhase($phase)
    {
        // enforce our input type
        Contract::Requires($phase instanceof Phase);

        foreach ($this->plugins as $plugin)
        {
            $plugin->startPhase($phase);
        }
    }

    /**
     * called when a story ends a phase
     *
     * @param  Phase $phase
     *         the phase that has finished
     * @param  Phase_Result $phaseResult
     *         the result of running $phase
     * @return void
     */
    public function endPhase($phase, $phaseResult)
    {
        // enforce our input type
        Contract::Requires($phase instanceof Phase);
        Contract::Requires($phaseResult instanceof Phase_Result);

        // inject the captured activity into the phase
        $phaseResult->activityLog = $this->activityLog;
        $this->activityLog=[];

        // pass the phase on
        foreach ($this->plugins as $plugin)
        {
            $plugin->endPhase($phase, $phaseResult);
        }
    }

    /**
     * called when a story logs an action
     *
     * @param string $msg
     *        the message to write to the logs / console
     * @param array|null $codeLine
     *        information about the line of code that is executing
     * @return void
     */
    public function logPhaseActivity($msg, $codeLine = null)
    {
        // enforce our input type
        Contract::RequiresValue($msg, is_string($msg));
        if ($codeLine) {
            Contract::RequiresValue($codeLine, is_array($codeLine));
        }

        // keep track of what was attempted, in case we need to show
        // the user what was attempted
        $this->activityLog[] = [
            'ts'       => time(),
            'text'     => $msg,
            'codeLine' => $codeLine,
            'isOutput' => false,
        ];

        // call all of our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->logPhaseActivity($msg, $codeLine);
        }
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
        // enforce our input type
        Contract::RequiresValue($msg, is_string($msg));

        // keep track of what was attempted, in case we need to show
        // the user what was attempted
        $this->activityLog[] = [
            'ts'       => time(),
            'text'     => $msg,
            'codeLine' => null,
            'isOutput' => true,
        ];

        // call all of our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->logPhaseSubprocessOutput($msg);
        }
    }

    /**
     * called when a story logs an error
     *
     * @param string $phaseName
     *        the name of the phase where the error occurred
     * @param string $msg
     *        an error message to send to console|logfile
     * @return void
     */
    public function logPhaseError($phaseName, $msg)
    {
        // enforce our inputs
        Contract::RequiresValue($phaseName, is_string($phaseName));
        Contract::RequiresValue($msg,       is_string($msg));

        // keep track of what was attempted, in case we need to show
        // the user what was attempted
        $this->activityLog[] = [
            'ts'       => time(),
            'text'     => $msg,
            'codeLine' => null,
        ];

        // call all of our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->logPhaseError($phaseName, $msg);
        }
    }

    /**
     * called when a story is skipped
     *
     * @param string $phaseName
     *        the name of the phase where the error occurred
     * @param string $msg
     *        an informational message to send to console|logfile
     * @return void
     */
    public function logPhaseSkipped($phaseName, $msg)
    {
        // enforce our inputs
        Contract::RequiresValue($phaseName, is_string($phaseName));
        Contract::RequiresValue($msg,       is_string($msg));

        // keep track of what was attempted, in case we need to show
        // the user what was attempted
        $this->activityLog[] = [
            'ts'       => time(),
            'text'     => $msg,
            'codeLine' => null,
        ];

        // call all of our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->logPhaseSkipped($phaseName, $msg);
        }
    }

    /**
     * called when we want to record which line of code in a phase is
     * currently executing
     *
     * @param  array $codeLine
     *         details about the line of code that is executing
     * @return void
     */
    public function logPhaseCodeLine($codeLine)
    {
        // enforce our inputs
        Contract::RequiresValue($codeLine, is_array($codeLine));

        // pass it on to all of our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->logPhaseCodeLine($codeLine);
        }
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
        // enforce our inputs
        Contract::RequiresValue($msg, is_string($msg));

        // pass it on to our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->logCliError($msg);
        }
    }

    /**
     * called when the outer CLI shell encounters a fatal error
     *
     * @param  string $msg
     *         the error message to show the user
     * @param  \Exception $e
     *         the exception that caused the error
     * @return void
     */
    public function logCliErrorWithException($msg, $e)
    {
        // enforce our inputs
        Contract::RequiresValue($msg, is_string($msg));
        Contract::RequiresValue($e, $e instanceof Exception);

        // pass this on to our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->logCliErrorWithException($msg, $e);
        }
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
        // enforce our inputs
        Contract::RequiresValue($msg, is_string($msg));

        // pass this on to our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->logCliWarning($msg);
        }
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
        // enforce our inputs
        Contract::RequiresValue($msg, is_string($msg));

        // pass this on to our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->logCliInfo($msg);
        }
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
        // enforce our inputs
        Contract::RequiresValue($name, is_string($name));
        // $var can be anything, so there is no contract to enforce

        // pass this on to our plugins
        foreach ($this->plugins as $plugin)
        {
            $plugin->logVardump($name, $var);
        }
    }
}