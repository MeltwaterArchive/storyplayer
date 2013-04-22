<?php

namespace DataSift\Storyplayer\Prose;

use ZMQ;
use ZMQContext;
use ZMQSocket;

use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\JsonObject;

class ZmqActions extends ProseActions
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

	public function recvMulti($socket)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("recvmulti() from ZMQ socket");

		// do it
		$return = $socket->recvmulti();

		// all done
		$log->endAction();
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