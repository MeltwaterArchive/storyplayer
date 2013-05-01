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
 * @package   Stone/ExceptionsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\ExceptionsLib;

/**
 * A simple wrapper to catch legacy PHP errors and turn them into exceptions
 *
 * @category  Libraries
 * @package   Stone/ExceptionsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class LegacyErrorCatcher
{
    /**
     * The exception that we need to throw when we catch a legacy error
     *
     * @var Exception
     */
    protected $exceptionToThrow = null;

    /**
     * keep track of whether exceptions should be thrown if a warning
     * occurs - default is 'false'
     *
     * @var boolean
     */
    protected $warningsAreFatal = false;

    /**
     * Call a method, function, or closure, and return the results. Any legacy
     * (ie non-exception) errors that happen during the call are converted into
     * an exception
     *
     * @param callback $callback
     * @param array $params
     * @return mixed the return value from $callback
     */
    public function callUserFuncArray($callback, $params = array())
    {
        // start with a clean slate
        $this->exceptionToThrow = null;

        // install ourselves as the legacy error handler
        set_error_handler(array($this, '__handleLegacyError'));

        // call the user's function or method
        //
        // NOTE: we do NOT do this inside a try/catch block because if
        // the user's code throws an exception, we don't want to mess
        // up the stack trace at all
        //
        // we can do better if a future PHP gains a rethrow() feature
        $return = call_user_func_array($callback, $params);

        // clean up after ourselves, and remove ourselves from the stack
        // of legacy error handlers
        //
        // this, btw, is the reason why we can only throw the one exception,
        // and why we have to stash the exception inside this object
        // instead of just throwing it out
        restore_error_handler();

        // do we have a pending exception?
        if ($this->exceptionToThrow !== null)
        {
            // yes - let's remove it from the pending state
            $e = $this->exceptionToThrow;
            $this->exceptionToThrow = null;

            // all done - throw that bugger
            throw $e;
        }

        // if we get here, then everything ran without a detectable
        // hitch, and we need to pass the wrapped callable's return value
        // back to the caller
        return $return;
    }

    /**
     * Called when PHP detects a legacy error.  Do not call this directly.
     *
     * What do we want to do about it?
     *
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param array $errcontext
     */
    public function __handleLegacyError($errno, $errstr, $errfile, $errline = 0, $errcontext = null)
    {
        // we may get multiple catchable errors when handling a piece of
        // code - but we can only safely throw one exception
        //
        // it's a tricky one to handle, but for now I've decided that,
        // if it occurs, we'll throw the first exception that we build

        // do we already have an exception that is ready to throw?
        if ($this->exceptionToThrow)
        {
            // yes we do - do nothing
            return false;
        }

        // we don't have an exception yet built
        //
        // decide whether we should build one or not
        switch($errno)
        {
            // we do NOT include E_WARNING here, as this code is used
            // when PHP detects an attempt to call an undefined function
            //
            // yes, it's confusing - that's why it's called legacy
            // error handling :(

            case E_NOTICE;
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
            case E_USER_NOTICE:
            case E_USER_WARNING:
                if (!$this->warningsAreFatal)
                {
                    // no need to throw an exception, as these are warnings
                    // rather than executing-halting errors
                    return false;
                }
                // if we get here, fall through to the default behaviour

            default:
                // anything else needs turning into an exception
                $this->exceptionToThrow = new E5xx_EngineError($errstr, $errno);
                return true;
        }
    }

    /**
     * are we treating legacy warnings as fatal errors?
     *
     * @return boolean
     */
    public function getWarningsAreFatal()
    {
        return $this->warningsAreFatal;
    }

    /**
     * decide if we are treating legacy warnings as fatal errors
     *
     * @param boolean $fatal
     *        set to true (the default) if legacy warnings should be
     *        turned into exceptions
     */
    public function setWarningsAreFatal($fatal = true)
    {
        $this->warningsAreFatal = $fatal;
    }

    /**
     * do we have an exception that is waiting to be thrown?
     *
     * @return boolean
     *         TRUE if we do
     *         FALSE if we do not
     */
    public function hasPendingException()
    {
        return isset($this->exceptionToThrow);
    }

    /**
     * get the exception that is waiting to be thrown
     *
     * @return DataSift\Stone\ExceptionsLib\E5xx_EngineError
     */
    public function getPendingException()
    {
        return $this->exceptionToThrow;
    }
}