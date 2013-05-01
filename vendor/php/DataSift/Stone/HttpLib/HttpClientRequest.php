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

/**
 * The request that we want to make to the HTTP server
 *
 * @category  Libraries
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

class HttpClientRequest
{
    /**
     * The URL we are connecting to
     * @var HttpAddress
     */
    private $address;

    /**
     * the HTTP verb for this request
     * @var string
     */
    private $httpVerb = 'GET';

    /**
     * The list of headers to send with this request
     * @var array
     */

    public $headers = array(
        'Accept'            => 'text/html,application/xhtml,+xml,application/xml,application/json',
        'AcceptCharset'     => 'utf-8',
        'Connection'        => 'keep-alive',
        'UserAgent'         => 'Hornet/6.6.6 (DataSift Hive) PHP/CLI (Hornet, like wasps only with evil intent)',
    );

    /**
     * THe headers to send with this request, as a single string for efficiency
     * @var string
     */
    private $headersString = null;

    /**
     * the data body to include in the request
     * @var array|string| null
     */
    private $body = array();

    /**
     * Constructor
     *
     * We need to know the URL we are connecting to
     *
     * @param HttpAddress|string $address
     */
    public function __construct($address)
    {
        $this->setAddress($address);
    }

    /**
     * get the HTTP verb that we're going to use when we send this
     * request to the HTTP server
     *
     * @return string
     */
    public function getHttpVerb()
    {
        return $this->httpVerb;
    }

    /**
     * What address are we connecting to?
     *
     * @return HttpAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the URL that this request is for
     *
     * @param mixed $address
     *      The address for this request. Can be string, can be HttpAddress
     */
    public function setAddress($address)
    {
        if ($address instanceof HttpAddress)
        {
            $this->address = $address;
        }
        else
        {
            $this->address = new HttpAddress($address);
        }

        $this->headers['Host'] = $this->address->hostname;
    }

    /**
     * Set the HTTP verb to use with this request
     *
     * @param  string $verb
     *         one of: GET, POST, PUT, DELETE
     * @return HttpClientRequest $this
     */
    public function withHttpVerb($verb)
    {
        // make sure that we store the verb in upper case
        $this->httpVerb = strtoupper($verb);

        // all done
        return $this;
    }

    /**
     * Set an extra header to send with this request
     *
     * @param string $heading
     * @param string $value
     * @return HttpClientRequest $this
     */
    public function withExtraHeader($heading, $value)
    {
        $this->headers[$heading] = $value;
        $this->headersString = null;

        return $this;
    }

    /**
     * Set the user-agent to send with this request
     *
     * The default should be fine unless we need to pretend to be a specific
     * browser
     *
     * @param string $userAgent the user-agent string to send
     * @return HttpClientRequest $this
     */
    public function withUserAgent($userAgent)
    {
        $this->headers['UserAgent'] = $userAgent;
        $this->headersString = null;
        return $this;
    }

    /**
     * Return the headers to send to the browser, as a single well-formed string
     *
     * @return string
     */
    public function getHeadersString()
    {
        if (!isset($this->headersString))
        {
            $this->headersString = '';
            foreach ($this->headers as $heading => $value)
            {
                $this->headersString .= $heading . ': ' . $value . "\r\n";
            }
        }

        return $this->headersString;
    }

    /**
     * Do we have the named header already set?
     *
     * @param  string $headerName
     *         the name of the header to check for
     *
     * @return boolean
     *         TRUE if the header already exists
     *         FALSE if it does not
     */
    public function hasHeaderCalled($headerName)
    {
        if (isset($this->headers[$headerName]))
        {
            return true;
        }

        return false;
    }

    /**
     * Obtain the request line to send to the HTTP server
     *
     * @param string $httpVersion
     *        the HTTP version number to use in the request line
     * @return string
     */
    public function getRequestLine($httpVersion = '1.1')
    {
        return $this->httpVerb . ' ' . $this->address->getRequestLine() . ' HTTP/' . $httpVersion;
    }

    // =========================================================================
    //
    // Support for GET requests
    //
    // -------------------------------------------------------------------------

    /**
     * Set this request to be a GET request
     *
     * @return HttpClientRequest $this
     */
    public function asGetRequest()
    {
        return $this->withHttpVerb("GET");
    }

    /**
     * mark this request as a HTTP GET request
     *
     * @return HttpClientRequest $this
     */
    public function setGetRequest()
    {
        return $this->withHttpVerb("GET");
    }

    // =========================================================================
    //
    // Support for POST and PUT requests
    //
    // -------------------------------------------------------------------------

    /**
     * mark this request as a HTTP POST request
     *
     * @return HttpClientRequest $this
     */
    public function asPostRequest()
    {
        return $this->withHttpVerb("POST");
    }

    /**
     * mark this request as a HTTP POST request
     *
     * @return HttpClientRequest $this
     */
    public function setPostRequest()
    {
        return $this->withHttpVerb("POST");
    }

    /**
     * mark this request as a HTTP PUT request
     *
     * @return HttpClientRequest $this
     */
    public function asPutRequest()
    {
        return $this->withHttpVerb("PUT");
    }

    /**
     * mark this request as a HTTP PUT request
     *
     * @return HttpClientRequest $this
     */
    public function setPutRequest()
    {
        return $this->withHttpVerb("PUT");
    }

    /**
     * add a key/value pair to the request's body data
     *
     * @param string $name
     *        name of the key to add
     * @param string $value
     *        value of the data to add
     */
    public function addData($name, $value)
    {
        $this->body[$name] = $value;
    }

    /**
     * set the body data for this request
     *
     * @param string $payload
     *        the data to submit for this request
     * @return HttpClientRequest $this
     */
    public function withPayload($payload)
    {
        $this->body = $payload;
        return $this;
    }

    /**
     * set the body data for this request
     *
     * @param string $payload
     *        the data to submit for this request
     */
    public function setPayload($payload)
    {
        $this->body = $payload;
    }

    /**
     * get the body data for this request
     *
     * if the body data is an array of key/value pairs, we'll automatically
     * convert that into an encoded string suitable for submitting as a
     * POSTed form
     *
     * @return string
     */
    public function getBody()
    {
        if (is_array($this->body))
        {
            return $this->getEncodedBody();
        }
        else
        {
            return $this->body;
        }
    }

    /**
     * get the body of the request, encoded for submitting as a POSTed
     * form
     *
     * @return string
     */
    public function getEncodedBody()
    {
        $return = '';
        foreach ($this->body as $key => $value)
        {
            if (strlen($return) > 0)
            {
                $return .= '&';
            }
            $return .= urlencode($key) . '=' . urlencode($value);
        }

        return $return;
    }

    // =========================================================================
    //
    // Support for DELETE requests
    //
    // -------------------------------------------------------------------------

    /**
     * mark this request as being a HTTP DELETE request
     *
     * @return HttpClientRequest $this
     */
    public function asDeleteRequest()
    {
        return $this->withHttpVerb("DELETE");
    }
}