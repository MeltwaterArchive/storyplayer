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

namespace Storyplayer\SPv2\Modules;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

use Storyplayer\SPv2\Modules\ZeroMQ\ExpectsZmq;
use Storyplayer\SPv2\Modules\ZeroMQ\ExpectsZmqSocket;
use Storyplayer\SPv2\Modules\ZeroMQ\FromZmqSocket;
use Storyplayer\SPv2\Modules\ZeroMQ\UsingZmq;
use Storyplayer\SPv2\Modules\ZeroMQ\UsingZmqContext;
use Storyplayer\SPv2\Modules\ZeroMQ\UsingZmqSocket;
use ZMQContext;
use ZMQSocket;

class ZeroMQ
{
    /**
     * returns the ExpectsZmq module
     *
     * This module provides support for working with ZeroMQ, the no-broker
     * inter-process queuing library.
     *
     * @return \Storyplayer\SPv2\Modules\ZeroMQ\ExpectsZmq
     */
    public static function expectsZmq()
    {
        return new ExpectsZmq(StoryTeller::instance());
    }

    /**
     * returns the ExpectsZmqSocket module
     *
     * This module provides support for testing ZeroMQ sockets
     *
     * @param  \ZMQSocket
     *         the ZMQSocket to test
     * @return \Storyplayer\SPv2\Modules\ZeroMQ\ExpectsZmqSocket
     */
    public static function expectsZmqSocket(ZMQSocket $zmqSocket)
    {
        return new ExpectsZmqSocket(StoryTeller::instance(), [$zmqSocket]);
    }

    /**
     * returns the FromZmqSocket module
     *
     * This module adds support for receiving data via a ZeroMQ socket.
     *
     * @param  \ZMQSocket $zmqSocket
     *         the ZeroMQ socket you want to receive data from
     * @return \Storyplayer\SPv2\Modules\ZeroMQ\FromZmqSocket
     */
    public static function fromZmqSocket(ZMQSocket $zmqSocket)
    {
        return new FromZmqSocket(StoryTeller::instance(), [$zmqSocket]);
    }

    /**
     * returns the UsingZmq module
     *
     * This module provides support for working with ZeroMQ, the no-broker
     * inter-process queuing library.
     *
     * @return \Storyplayer\SPv2\Modules\ZeroMQ\UsingZmq
     */
    public static function usingZmq()
    {
        return new UsingZmq(StoryTeller::instance());
    }

    /**
     * returns the UsingZmqContext module
     *
     * This module provides support for creating ZeroMQ sockets
     *
     * @param  \ZMQContext|null $zmqContext
     *         the ZMQContext to use when creating the socket
     *         (leave empty and we'll create a context for you)
     * @return \Storyplayer\SPv2\Modules\ZeroMQ\UsingZmqContext
     */
    public static function usingZmqContext(ZMQContext $zmqContext = null, $ioThreads = 1)
    {
        return new UsingZmqContext(StoryTeller::instance(), [$zmqContext, $ioThreads]);
    }

    /**
     * returns the UsingZmqSocket module
     *
     * This module provides support for sending messages via a ZeroMQ socket
     *
     * @param  \ZMQSocket $zmqSocket
     *         the socket to send on
     * @return \Storyplayer\SPv2\Modules\ZeroMQ\UsingZmqSocket
     */
    public static function usingZmqSocket(ZMQSocket $zmqSocket)
    {
        return new UsingZmqSocket(StoryTeller::instance(), [$zmqSocket]);
    }
}
