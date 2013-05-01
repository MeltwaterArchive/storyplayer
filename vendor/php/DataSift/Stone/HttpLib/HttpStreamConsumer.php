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

use DataSift\Stone\HttpLib\HttpAddress;
use DataSift\Stone\HttpLib\HttpClient;
use DataSift\Stone\HttpLib\HttpClientRequest;
use DataSift\Stone\HttpLib\HttpClientResponse;
use DataSift\Stone\LogLib\Log;
use DataSift\Stone\TimeLib\DateInterval;

/**
 * A generic class for receiving data from one or more connections
 * via HTTP streaming
 *
 * @category  Libraries
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class HttpStreamConsumer
{
    /**
     * Read data from a stream, forever
     *
     * @param array $requests
     *        where to find the stream
     * @param callback $callback
     *        we call this after every piece of data is read from the stream
     * @param string duration
     *        how long (in DaveInterval format) to consume the stream for
     */
    public function consume($requests, $callback, $duration = null)
    {
        // keep track of the number of active requests
        $activeRequests = array();

        // deal with things when a non-array is passed in
        if (!is_array($requests))
        {
            $requests = array($requests);
        }

        // work out our starting time
        $now = time();

        // convert the max duration into seconds
        if ($duration)
        {
            // how long are we consuming for?
            $noOfRequests = count($requests);
            Log::write(Log::LOG_INFO, "consuming {$noOfRequests} stream(s) for {$duration}");

            // convert the duration into a time to stop
            $interval = new DateInterval($duration);
            $seconds  = $interval->getTotalSeconds();

            $stopAt   = $now + $seconds;
        }
        else
        {
            // effectively, run forever
            $stopAt = PHP_INT_MAX;
        }

        // make the initial requests for data
        foreach ($requests as $index => $request)
        {
            // lets get our requests made
            $client = new HttpClient();
            $bytesRead = 0;

            // we make the request
            $response = $client->newGetRequest($request);
            if (!$response instanceof HttpClientResponse)
            {
                // the connection failed
                // var_dump('failed connection');
            }
            else
            {
                $activeRequests[$index] = array('request' => $request, 'client' => $client, 'response' => $response);

                // now, we need to make sure that we call the callback here
                if (!call_user_func_array($callback, array($client, $request, $response)))
                {
                    // remove us from the list of active requests
                    $client->disconnect();
                    unset($activeRequests[$index]);
                }
            }
        }

        // do we have any active requests left?
        if (count($activeRequests) == 0)
        {
            // no ... job done
            return;
        }

        // if we get here, then we have some active requests to consume data from
        $done = false;
        do
        {
            // the time we started this loop
            $now = time();

            // wait for a stream
            $readArray = array();
            foreach ($activeRequests as $requestIndex => $request)
            {
                if ($request['client']->isConnected())
                {
                    $readArray[$requestIndex] = $request['client']->getSocket();
                }
            }

            if (count($readArray) == 0)
            {
                // var_dump('>> READARRAY is 0');
                // We don't have anything to process, dive out
                return;
            }

            $sockets    = $readArray;
            $writeArray = $exceptArray = array();

            $noOfRequests = count($activeRequests);
            Log::write(Log::LOG_DEBUG, "Waiting for data from {$noOfRequests} connection(s)");

            $selectedStreams = 0;
            while ($selectedStreams == 0 && count($readArray) > 0 && $now < $stopAt)
            {
                $readArray = $sockets;

                // we use the scream operator here to suppress a stupid
                // warning about the system call being interrupted by
                // signals such as SIGALRM ... grrr at the warning!
                $selectedStreams = @stream_select($readArray, $writeArray, $exceptArray, 1);

                // make sure we have the right time after our select()
                $now = time();

                $remainingDuration = $stopAt - $now;
                Log::write(Log::LOG_DEBUG, "selectedStreams is {$selectedStreams}; remaining duration is {$remainingDuration}");
            }

            // have we run out of time?
            if ($now >= $stopAt)
            {
                // yes, we have
                Log::write(Log::LOG_INFO, "Closing connection; duration of {$seconds} second(s) reached");

                foreach ($activeRequests as $request)
                {
                    $request['client']->disconnect();
                }
                // all done
                return;
            }

            // if we get here, then we have at least one connection
            // to read data from
            $noOfSockets = count($readArray);
            Log::write(Log::LOG_DEBUG, "Data available from {$noOfSockets} connections");
            foreach ($readArray as $socket)
            {
                $requestIndex = array_search($socket, $sockets);

                $client   = $activeRequests[$requestIndex]['client'];
                $request  = $activeRequests[$requestIndex]['request'];
                $response = $activeRequests[$requestIndex]['response'];

                // step 1:
                //
                // get some data
                $client->readContent($response);

                // step 2:
                //
                // call the callback
                if (!call_user_func_array($callback, array($client, $request, $response)))
                {
                    // remove us from the list of streams
                    Log::write(Log::LOG_INFO, "Closing connection at the internal callback's request");

                    $client->disconnect();
                    unset($activeRequests[$requestIndex]);
                }

                // step 3: was there a problem with the response?
                else if ($response->hasErrors())
                {
                    foreach ($response->errorMsgs as $msg)
                    {
                        Log::write(Log::LOG_WARNING, "HttpStreamConsumer error: $msg");
                    }

                    Log::write(Log::LOG_INFO, "Closing connection after errors");
                    $client->disconnect();
                    unset($activeRequests[$requestIndex]);
                }

                else if ($response->connectionMustClose())
                {
                    Log::write(Log::LOG_INFO, "Closing connection (probably closed by remote end");
                    $client->disconnect();
                    unset($activeRequests[$requestIndex]);
                }
            }
        }
        while (count($activeRequests) > 0);
    }
}
