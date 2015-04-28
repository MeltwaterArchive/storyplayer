#!/usr/bin/env php
<?php

$context = new ZMQContext();
$single  = array();
$single['in']  = $context->getSocket(ZMQ::SOCKET_PULL);
$single['out'] = $context->getSocket(ZMQ::SOCKET_PUSH);
$multi  = array();
$multi['in']  = $context->getSocket(ZMQ::SOCKET_PULL);
$multi['out'] = $context->getSocket(ZMQ::SOCKET_PUSH);

$single['in']->bind("tcp://0.0.0.0:5000");
$single['out']->bind("tcp://0.0.0.0:5001");
$multi['in']->bind("tcp://0.0.0.0:5002");
$multi['out']->bind("tcp://0.0.0.0:5003");

$poller = new ZMQPoll();
$poller->add($single['in'], ZMQ::POLL_IN);
$poller->add($multi['in'], ZMQ::POLL_IN);

$events = 0;
while(true) {
	try {
		$readable = $writeable = [];
		$events = $poller->poll($readable, $writeable, -1);
	}
	catch (Exception $e) {
		// do nothing
	}

	if ($events > 0) {
		foreach($readable as $r) {
			if ($r === $single['in']) {
				$msg = $r->recv();
				$single['out']->send($msg);
			}
			else {
				$msg = $r->recvmulti();
				$multi['out']->sendmulti($msg);
			}
		}
	}
}
