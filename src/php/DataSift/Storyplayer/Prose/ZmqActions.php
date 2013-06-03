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

namespace DataSift\Storyplayer\Prose;

use ZMQ;
use ZMQContext;
use ZMQSocket;

use DataSift\Stone\DataLib\DataPrinter;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\Prose;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * do things with ZeroMQ
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ZmqActions extends Prose
{
	protected $socketMap = array (
		ZMQ::SOCKET_PUB => "ZMQ::SOCKET_PUB",
		ZMQ::SOCKET_SUB => "ZMQ::SOCKET_SUB",
		ZMQ::SOCKET_REQ => "ZMQ::SOCKET_REQ",
		ZMQ::SOCKET_REP => "ZMQ::SOCKET_REP",
		ZMQ::SOCKET_XREQ => "ZMQ::SOCKET_XREQ",
		ZMQ::SOCKET_XREP => "ZMQ::SOCKET_XREP",
		ZMQ::SOCKET_PUSH => "ZMQ::SOCKET_PUSH",
		ZMQ::SOCKET_PULL => "ZMQ::SOCKET_PULL",
		ZMQ::SOCKET_ROUTER => "ZMQ::SOCKET_ROUTER",
		ZMQ::SOCKET_DEALER => "ZMQ::SOCKET_DEALER"
	);

	public function bind($address, $socketType)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("bind() to '{$this->socketMap[$socketType]}' at address '{$address}'");

		// make the connection
		$socket = new ZMQSocket(new ZMQContext(), $socketType);
		if (!$socket) {
			throw new E5xx_ActionFailed(__METHOD__, "unable to create ZMQ socket");
		}
		$socket->bind($address);

		// all done
		$log->endAction();
		return $socket;
	}

	public function connect($address, $socketType, $sendHwm = 100, $recvHwm = 100)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("connect() to '{$this->socketMap[$socketType]}' at address '{$address}'");

		// create the socket
		$socket = new ZMQSocket(new ZMQContext(), $socketType);
		if (!$socket) {
			throw new E5xx_ActionFailed(__METHOD__, "unable to create ZMQ socket");
		}

		// set high-water marks now
		$socket->setSockOpt(ZMQ::SOCKOPT_SNDHWM, $sendHwm);
		$socket->setSockOpt(ZMQ::SOCKOPT_RCVHWM, $recvHwm);

		$socket->connect($address);

		// all done
		$log->endAction();
		return $socket;
	}

	public function send($socket, $message)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("send() to ZMQ socket");

		// do it
		$socket->send($message);

		// all done
		$log->endAction();
	}

	public function recv($socket)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("recv() from ZMQ socket");

		// do it
		$return = $socket->recv();

		// all done
		$log->endAction();
		return $return;
	}

	public function recvNonBlocking($socket)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("recv() from ZMQ socket");

		// do it
		$return = $socket->recv(ZMQ::MODE_NOBLOCK);

		// all done
		if ($return === false) {
			$log->endAction("receive attempt would have blocked");
		}
		else {
			$log->endAction();
		}
		return $return;
	}

	public function sendMulti($socket, $message)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("sendmulti() to ZMQ socket");

		// do it
		$socket->sendmulti($message);

		// all done
		$log->endAction();
	}

	public function recvMulti($socket, $timeout = -1)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		if ($timeout == -1) {
			$log = $st->startAction("recvmulti() from ZMQ socket; no timeout");
		}
		else {
			$log = $st->startAction("recvmulti() from ZMQ socket; timeout is {$timeout} seconds");
		}

		// set the socket timeout
		$socket->setSockOpt(ZMQ::SOCKOPT_RCVTIMEO, $timeout);

		// do it
		$return = $socket->recvmulti();

		// we need to look at the received value
		$printer = new DataPrinter();
		$msg     = $printer->convertToString($return);

		// all done
		$log->endAction("result is: {$msg}");
		return $return;
	}

	public function recvMultiNonBlocking($socket)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("recvmulti() from ZMQ socket");

		// do it
		$return = $socket->recvmulti(ZMQ::MODE_NOBLOCK);

		// all done
		if ($return === false) {
			$log->endAction("receive attempt would have blocked");
		}
		else {
			$log->endAction();
		}
		return $return;
	}
}