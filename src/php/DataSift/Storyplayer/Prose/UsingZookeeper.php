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

use Zookeeper;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * do things with zookeeper
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingZookeeper extends Prose
{
	protected $connect_timeout = 0.5;
	protected $host;
	protected $zk;

	protected $default_acl = array( array(
		"perms"  => Zookeeper::PERM_ALL,
		"scheme" => "world",
		"id"     => "anyone"
	));

	public function __construct(StoryTeller $st, $args = array())
	{
		// call the parent constructor
		parent::__construct($st, $args);

		// $args[0] contains the hostname of our Zookeeper server
		$this->host = $args[0];

		// connect to zookeeper
		$this->zk = $this->connect($this->host);
	}

	protected function connect($host)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("connect to Zookeeper at '{$host}'");

		// create a Zookeeper object, and connect
		$zk = new Zookeeper();
		$zk->connect($host);

		$start = microtime(true);
		while ($zk->getState() != Zookeeper::CONNECTED_STATE) {
			usleep(100000);

			// timed out?
			$now = microtime(true);
			if ($now > $start + $this->connect_timeout) {
				throw new E5xx_ActionFailed(__METHOD__);
			}
		}

		// if we get here, we have successfully connected
		$log->endAction();
		return $zk;
	}

	protected function ensurePathToKeyExists($key)
	{
		// break the path up into parts
		$parts = explode("/", $key);

		// drop the first part, as it is empty because of the way
		// that PHP's explode() works
		if (empty($parts[0])) {
			$parts = array_slice($parts, 1);
		}

		// drop the last part, as that is the key itself
		unset($parts[count($parts) -1]);

		// start with an empty path
		$path  = '';

		// work through the parts, building up the path to create from
		// the root of the directory
		foreach ($parts as $part)
		{
			// expand our path to the next part
			$path .= '/' . $part;

			// does this path exist?
			if (!$this->zk->exists($path)) {
				// no - create it
				if (!$this->zk->create($path, 1, $this->default_acl))
				{
					// failed to create the entry
					throw new E5xx_ActionFailed(__METHOD__);
				}
			}
		}
	}

	public function write($key, $value)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("set '{$key}' to '{$value}' in Zookeeper");

		// make sure the path exists
		$this->ensurePathToKeyExists($key);

		// now we can safely set the key itself
		if (!$this->zk->exists($key)) {
			if (!$this->zk->create($key, $value, $this->default_acl))
				throw new E5xx_ActionFailed(__METHOD__);
		}
		else if (!$this->zk->set($key, $value)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done
		$log->endAction();
	}

	public function delete($key)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("delete '{$key}' from Zookeeper");

		// does the key itself exist in zookeeper?
		if (!$this->zk->exists($key)) {
			// no ... we're done here
			$log->endAction("key does not exist ... no action required");
			return;
		}

		// does the key have any children?
		$children = $this->zk->getChildren($key);
		if (!empty($children)) {
			// we have to delete all of the children first
			$this->deleteChildrenOf($key);
		}

		// now we can safely delete the key itself
		if (!$this->zk->delete($key)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done
		$log->endAction();
	}

	public function deleteChildrenOf($key)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("delete all children of '{$key}' from Zookeeper");

		// does the key itself exist in zookeeper?
		if (!$this->zk->exists($key)) {
			// no - we're done here
			$log->endAction("key does not exist ... no action required");
			return;
		}

		// get the children
		$children = $this->zk->getChildren($key);
		if (!empty($children)) {
			foreach ($children as $childKey) {
				$this->delete($key . '/' . $childKey);
			}
		}

		// all done
		$log->endAction();
	}
}