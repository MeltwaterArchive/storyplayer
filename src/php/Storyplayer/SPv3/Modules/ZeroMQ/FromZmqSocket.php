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
 * @package   Storyplayer/Modules/ZeroMQ
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules\ZeroMQ;

use Prose\Prose;
use Storyplayer\SPv3\Modules\Log;
use ZMQ;
use ZMQContext;
use ZMQSocket;
use DataSift\Stone\DataLib\DataPrinter;

/**
 * receive data from a ZMQ socket
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/ZeroMQ
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromZmqSocket extends ZmqSocketBase
{
    public function recv($timeout = null)
    {
        // do we need to set a default timeout?
        if ($timeout === null) {
            $timeout = self::$defaultTimeout;
        }

        // what are we doing?
        if ($timeout == -1) {
            $log = Log::usingLog()->startAction("recv() from ZMQ socket; no timeout");
            $this->args[0]->setSockOpt(ZMQ::SOCKOPT_RCVTIMEO, -1);
        }
        else {
            $log = usingLog()->startAction("recv() from ZMQ socket; timeout is {$timeout} seconds");
            $this->args[0]->setSockOpt(ZMQ::SOCKOPT_RCVTIMEO, $timeout * 1000);
        }

        // do it
        $return = $this->args[0]->recv();

        // all done
        $log->endAction();
        return $return;
    }

    public function recvNonBlocking()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("recv() from ZMQ socket");

        // do it
        $return = $this->args[0]->recv(ZMQ::MODE_NOBLOCK);

        // all done
        if ($return === false) {
            $log->endAction("receive attempt would have blocked");
        }
        else {
            $log->endAction();
        }
        return $return;
    }

    public function recvMulti($timeout = null)
    {
        // do we need to set a default timeout?
        if ($timeout === null) {
            $timeout = self::$defaultTimeout;
        }

        // what are we doing?
        if ($timeout == -1) {
            $log = Log::usingLog()->startAction("recvmulti() from ZMQ socket; no timeout");
            $this->args[0]->setSockOpt(ZMQ::SOCKOPT_RCVTIMEO, -1);
        }
        else {
            $log = Log::usingLog()->startAction("recvmulti() from ZMQ socket; timeout is {$timeout} seconds");
            $this->args[0]->setSockOpt(ZMQ::SOCKOPT_RCVTIMEO, $timeout * 1000);
        }

        // do it
        $return = $this->args[0]->recvmulti();

        // we need to look at the received value
        $printer = new DataPrinter();
        $msg     = $printer->convertToString($return);

        // all done
        $log->endAction("result is: {$msg}");
        return $return;
    }

    public function recvMultiNonBlocking()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("recvmulti() from ZMQ socket");

        // do it
        $return = $this->args[0]->recvmulti(ZMQ::MODE_NOBLOCK);

        // all done
        if ($return === false) {
            $log->endAction("receive attempt would have blocked");
        }
        else {
            $log->endAction();
        }
        return $return;
    }

    public function getEndpoints()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get the list of endpoints for a ZMQ socket");

        // do it
        $endpoints = $this->args[0]->getEndpoints();

        // all done
        $log->endAction($endpoints);
        return $endpoints;
    }
}
