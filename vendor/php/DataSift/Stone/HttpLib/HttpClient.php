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

use DataSift\Stone\HttpLib\Transports\HttpDefaultTransport;
use DataSift\Stone\HttpLib\Transports\HttpChunkedTransport;
use DataSift\Stone\HttpLib\Transports\WsTransport;

/**
 * An effective URL client with detailed metrics built in
 *
 * @category  Libraries
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class HttpClient
{
    /**
     * Our connection to the (probably remote) http server
     *
     * @var HttpClientConnection
     */
    protected $connection = null;

    /**
     * The helper library for working with the connection
     *
     * @var HttpTransport
     */
    protected $transport  = null;

    /**
     * A statsd client to log timing data to
     *
     * @var object
     */
    protected $statsdClient = null;

    /**
     * Make a request to the HTTP server
     *
     * @param HttpClientRequest $request
     * @return HttpClientResponse
     */
    public function newRequest(HttpClientRequest $request)
    {
        $method = 'new' . ucfirst(strtolower($request->getHttpVerb())) . 'Request';
        return call_user_func_array(array($this, $method), array($request));
    }

    // =========================================================================
    //
    // Support for GET requests, possibly ones that stream
    //
    // -------------------------------------------------------------------------

    /**
     * Make a new GET request to the HTTP server
     *
     * NOTE: the connection to the HTTP server will only be closed *if* the
     *       HTTP server sends a Connection: close header
     *
     * @param HttpClientRequest $request the request to make
     * @return HttpClientResponse what we got back from the HTTP server
     */
    public function newGetRequest(HttpClientRequest $request)
    {
        // var_dump('>> GET ' . (string)$request->getAddress());
        // can we connect to the remote server?
        $this->connection = new HttpClientConnection();
        if (!$this->connection->connect($request->getAddress()))
        {
            // could not connect
            return false;
        }

        // choose a transport; this may change as we work with the connection
        if ($request->getAddress()->scheme == 'ws')
        {
            $this->transport = new WsTransport();
        }
        else
        {
            $this->transport = new HttpDefaultTransport();
        }

        // now, send the GET request
        $this->transport->sendGet($this->connection, $request);

        // listen for an answer
        $response = $this->transport->readResponse($this->connection, $request);

        // at this point, we have read all of the headers sent back to us
        //
        // do we need to switch transports?
        if ($response->transferIsChunked())
        {
            $this->transport = new HttpChunkedTransport();
        }

        // now, do we have any valid content to read?
        if ($response->type && !$response->hasErrors())
        {
            $this->transport->readContent($this->connection, $response);
        }

        // return the results
        return $response;
    }

    /**
     * Make a new POST request to the HTTP server
     *
     * NOTE: the connection to the HTTP server will only be closed *if* the
     *       HTTP server sends a Connection: close header
     *
     * @param HttpClientRequest $request the request to make
     * @return HttpClientResponse what we got back from the HTTP server
     */
    public function newPostRequest(HttpClientRequest $request)
    {
        // can we connect to the remote server?
        $this->connection = new HttpClientConnection();
        if (!$this->connection->connect($request->getAddress(), 5))
        {
            // could not connect
            return false;
        }

        // choose a transport; this may change as we work with the connection
        if ($request->getAddress()->scheme == 'ws')
        {
            $this->transport = new WsTransport();
        }
        else
        {
            $this->transport = new HttpDefaultTransport();
        }

        // now, send the POST request
        $this->transport->sendPost($this->connection, $request);

        // listen for an answer
        $response = $this->transport->readResponse($this->connection, $request);

        // at this point, we have read all of the headers sent back to us
        //
        // do we need to switch transports?
        if ($response->transferIsChunked())
        {
            $this->transport = new HttpChunkedTransport();
        }

        // now, do we have any valid content to read?
        if ($response->type && !$response->hasErrors())
        {
            $this->transport->readContent($this->connection, $response);
        }

        // return the results
        return $response;
    }

    // =========================================================================
    //
    // Support for PUT requests, possibly ones that stream
    //
    // -------------------------------------------------------------------------

    /**
     * Make a new PUT request to the HTTP server
     *
     * NOTE: the connection to the HTTP server will only be closed *if* the
     *       HTTP server sends a Connection: close header
     *
     * @param HttpClientRequest $request the request to make
     * @return HttpClientResponse what we got back from the HTTP server
     */
    public function newPutRequest(HttpClientRequest $request)
    {
        // var_dump('>> PUT ' . (string)$request->getAddress());
        // can we connect to the remote server?
        $this->connection = new HttpClientConnection();
        if (!$this->connection->connect($request->getAddress(), 5))
        {
            // could not connect
            return false;
        }

        // choose a transport; this may change as we work with the connection
        if ($request->getAddress()->scheme == 'ws')
        {
            $this->transport = new WsTransport();
        }
        else
        {
            $this->transport = new HttpDefaultTransport();
        }

        // now, send the GET request
        $this->transport->sendPut($this->connection, $request);

        // listen for an answer
        $response = $this->transport->readResponse($this->connection, $request);

        // at this point, we have read all of the headers sent back to us
        //
        // do we need to switch transports?
        if ($response->transferIsChunked())
        {
            $this->transport = new HttpChunkedTransport();
        }

        // now, do we have any valid content to read?
        if ($response->type && !$response->hasErrors())
        {
            $this->transport->readContent($this->connection, $response);
        }

        // return the results
        return $response;
    }

    // =========================================================================
    //
    // Support for DELETE requests, possibly ones that stream
    //
    // -------------------------------------------------------------------------

    /**
     * Make a new DELETE request to the HTTP server
     *
     * NOTE: the connection to the HTTP server will only be closed *if* the
     *       HTTP server sends a Connection: close header
     *
     * @param HttpClientRequest $request the request to make
     * @return HttpClientResponse what we got back from the HTTP server
     */
    public function newDeleteRequest(HttpClientRequest $request)
    {
        // var_dump('>> DELETE ' . (string)$request->getAddress());
        // can we connect to the remote server?
        $this->connection = new HttpClientConnection();
        if (!$this->connection->connect($request->getAddress(), 5))
        {
            // could not connect
            return false;
        }

        // choose a transport; this may change as we work with the connection
        if ($request->getAddress()->scheme == 'ws')
        {
            $this->transport = new WsTransport();
        }
        else
        {
            $this->transport = new HttpDefaultTransport();
        }

        // now, send the GET request
        $this->transport->sendDelete($this->connection, $request);

        // listen for an answer
        $response = $this->transport->readResponse($this->connection, $request);

        // at this point, we have read all of the headers sent back to us
        //
        // do we need to switch transports?
        if ($response->transferIsChunked())
        {
            $this->transport = new HttpChunkedTransport();
        }

        // now, do we have any valid content to read?
        if ($response->type && !$response->hasErrors())
        {
            $this->transport->readContent($this->connection, $response);
        }

        // return the results
        return $response;
    }

    /**
     * Read more data from an existing HTTP connection
     *
     * @param HttpClientResponse $response
     * @return boolean false if there was no more data, true otherwise
     */

    public function readContent(HttpClientResponse $response)
    {
        // do we have an open connection?
        if (!isset($this->connection))
        {
            return null;
        }
        if (!$this->connection->isConnected())
        {
            // we are not connected
            return null;
        }

        // if we get here, we are connected
        $response->resetForNextResponse();
        return $this->transport->readContent($this->connection, $response);
    }

    /**
     * Send data to an existing HTTP connection
     *
     * @param string $data
     *      The data to send
     */
    public function sendData($data)
    {
        // do we have an open connection?
        if (!isset($this->connection))
        {
            return null;
        }
        if (!$this->connection->isConnected())
        {
            // we are not connected
            return null;
        }

        // send the data
        $this->connection->send($data);
    }

    // =========================================================================
    //
    //  Additional connect/disconnect support
    //
    // -------------------------------------------------------------------------

    /**
     * Are we currently connected?
     * @return boolean
     */
    public function isConnected()
    {
        if (!$this->connection instanceof HttpClientConnection)
        {
            return false;
        }

        return $this->connection->isConnected();
    }

    /**
     * Disconnect from the remote server.
     *
     * If we are not currently connected, do nothing
     */
    public function disconnect()
    {
        if ($this->connection->isConnected())
        {
            $this->transport->close($this->connection);
            $this->connection->disconnect();
        }
    }

    /**
     * Get the current socket from the underlying connection
     *
     * @return resource
     *         the TCP/IP socket
     */
    public function getSocket()
    {
        return $this->connection->getSocket();
    }
}
