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

use Exception;

/**
 * A simple class for writing log messages to stdout
 *
 * @category  Libraries
 * @package   Stone/LogLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class StdOutWriter extends LogWriter
{
    /**
     * our stdout file descriptor
     * @var resource
     */
    protected $fp;

    /**
     * initialise this log writer
     *
     * @param  string $processName
     *         the name of the process writing log messages
     * @param  int $pid
     *         the process ID of the process writing log messages
     * @return void
     */
    public function init($processName, $pid)
    {
        parent::init($processName, $pid);
        $this->openStdout();
    }

    /**
     * open stdout ready for writing
     * @return void
     */
    protected function openStdout()
    {
        $this->fp = fopen("php://stdout", "w");
        if (!$this->fp)
        {
            throw new E5xx_LogWriteFailure("Unable to open stdout for output");
        }
    }

    /**
     * write a log message
     *
     * @param  string $logLevel
     *         the severity of the log message
     *         (one of $this->$prefixes)
     * @param  string $message
     *         the log message to write
     * @param  Exception $cause
     *         the exception that caused the log message
     * @return void
     */
    public function write($logLevel, $message, $cause = null)
    {
        $now = date('Y-m-d H:i:s', time());

        fwrite($this->fp, '[' . $now . '] [' . $this->processName . ':' . $this->pid . '] ' . $this->prefixes[$logLevel] . $message . "\n");
        fflush($this->fp);
    }
}