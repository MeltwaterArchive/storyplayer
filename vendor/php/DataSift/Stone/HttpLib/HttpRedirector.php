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
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\HttpLib;

use DataSift\Stone\ApiLib\ApiHelpers;

/**
 * Very simple support for redirecting the user's browser to a different
 * location. For use inside controllers.
 *
 * @category  Libraries
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class HttpRedirector
{
    /**
     * Issue a 302 redirect, and then quit
     *
     * @codeCoverageIgnore
     * @param  string $url
     *         the URL to redirect to
     * @param  array $params
     *         any query string params to tack onto the URL
     * @return void
     */
    static public function send_302($url, $params = array())
    {
        header('HTTP/1.0 302 Temporarily moved');
        header('Location: ' . $url . self::convertParamsToQueryString($params));
        exit(0);
    }

    /**
     * Issue a 303 redirect, and then quit
     *
     * @codeCoverageIgnore
     * @param  string $url
     *         the URL to redirect to
     * @param  array $params
     *         any query string params to tack onto the URL
     * @return void
     */
    static public function send_303($url, $params)
    {
        header('HTTP/1.0 303 See Other');
        header('Location: ' . $url . self::convertParamsToQueryString($params));
        exit(0);
    }

    /**
     * Convert a set of params into the query string for a URL
     *
     * The parameters can either be an array, or an object.
     *
     * @param  mixed $params the params to convert
     * @return string
     */
    static protected function convertParamsToQueryString($params)
    {
        // just in case we have an object instead of an array
        $paramsToConvert = ApiHelpers::normaliseParams($params);

        // do we have any parameters to convert?
        if (count($paramsToConvert) == 0)
        {
            // no, we do not
            return '';
        }

        // encode the parameters individually
        array_walk($paramsToConvert, array(__CLASS__, 'encodeParam'));

        // flatten the parameters
        $pairs = array();
        foreach ($paramsToConvert as $key => $value)
        {
            $pairs[] = $key . '=' . $value;
        }
        return '?' . join('&', $pairs);
    }

    /**
     * Callback for array_walk() call
     *
     * @param  string &$param the param to be encoded
     * @return void
     */
    static public function encodeParam(&$param)
    {
        $param = urlencode($param);
    }
}