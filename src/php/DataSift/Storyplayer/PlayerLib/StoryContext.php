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
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\UserLib\ConfigUserLoader;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * a sanitised & dynamically enhanced version of the config that has been
 * loaded for this test run
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class StoryContext extends BaseObject
{
	/**
	 * the details about the user that has been chosen
	 *
	 * @var DataSift\Stone\ObjectLib\BaseObject
	 */
	public $user;

	/**
	 * the details of the environment, taken directly from the app's JSON
	 * config file
	 *
	 * @var DataSift\Stone\ObjectLib\BaseObject
	 */
	public $env;

	/**
	 * persistent config (users, vms, etc) that gets cached to disk
	 * between runs
	 *
	 * @var DataSift\Stone\ObjectLib\BaseObject
	 */
	public $runtime;

	public function __construct()
	{
		$this->user    = new BaseObject;
		$this->env     = new BaseObject;
		$this->runtime = new BaseObject;

		// we need to provide information about the machine that we
		// are running on
		$this->env->host = new BaseObject;
		list($this->env->host->networkInterface, $this->env->host->ipAddress) = $this->getHostIpAddress();
	}

	public function initUser($staticConfig, $runtimeConfig, Story $story)
	{
		// do we have a cached user?

		// our default provider of users
		$className = "DataSift\\Storyplayer\\UserLib\\GenericUserGenerator";

		var_dump($this->env->users);

		// do we have a specific generator to load?
		if (isset($this->env->users, $this->env->users->generator)) {
			$className = $this->env->users->generator;
		}

		// create the generator
		$generator = new ConfigUserLoader(new $className());

		// get a user from the generator
		$this->user = $generator->getUser($staticConfig, $runtimeConfig, $this, $story);

		// all done
	}

	protected function getHostIpAddress()
	{
		// we can't use constants inside our strings
		$BIN_DIR=APP_BINDIR;

		// step 1 - how many adapters do we have on this box?
		$adapters = trim(`{$BIN_DIR}/get-ip -l | tr '\n' ' '`);
		if (empty($adapters)) {
			throw new Exception("unable to parse host machine network adapters list");
		}
		$adapters = explode(' ', $adapters);

		// step 2 - find an adapter that is most likely to have the IP address
		// that we want
		//
		// note: am not sure that the search list for OSX interfaces is
		// reliable :(
		$searchList = array("br0", "p20p1", "eth0", "en2", "en0", "en1");
		foreach ($searchList as $adapterToTest) {
			// skip over any adapters that don't exist on this machine
			if (!in_array($adapterToTest, $adapters)) {
				continue;
			}

			// we think the adapter is present
			//
			// does it have an IP address?
			$ipAddress = trim(`{$BIN_DIR}/get-ip -i {$adapterToTest}`);

			// did we get back a valid IP address?
			$parts = explode('.', $ipAddress);
			if (count($parts) == 4) {
				// success!
				return array($adapterToTest, $ipAddress);
			}
		}

		// if we get here, we could not determine the IP address of our
		// host :(
		//
		// this sucks
		throw new Exception("could not determine IP address of host machine");
	}
}
