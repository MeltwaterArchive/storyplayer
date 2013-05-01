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
 * @package   Stone/ApiLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\ApiLib;

use Exception;
use stdClass;
use DataSift\Stone\ExceptionsLib\Exxx_Exception;
use DataSift\Stone\LogLib\Log;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * The object to return to API users
 *
 * @category  Libraries
 * @package   Stone/ApiLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class ApiResponse extends BaseObject
{
    const MSG_ERROR = "error";
    const MSG_INFO = "info";
    const MSG_SUCCESS = "success";
    const MSG_WARNING = "warning";

    /**
     * true if we think the API call was successful
     * @var boolean
     */
    public $success = true;

    // NOTE:
    //
    // we do *not* define $messages here, because we do NOT want an
    // empty messages list converted to JSON
    //
    // Once we move to PHP 5.4, we can add a JsonSerializer to this class
    // to tidy things up.  But, until then ... $this->messages will not
    // exist until we add our first message
    //
    // *Make sure* that any code changes to this class take that into
    // account!

    // ==================================================================
    //
    // Handle success / failure
    //
    // ------------------------------------------------------------------

    /**
     * mark this API call as having failed
     */
    public function setFailed()
    {
        $this->success = false;
    }

    /**
     * mark this API call as having succeeded
     */
    public function setSucceeded()
    {
        $this->success = true;
    }

    /**
     * was the API call a success?
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    // ==================================================================
    //
    // Support for dealing with exceptions
    //
    // ------------------------------------------------------------------

    /**
     * initialise this response from an exception that the
     * ApiExceptionHandler has caught
     *
     * @param  Exception $e
     *         the exception that was caught
     * @return void
     */
    public function initFromException(Exception $e)
    {
        // exceptions are always errors
        $this->setFailed();
        $this->addMessage('error', $e->getMessage());
    }

    // ==================================================================
    //
    // Support for flash messages to show the caller
    //
    // ------------------------------------------------------------------

    /**
     * add a 'flash message' to the list
     *
     * @param string $type
     *        one of self::MSG_* constants
     * @param string $message
     *        the message to add
     */
    public function addMessage($type, $message)
    {
        $msg = new stdClass;
        $msg->alert   = $type;
        $msg->message = $message;

        if (!isset($this->messages))
        {
            $this->messages = array();
        }

        $this->messages[] = $msg;
    }

    /**
     * do we have any 'flash messages'?
     * @return boolean
     */
    public function hasMessages()
    {
        return (isset($this->messages) && count($this->messages) > 0);
    }

    /**
     * get the (possibly empty) list of 'flash messages'
     * @return array
     */
    public function getMessages()
    {
        if (!isset($this->messages))
        {
            return array();
        }

        return $this->messages;
    }

    /**
     * merge in the flash messages from another ApiResponse
     * @param ApiResponse $response
     *        the ApiResponse object to merge from
     */
    public function addMessagesFromResponse(ApiResponse $response)
    {
        $messages = $response->getMessages();
        $this->messages = array_merge($this->messages, $messages);
    }

    // ==================================================================
    //
    // Convert to output format
    //
    // ------------------------------------------------------------------

    /**
     * convert this object into something to return to the user's browser
     * or API client
     *
     * @return string
     */
    public function getOutput()
    {
        // special case - are we wanting to show the output in a
        // developer-friendly way?

        $prefix = $suffix = '';
        $jsonOptions = null;

        // @codeCoverageIgnoreStart
        if (isset($_GET['debug']) && $_GET['debug'] && defined ('JSON_PRETTY_PRINT'))
        {
            $prefix = '<pre>';
            $suffix = '</pre>';
            $jsonOptions = JSON_PRETTY_PRINT;
        }
        // @codeCoverageIgnoreEnd

        return $prefix . json_encode($this, $jsonOptions) . $suffix;
    }
}