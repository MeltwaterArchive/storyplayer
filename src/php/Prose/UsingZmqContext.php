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
use ZMQContext;
use ZMQSocket;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\DataLib\DataPrinter;

/**
 * create a ZMQ socket for sending or receiving data
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingZmqContext extends Prose
{
	protected $socketMap = [
		"PUB"    => ZMQ::SOCKET_PUB,
		"SUB"    => ZMQ::SOCKET_SUB,
		"REQ"    => ZMQ::SOCKET_REQ,
		"REP"    => ZMQ::SOCKET_REP,
		"XREQ"   => ZMQ::SOCKET_XREQ,
		"XREP"   => ZMQ::SOCKET_XREP,
		"PUSH"   => ZMQ::SOCKET_PUSH,
		"PULL"   => ZMQ::SOCKET_PULL,
		"ROUTER" => ZMQ::SOCKET_ROUTER,
		"DEALER" => ZMQ::SOCKET_DEALER,
	];

	public function __construct(Storyteller $st, $params = [])
	{
		// make sure we have a ZMQContext
		//
		// $params[0] is null when we need to create a ZMQContext
		if (!isset($params[0])) {
			$params[0] = new ZMQContext();
		}

		// now we're ready to call the parent constructor
		parent::__construct($st, $params);
	}

	public function getZmqContext()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get a ZMQContext object");

		// all done
		$log->endAction();
		return $this->args[0];
	}

	public function bindToPort($port, $socketType, $sendHwm = 100, $recvHwm = 100)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("bind() to ZMQ tcp '{$socketType}' socket at host 'localhost':{$port}");

		// do we have a supported socket?
		if (!isset($this->socketMap[$socketType])) {
			$msg = "unknown ZMQ socket type '{$socketType}'";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// make the connection
		$socket = new ZMQSocket($this->args[0], $this->socketMap[$socketType]);
		if (!$socket) {
			$msg = "unable to create ZMQ socket";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}
		$socket->bind("tcp://*:{$port}");

		// set high-water marks now
		$socket->setSockOpt(ZMQ::SOCKOPT_SNDHWM, $sendHwm);
		$socket->setSockOpt(ZMQ::SOCKOPT_RCVHWM, $recvHwm);

		// all done
		$log->endAction();
		return $socket;
	}

	public function connectToHost($hostId, $port, $socketType, $sendHwm = 100, $recvHwm = 100)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("connect() to ZMQ '{$socketType}' socket on host '{$hostId}':{$port}");

		// do we have a supported socket?
		if (!isset($this->socketMap[$socketType])) {
			$msg = "unknown ZMQ socket type '{$socketType}'";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// where are we connecting to?
		$ipAddress = fromHost($hostId)->getIpAddress();

		// create the socket
		$socket = new ZMQSocket($this->args[0], $this->socketMap[$socketType]);
		if (!$socket) {
			$msg = "unable to create ZMQ socket";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

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
		return $socket;
	}
}
