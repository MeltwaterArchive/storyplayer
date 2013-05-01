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
 * Exception for when LegacyErrorCatcher caught a PHP4-style runtime
 * error, and needs to convert it into an exception
 *
 * @category  Libraries
 * @package   Stone/ExceptionsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class E5xx_EngineError extends Exxx_Exception
{
    /**
     * a simple table to translate PHP error constants into a
     * human-readable string
     *
     * this really should be built into the PHP engine itself, but
     * AFAIK it currently is not
     *
     * @var array
     */
	static protected $engineErrors = array(
        E_WARNING => "E_WARNING: ",
        E_NOTICE => "E_NOTICE: ",
        E_USER_ERROR => "E_USER_ERROR: ",
        E_USER_NOTICE => "E_USER_NOTICE: ",
        E_USER_WARNING => "E_USER_WARNING: ",
        E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR: ",
        E_DEPRECATED => "E_DEPRECATED: ",
        E_USER_DEPRECATED => "E_USER_DEPRECATED: "
	);

    /**
     * the PHP engine error that was passed to us
     *
     * @var integer
     */
    protected $engineError = 0;

    /**
     * constructor
     *
     * @param string $errstr
     *        the PHP error message passed to the error handler
     * @param int $errno
     *        the PHP error number passed to the error handler
     */
    public function __construct($errstr, $errno)
    {
    	// is this an error we know about?
    	if (isset(self::$engineErrors[$errno])) {
    		// yes, it is
    		$msg = self::$engineErrors[$errno] . $errstr;
    	}
    	else {
    		// we do not know what this is
    		$msg = "PHP engine error #{$errno}: {$errstr}";
    	}

        // remember the PHP engine error code we received
        $this->engineError = $errno;

        // call our parent's constructor
        parent::__construct(500, $msg, $msg);
    }

    /**
     * get the error code for this PHP engine error
     *
     * @return int
     */
    public function getEngineError()
    {
        return $this->engineError;
    }
}