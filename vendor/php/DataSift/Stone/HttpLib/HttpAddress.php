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

use Exception;

/**
 * Represents a given URL
 *
 * @category  Libraries
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

class HttpAddress
{
    /**
     * The URL as a string, in the form:
     *
     * <scheme>://<hostname>[:<port>]/[<queryPath>][#fragment][?<params>]
     *
     * @var string
     */
    protected $rawAddress;

    /**
     * What protocol are we using? Normally 'http' or 'https'.
     * @var type
     */
    public $scheme = null;

    /**
     * The host we are connecting to
     * @var string
     */

    public $hostname = null;

    /**
     * The port we are connecting to.
     *
     * NOTE: PHP's parse_url() will not provide a port number if there isn't
     *       one in the rawAddress; we need to work this out for ourselves in
     *       that situation
     *
     * @var type
     */
    public $port = null;

    /**
     * The username we need to connect as (HTTP Basic Auth)
     * @var string
     */

    public $user = null;

    /**
     * The password to use when we connect (HTTP Basic Auth)
     * @var string
     */
    public $password = null;

    /**
     * The PATH section of the URL
     * @var string
     */
    public $path = null;

    /**
     * The query string section of the URL (everything after '?').
     * @var string
     */
    public $queryString = null;

    /**
     * The fragment section of the URL (everything after '#')
     * @var string
     */
    public $fragment = null;

    /**
     * Constructor.
     * @param string $addressString the URL we are representing
     */
    public function __construct($addressString)
    {
        $this->setAddress($addressString);
    }

    /**
     * Set the URL that we are representing.
     *
     * This is called by the constructor; you only need to call it yourself
     * if you're changing the URL for some reason.
     *
     * @param type $addressString
     */
    public function setAddress($addressString)
    {
        $parts = parse_url($addressString);
        if (!is_array($parts))
        {
            throw new Exception('unable to parse URL');
        }

        // okay, what do we have?
        static $components = array (
            'scheme'      => 'scheme',
            'hostname'    => 'host',
            'port'        => 'port',
            'user'        => 'user',
            'password'    => 'pass',
            'path'        => 'path',
            'queryString' => 'query',
            'fragment'    => 'fragment'
        );

        foreach ($components as $attribute => $index)
        {
            if (isset($parts[$index]))
            {
                $this->$attribute = $parts[$index];
            }
        }

        // fill in the blanks
        $method = 'postProcessSetAddress' . ucfirst($this->scheme);
        if (method_exists($this, $method))
        {
            call_user_func(array($this, $method));
        }

        // make __toString() very easy to do
        $this->rawAddress = $addressString;

        // at this point, we're all set to go :)
    }

    /**
     * fill in the blanks when we have a HTTP address
     */
    private function postProcessSetAddressHttp()
    {
        if ($this->port == null)
        {
            $this->port = 80;
        }
        if ($this->path == null)
        {
            $this->path = '/';
        }
    }

    /**
     * fill in the blanks when we have a HTTPS address
     */
    private function postProcessSetAddressHttps()
    {
        if ($this->port == null)
        {
            $this->port = 443;
        }
        if ($this->path == null)
        {
            $this->path = '/';
        }
    }

    /**
     * fill in the blanks when we have a WS address
     */
    private function postProcessSetAddressWs()
    {
        if ($this->port == null)
        {
            $this->port = 80;
        }
        if ($this->path == null)
        {
            $this->path = '/';
        }
    }

    /**
     * fill in the blanks when we have a WSS address
     */
    private function postProcessSetAddressWss()
    {
        if ($this->port == null)
        {
            $this->port = 443;
        }
        if ($this->path == null)
        {
            $this->path = '/';
        }
    }

    /**
     * Obtain the string that would be passed to a HTTP server when making
     * a GET request.
     *
     * @return string
     */
    public function getRequestLine()
    {
        $return = $this->path;
        if (isset($this->queryString))
        {
            $return .= '?' . $this->queryString;
        }

        if (isset($this->fragment))
        {
            $return .= '#' . $this->fragment;
        }

        return $return;
    }

    /**
     * Return the URL we represent as a string
     * @return string
     */
    public function __toString()
    {
        return $this->rawAddress;
    }

}