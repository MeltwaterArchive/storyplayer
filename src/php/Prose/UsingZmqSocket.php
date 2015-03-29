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
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

use ZMQ;
use ZMQSocket;
use DataSift\Stone\DataLib\DataPrinter;

/**
 * send data via a ZMQ socket
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingZmqSocket extends ZmqSocketBase
{
    public function bindToPort($port, $sendHwm = 100, $recvHwm = 100)
    {
        // what are we doing?
        $log = usingLog()->startAction("bind() as ZMQ tcp socket at host 'localhost':{$port}");

        // reuse the existing socket
        $socket = $this->args[0];
        $socket->bind("tcp://*:{$port}");

        // set high-water marks now
        $socket->setSockOpt(ZMQ::SOCKOPT_SNDHWM, $sendHwm);
        $socket->setSockOpt(ZMQ::SOCKOPT_RCVHWM, $recvHwm);

        // all done
        $log->endAction();
    }

    public function unbindFromPort($port)
    {
        // what are we doing?
        $log = usingLog()->startAction("unbind() ZMQ tcp socket at host 'localhost':{$port}");

        // attempt the unbind
        $this->args[0]->unbind("tcp://*:{$port}");

        // all done
        $log->endAction();
    }

    public function unbindFromAllPorts()
    {
        // what are we doing?
        $log = usingLog()->startAction("unbind() ZMQ socket from all ports");

        // where are we unbinding from?
        $endpoints = fromZmqSocket($this->args[0])->getEndpoints();

        foreach($endpoints['bind'] as $address) {
            usingLog()->writeToLog("unbinding from {$address}");
            $this->args[0]->unbind($address);
        }

        // all done
        $log->endAction();
    }

    public function connectToHost($hostId, $port, $sendHwm = 100, $recvHwm = 100)
    {
        // what are we doing?
        $log = usingLog()->startAction("connect() to ZMQ tcp socket on host '{$hostId}':{$port}");

        // where are we connecting to?
        $ipAddress = fromHost($hostId)->getIpAddress();

        // we're reusing the existing socket
        $socket = $this->args[0];

        // set high-water marks now
        $socket->setSockOpt(ZMQ::SOCKOPT_SNDHWM, $sendHwm);
        $socket->setSockOpt(ZMQ::SOCKOPT_RCVHWM, $recvHwm);

        // make the connection
        //
        // NOTE: we use the 'force' parameter here to avoid Storyplayer
        // hanging if the remote end is not available
        $socket->connect("tcp://{$ipAddress}:{$port}", true);

        // all done
        $log->endAction();
    }

    public function disconnectFromHost($hostId, $port)
    {
        // what are we doing?
        $log = usingLog()->startAction("disconnect() from ZMQ tcp socket on host '{$hostId}':{$port}");

        // where are we connecting to?
        $ipAddress = fromHost($hostId)->getIpAddress();

        // we're reusing the existing socket
        $socket = $this->args[0];

        // attempt the disconnection
        $socket->disconnect("tcp://{$ipAddress}:{$port}");

        // all done
        $log->endAction();
    }

    public function disconnectFromAllHosts()
    {
        // what are we doing?
        $log = usingLog()->startAction("disconnect() ZMQ socket from all endpoints");

        // where are we disconnecting from?
        $endpoints = fromZmqSocket($this->args[0])->getEndpoints();

        foreach($endpoints['connect'] as $address) {
            usingLog()->writeToLog("disconnecting from {$address}");
            $this->args[0]->disconnect($address);
        }

        // all done
        $log->endAction();
    }

    public function close()
    {
        // what are we doing?
        $log = usingLog()->startAction("close all open endpoints on ZMQ socket");

        $this->unbindFromAllPorts();
        $this->disconnectFromAllHosts();

        // all done
        $log->endAction();
    }

    public function send($message, $timeout = null)
    {
        // do we need to set a default timeout?
        if ($timeout === null) {
            $timeout = self::$defaultTimeout;
        }

        // what are we doing?
        if ($timeout == -1) {
            $log = usingLog()->startAction("send() to ZMQ socket; no timeout");
            $this->args[0]->setSockOpt(ZMQ::SOCKOPT_SNDTIMEO, -1);
        }
        else {
            $log = usingLog()->startAction("send() to ZMQ socket; timeout is {$timeout} seconds");
            $this->args[0]->setSockOpt(ZMQ::SOCKOPT_SNDTIMEO, $timeout * 1000);
        }

        // do it
        $this->args[0]->send($message);

        // all done
        $log->endAction();
    }

    public function sendNonBlocking($message)
    {
        // what are we doing?
        $log = usingLog()->startAction("sendNonBlocking() to ZMQ socket");
        $this->args[0]->setSockOpt(ZMQ::SOCKOPT_SNDTIMEO, -1);

        // do it
        $sent = $this->args[0]->send($message, ZMQ::MODE_NOBLOCK);

        // all done
        if ($sent) {
            $log->endAction("message sent");
        }
        else {
            $log->endAction("message not sent");
        }
        return $sent;
    }

    public function sendMulti($message, $timeout = null)
    {
        // do we need to set a default timeout?
        if ($timeout === null) {
            $timeout = self::$defaultTimeout;
        }

        // what are we doing?
        if ($timeout == -1) {
            $log = usingLog()->startAction("sendmulti() to ZMQ socket; no timeout");
            $this->args[0]->setSockOpt(ZMQ::SOCKOPT_SNDTIMEO, -1);
        }
        else {
            $log = usingLog()->startAction("sendmulti() to ZMQ socket; timeout is {$timeout} seconds");
            $this->args[0]->setSockOpt(ZMQ::SOCKOPT_SNDTIMEO, $timeout * 1000);
        }

        // do it
        $this->args[0]->sendmulti($message);

        // all done
        $log->endAction();
    }

    public function sendMultiNonBlocking($message)
    {
        // what are we doing?
        $log = usingLog()->startAction("sendMultiNonBlocking() to ZMQ socket");

        // do it
        $sent = $this->args[0]->sendmulti($message, ZMQ::MODE_NOBLOCK);

        // all done
        if ($sent) {
            $log->endAction("message sent");
        }
        else {
            $log->endAction("message not sent");
        }
        return $sent;
    }
}
