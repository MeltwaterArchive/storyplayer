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
use DataSift\Stone\LogLib\Log;

/**
 * Support for dealing with content that is chunked
 *
 * @category  Libraries
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class HttpChunkedTransport extends HttpTransport
{
    /**
     * Read data from the connection
     *
     * @param HttpClientConnection $connection our connection to the HTTP server
     * @param HttpClientResponse $response where we put the results
     * @return mixed null on error, otherwise the size of the content read
     */
    public function readContent(HttpClientConnection $connection, HttpClientResponse $response)
    {
        // cannot read if we do not have an open socket
        if (!$connection->isConnected())
        {
            $response->addError("readContent", "not connected");
            return null;
        }

        if (!$response->transferIsChunked())
        {
            $response->addError("readContent", "Transfer-Encoding is not Chunked");
            return null;
        }

        // we are chunking!
        //
        // keep count of how many
        $chunkCount = 0;

        // do we need to read all of the chunks in one go?
        if ($response->connectionMustClose())
        {
            $chunkSize = 1;
            do
            {
                $chunkSize = $this->readChunk($connection, $response);
                $chunkCount++;
            }
            while (!$connection->feof() && $chunkSize != 0);
        }
        else
        {
            $chunkSize = $this->readChunk($connection, $response);
            $chunkCount++;
        }

        // how many chunks have we received?
        // $context->stats->updateStats('response.chunks', $chunkCount);

        // does the connection need to close?
        if ($response->connectionMustClose())
        {
            $connection->disconnect();
        }

        // all done
        return $chunkSize;
    }

    /**
     * Read a single chunk from the HTTP server
     *
     * @param HttpClientConnection $connection
     * @param HttpClientResponse $response
     * @return int the size of the chunk
     */
    protected function readChunk(HttpClientConnection $connection, HttpClientResponse $response)
    {
        $crlf = "\r\n";

        $chunkSize = $connection->readLine();
        $response->bytesRead += strlen($chunkSize);
        $chunkSize = substr($chunkSize, 0, -2);
        $chunkSize = hexdec($chunkSize);

        if (!is_int($chunkSize))
        {
            Log::write(Log::LOG_WARNING, "Received non-integer chunksize: " . $chunksize);
            $response->type      = HttpClientResponse::TYPE_INVALID;
            $response->mustClose = true;

            return 0;
        }
        else if ($chunkSize > 1024*1024*10)
        {
            $response->addError("readChunk", "chunk size too large: " . $chunkSize);
            $response->type      = HttpClientResponse::TYPE_INVALID;
            $response->mustClose = true;
            return 0;
        }

        if ($chunkSize > 0)
        {
            $expectedChunkSize = $chunkSize + 2;
            Log::write(Log::LOG_DEBUG, "Expecting chunk of size: " . $expectedChunkSize);

            $chunk = $connection->readBlock($expectedChunkSize);

            if (strlen($chunk) < $expectedChunkSize)
            {
                $response->addError("readChunk", sprintf("Expected %d bytes; received %d bytes\n", $expectedChunkSize, strlen($chunk)));

                $response->type      = HttpClientResponse::TYPE_INVALID;
                $response->mustClose = true;

                return 0;
            }

            $response->bytesRead += strlen($chunk);
            $response->decodeChunk($chunk);

            return $chunkSize;
        }
        else
        {
            // we assume that we are at the end of the stream
            $response->mustClose = true;
            return 0;
        }
    }
}
