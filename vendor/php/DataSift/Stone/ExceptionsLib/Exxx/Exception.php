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

use Exception;

/**
 * Base class for all exceptions thrown by Stone
 *
 * @category  Libraries
 * @package   Stone/ExceptionsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class Exxx_Exception extends Exception
{
    /**
     * the developer-friendly message
     *
     * @var string
     */
    protected $devMessage;

    /**
     * constructor
     *
     * @param int $code
     *        the error code to report back (e.g. a HTTP status code)
     * @param string $publicMessage
     *        the message to show the public (e.g. in an 'error' field in an API call response)
     * @param string $devMessage
     *        the message to show your fellow developers (e.g. in a log file)
     * @param Exception $cause
     *        the original exception that caused this exception
     */
    public function __construct($code, $publicMessage, $devMessage, $cause = null)
    {
        parent::__construct($publicMessage, $code, $cause);
        $this->setDevMessage($devMessage);
    }

    /**
     * what is the developer-friendly message?
     *
     * @return string
     */
    public function getDevMessage()
    {
        return $this->devMessage;
    }

    /**
     * set the developer-friendly message
     *
     * @param  string         $newDevMessage
     * @return Exxx_Exception $this
     */
    protected function setDevMessage($newDevMessage)
    {
        $this->devMessage = $newDevMessage;
        return $this;
    }

    /**
     * convert this object into a printable string
     * @return string
     */
    public function __toString()
    {
        $msg = $this->getMessage() . ' in ' . $this->getFile() . ' at line ' . $this->getLine();
        return $msg;
    }
}