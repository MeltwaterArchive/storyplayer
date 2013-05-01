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
namespace DataSift\Stone\HttpLib\Transports;

use Exception;
use DataSift\Stone\ExceptionsLib\LegacyErrorCatcher;
use DataSift\Stone\HttpLib\HttpClientConnection;
use DataSift\Stone\HttpLib\HttpClientRequest;
use DataSift\Stone\HttpLib\HttpClientResponse;

/**
 * Base class for supporting all of the different connection types to a HTTP
 * server
 *
 * @category  Libraries
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
abstract class HttpTransport
{
    /**
     * The OneTrueLineEnding(tm) for the HTTP dialect
     */
    const CRLF = "\r\n";

    /**
     * Send data to our connection as a GET request
     *
     * Override this for more exotic transports, such as web sockets
     *
     * @param HttpClientConnection $connection
     *     our network connection to the HTTP server
     * @param HttpClientRequest $request
     *     the request that we are sending
     * @return mixed
     *     HttpClientResponse on success,
     *     false if the connection was not open
     */
    public function sendGet(HttpClientConnection $connection, HttpClientRequest $request)
    {
        // log how many GET requests we have made
        // $context->stats->increment('request.verb.get');

        // cannot send if we do not have an open socket
        if (!$connection->isConnected())
        {
            return false;
        }

        // how quickly did we get the chance to send the first line off?
        // $context->stats->timing('request.firstLineTime', microtime(true) - $connection->connectStart);

        // send the request
        //var_dump('>> SENDING');
        $connection->send($request->getRequestLine() . self::CRLF);

        // send any supporting headers

        $this->addAdditionalHeadersToRequest($request);
        $headers = $request->getHeadersString();
        if ($headers !== null)
        {
            $connection->send($headers);
        }

        // send empty line to complete request
        $connection->send(self::CRLF);
        //var_dump('>> SENT');

        // how long did that take?
        // $context->stats->timing('request.lastLineTime', microtime(true) - $connection->connectStart);
    }

    /**
     * Send data to our connection as a POST request
     *
     * Override this for more exotic transports, such as web sockets
     *
     * @param HttpClientConnection $connection
     *     our network connection to the HTTP server
     * @param HttpClientRequest $request
     *     the request that we are sending
     * @return mixed
     *     HttpClientResponse on success,
     *     false if the connection was not open
     */
    public function sendPost(HttpClientConnection $connection, HttpClientRequest $request)
    {
        // log how many GET requests we have made
        // $context->stats->increment('request.verb.get');

        // cannot send if we do not have an open socket
        if (!$connection->isConnected())
        {
            return false;
        }

        // how quickly did we get the chance to send the first line off?
        // $context->stats->timing('request.firstLineTime', microtime(true) - $connection->connectStart);

        // send the request
        //var_dump('>> SENDING');
        $connection->send($request->getRequestLine() . self::CRLF);
        $encodedData = $request->getBody();
        if (!$request->hasHeaderCalled('Content-Type'))
        {
            $request->withExtraHeader('Content-Type', 'application/x-www-form-urlencoded');
        }
        $request->withExtraHeader('Content-Length', strlen($encodedData));

        // send any supporting headers
        $this->addAdditionalHeadersToRequest($request);
        $headers = $request->getHeadersString();
        if ($headers !== null)
        {
            $connection->send($headers);
        }

        // send empty line to complete request
        $connection->send(self::CRLF);
        //var_dump('>> SENT');

        $connection->send($encodedData);

        // how long did that take?
        // $context->stats->timing('request.lastLineTime', microtime(true) - $connection->connectStart);
    }

    /**
     * Send data to our connection as a PUT request
     *
     * Override this for more exotic transports, such as web sockets
     *
     * @param HttpClientConnection $connection
     *     our network connection to the HTTP server
     * @param HttpClientRequest $request
     *     the request that we are sending
     * @return mixed
     *     HttpClientResponse on success,
     *     false if the connection was not open
     */
    public function sendPut(HttpClientConnection $connection, HttpClientRequest $request)
    {
        // log how many GET requests we have made
        // $context->stats->increment('request.verb.get');

        // cannot send if we do not have an open socket
        if (!$connection->isConnected())
        {
            return false;
        }

        // how quickly did we get the chance to send the first line off?
        // $context->stats->timing('request.firstLineTime', microtime(true) - $connection->connectStart);

        // send the request
        //var_dump('>> SENDING');
        $connection->send($request->getRequestLine() . self::CRLF);
        $encodedData = $request->getBody();
        if (!$request->hasHeaderCalled('Content-Type'))
        {
            $request->withExtraHeader('Content-Type', 'application/x-www-form-urlencoded');
        }
        $request->withExtraHeader('Content-Length', strlen($encodedData));

        // send any supporting headers
        $this->addAdditionalHeadersToRequest($request);
        $headers = $request->getHeadersString();
        if ($headers !== null)
        {
            $connection->send($headers);
        }

        // send empty line to complete request
        $connection->send(self::CRLF);
        //var_dump('>> SENT');

        $connection->send($encodedData . self::CRLF);

        // how long did that take?
        // $context->stats->timing('request.lastLineTime', microtime(true) - $connection->connectStart);
    }

    /**
     * Send data to our connection as a DELETE request
     *
     * Override this for more exotic transports, such as web sockets
     *
     * @param HttpClientConnection $connection
     *     our network connection to the HTTP server
     * @param HttpClientRequest $request
     *     the request that we are sending
     * @return mixed
     *     HttpClientResponse on success,
     *     false if the connection was not open
     */
    public function sendDelete(HttpClientConnection $connection, HttpClientRequest $request)
    {
        // log how many GET requests we have made
        // $context->stats->increment('request.verb.get');

        // cannot send if we do not have an open socket
        if (!$connection->isConnected())
        {
            return false;
        }

        // how quickly did we get the chance to send the first line off?
        // $context->stats->timing('request.firstLineTime', microtime(true) - $connection->connectStart);

        // send the request
        //var_dump('>> SENDING');
        $connection->send($request->getRequestLine() . self::CRLF);

        // send any supporting headers

        $this->addAdditionalHeadersToRequest($request);
        $headers = $request->getHeadersString();
        if ($headers !== null)
        {
            $connection->send($headers);
        }

        // send empty line to complete request
        $connection->send(self::CRLF);
        //var_dump('>> SENT');

        // how long did that take?
        // $context->stats->timing('request.lastLineTime', microtime(true) - $connection->connectStart);
    }

    /**
     * Generate any additional header lines to send for a request
     *
     * This is here for exotic transports (like web sockets) to override when
     * they need to do something funky
     *
     * @param HttpClientRequest $request
     *     the request that the user wants to send
     *     we add any additional headers to the request object
     */
    protected function addAdditionalHeadersToRequest(HttpClientRequest $request)
    {
        // do nothing by default
    }

    /**
     * Read the response line + response headers from the HTTP connection
     *
     * @param HttpClientConnection $connection our connection to the HTTP server
     * @param HttpClientRequest $request the request that we want a response to
     * @return HttpClientResponse the response we received
     */
    public function readResponse(HttpClientConnection $connection, HttpClientRequest $request)
    {
        // now, we need to see what the server said
        $response = new HttpClientResponse($connection);
        $statusCode = $this->readResponseLine($connection, $response);

        // do we think it is safe to read the response headers?
        if (!$response->hasErrors())
        {
            // yes - let's go get them
            $this->readHeaders($connection, $response);
        }

        // what do we think of the response?
        //
        // this hook is here for the more exotic transports
        $this->evaluateResponse($connection, $request, $response);

        // all done, for better or for worse
        return $response;
    }

    /**
     * Read the very first line back that we get back from the HTTP server
     * after making a request
     *
     * @param HttpClientConnection $connection
     *        the raw connection to read from
     * @param HttpClientResponse $response
     *        the response for us to record into
     * @return int|null the HTTP status code that we get
     */
    protected function readResponseLine(HttpClientConnection $connection, HttpClientResponse $response)
    {
        // make sure the socket is valid
        if (!$connection->isConnected())
        {
            $response->addError("readResponseLine", "not connected");
            return null;
        }

        // we are expecting statusLine
        $statusLine = $connection->readLine();
        // var_dump('>> STATUS: ' . $statusLine);
        $response->bytesRead += strlen($statusLine);
        $statusLine = substr($statusLine, 0, -2);

        // how long did it take to get the first response?
        // $context->stats->timing('response.firstLineTime', microtime(true) - $connection->connectStart);

        // decode the statusLine
        $response->decodeStatusLine($statusLine);

        // what response code did we get?
        // $context->stats->increment('response.status.' . $response->statusCode);

        // all done
        return $response->statusCode;
    }

    /**
     * Read the headers from the remote server
     *
     * @param HttpClientConnection $connection the network connection to the HTTP server
     * @param HttpClientResponse $response
     */
    protected function readHeaders(HttpClientConnection $connection, HttpClientResponse $response)
    {
        // make sure the socket is valid
        if (!$connection->isConnected())
        {
            $response->addError("readHeaders", "not connected");
            return false;
        }

        // retrieve the headers
        $headersCompleted = false;
        do
        {
            $headerLine = $connection->readLine();
            //var_dump('>> HEADER: ' . $headerLine);
            $response->bytesRead += strlen($headerLine);
            $headerLine = substr($headerLine, 0, -2);

            if (strlen($headerLine) == 0)
            {
                $headersCompleted = true;
            }
            else
            {
                $response->decodeHeader($headerLine);
            }
        }
        while (!$connection->feof() && !$headersCompleted);
    }

    /**
     * Read data from the connection
     *
     * Each transport mechanism needs to provide its own readContent() that
     * copes with whatever perculiarities abound
     *
     * @param HttpClientConnection $connection our connection to the HTTP server
     * @param HttpClientResponse $response where we put the results
     * @return mixed null on error, otherwise the size of the content read
     */
    abstract public function readContent(HttpClientConnection $connection, HttpClientResponse $response);

    /**
     * Work out whether we like the response or not
     *
     * This is called after the response line + all response headers have been
     * retrieved from the remote HTTP server
     *
     * Exotic transports (such as websockets) should override this
     *
     * @param HttpClientConnection $connection
     *     our connection to the HTTP server
     * @param HttpClientRequest $request
     *     the request that this is a response to
     * @param HttpClientResponse $response
     *     the response containing the headers we have received
     */
    protected function evaluateResponse(HttpClientConnection $connection, HttpClientRequest $request, HttpClientResponse $response)
    {
        // do nothing by default :)
    }

    /**
     * close the connection
     *
     * @param  HttpClientConnection $connection
     *         the connection to close
     * @return void
     */
    public function close(HttpClientConnection $connection)
    {
        // do nothing by default
    }
}
