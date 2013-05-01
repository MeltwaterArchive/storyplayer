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
 * @package   Stone/LogLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\LogLib;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * PSR-3-compatible interface to Stone's Logging API
 *
 * @category  Libraries
 * @package   Stone/LogLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

class PSR3_Logger implements LoggerInterface
{
	/**
	 * map PSR-3 log levels to ours
	 */
	private $logLevelMap = array(
		LogLevel::EMERGENCY => Log::LOG_EMERGENCY,
		LogLevel::ALERT 	=> Log::LOG_ALERT,
		LogLevel::CRITICAL  => Log::LOG_CRITICAL,
		LogLevel::ERROR     => Log::LOG_ERROR,
		LogLevel::WARNING   => Log::LOG_WARNING,
		LogLevel::NOTICE    => Log::LOG_NOTICE,
		LogLevel::INFO      => Log::LOG_INFO,
		LogLevel::DEBUG     => Log::LOG_DEBUG
	);

	/**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array())
    {
    	// by default, there's no exception that caused this message
    	$cause = null;

    	// deal with the 'exception' special case ... grrr
    	if (isset($context['exception']))
    	{
    		$cause = $context['exception'];
    		unset($context['exception']);
    	}

    	// log the message
    	Log::write(Log::LOG_EMERGENCY, $message, $context, $cause);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array())
    {
    	// by default, there's no exception that caused this message
    	$cause = null;

    	// deal with the 'exception' special case ... grrr
    	if (isset($context['exception']))
    	{
    		$cause = $context['exception'];
    		unset($context['exception']);
    	}

    	// log the message
    	Log::write(Log::LOG_ALERT, $message, $context, $cause);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array())
    {
    	// by default, there's no exception that caused this message
    	$cause = null;

    	// deal with the 'exception' special case ... grrr
    	if (isset($context['exception']))
    	{
    		$cause = $context['exception'];
    		unset($context['exception']);
    	}

    	// log the message
    	Log::write(Log::LOG_CRITICAL, $message, $context, $cause);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array())
    {
    	// by default, there's no exception that caused this message
    	$cause = null;

    	// deal with the 'exception' special case ... grrr
    	if (isset($context['exception']))
    	{
    		$cause = $context['exception'];
    		unset($context['exception']);
    	}

    	// log the message
    	Log::write(Log::LOG_ERROR, $message, $context, $cause);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array())
    {
    	// by default, there's no exception that caused this message
    	$cause = null;

    	// deal with the 'exception' special case ... grrr
    	if (isset($context['exception']))
    	{
    		$cause = $context['exception'];
    		unset($context['exception']);
    	}

    	// log the message
    	Log::write(Log::LOG_WARNING, $message, $context, $cause);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array())
    {
    	// by default, there's no exception that caused this message
    	$cause = null;

    	// deal with the 'exception' special case ... grrr
    	if (isset($context['exception']))
    	{
    		$cause = $context['exception'];
    		unset($context['exception']);
    	}

    	// log the message
    	Log::write(Log::LOG_NOTICE, $message, $context, $cause);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array())
    {
    	// by default, there's no exception that caused this message
    	$cause = null;

    	// deal with the 'exception' special case ... grrr
    	if (isset($context['exception']))
    	{
    		$cause = $context['exception'];
    		unset($context['exception']);
    	}

    	// log the message
    	Log::write(Log::LOG_INFO, $message, $context, $cause);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array())
    {
    	// by default, there's no exception that caused this message
    	$cause = null;

    	// deal with the 'exception' special case ... grrr
    	if (isset($context['exception']))
    	{
    		$cause = $context['exception'];
    		unset($context['exception']);
    	}

    	// log the message
    	Log::write(Log::LOG_DEBUG, $message, $context, $cause);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
    	// by default, there's no exception that caused this message
    	$cause = null;

    	// deal with the 'exception' special case ... grrr
    	if (isset($context['exception']))
    	{
    		$cause = $context['exception'];
    		unset($context['exception']);
    	}

    	// convert the log level to one we understand
    	if (!isset($this->logLevelMap[$level]))
    	{
    		throw new E5xx_UnknownLogLevel($level);
    	}
    	$level = $this->logLevelMap[$level];

    	// log the message
    	Log::write($level, $message, $context, $cause);
    }
}