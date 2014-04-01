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

use DataSift\Storyplayer\Cli\Injectables;
use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\UserLib\ConfigUserLoader;
use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Stone\ObjectLib\E5xx_NoSuchProperty;
use Datasift\Os;
use Datasift\IfconfigParser;
use Datasift\netifaces;
use Datasift\netifaces\NetifacesException;
use Exception;

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
	 * @var DataSift\StoryPlayer\UserLib\User
	 */
	public $user;

	/**
	 * the details of the environment, taken directly from the app's JSON
	 * config file
	 *
	 * @var \DataSift\Stone\ObjectLib\BaseObject
	 */
	public $env;

	/**
	 *
	 * @var string
	 */
	public $envName;

	/**
	 *
	 * @var array
	 */
	public $defines;

	/**
	 *
	 * @var \DataSift\Stone\ObjectLib\BaseObject
	 */
	public $device;

	/**
	 *
	 * @var string
	 */
	public $deviceName;

	/**
	 * persistent config (users, vms, etc) that gets cached to disk
	 * between runs
	 *
	 * @var DataSift\Stone\ObjectLib\BaseObject
	 */
	public $runtime;

	// ==================================================================
	//
	// initialise all the things
	//
	// ------------------------------------------------------------------

	/**
	 * build the config for this story run, from the config we've loaded
	 *
	 * we rebuild this for each story to ensure that each story runs
	 * with an identical config
	 *
	 * @param Injectables $injectables
	 */
	public function __construct(Injectables $injectables)
	{
		$this->user = new BaseObject;

		// if there are any 'defines', we need those
		$this->initDefines($injectables->staticConfig);

		// build up the environment of app settings
		$this->initEnvironment($injectables->staticConfig, $injectables->envName);

		// which device are we using for testing?
		$this->initDevice($injectables->staticConfig, $injectables->deviceName);

		// we need to know where to look for Prose classes
		$this->initProse($injectables->staticConfig);

		// we need to know which Phases to run, and where to find them
		$this->initPhases($injectables->staticConfig);
	}

	public function initDefines($staticConfig)
	{
		// make sure we start with an empty set of defines
		$this->defines = new BaseObject;

		// merge any that are present in the config
		$this->defines->mergeFrom($staticConfig->defines);
	}

	public function initDevice($staticConfig, $deviceName)
	{
		// copy over the device that we want
		$this->device = $staticConfig->devices->$deviceName;

		// remember the device name
		$this->deviceName = $deviceName;
	}

	public function initEnvironment($staticConfig, $envName)
	{
		// make sure we start with a fresh environment
		$this->env = new BaseObject;

		// we need to work out which environment we are running against,
		// as all other decisions are affected by this
		$this->env->mergeFrom($staticConfig->environments->defaults);
		try {
			$this->env->mergeFrom($staticConfig->environments->$envName);
		} catch (E5xx_NoSuchProperty $e) {
			echo "*** warning: using empty config instead of '{$envName}'";
		}

		// we need to remember the name of the environment too!
		$this->envName = $envName;

		// we need to provide information about the machine that we
		// are running on
		$this->env->host = new BaseObject;
		list($this->env->host->networkInterface, $this->env->host->ipAddress) = $this->getHostIpAddress();
	}

	public function initPhases($staticConfig)
	{
		// start with an empty list
		$this->phases = new BaseObject;

		// where are we looking?
		if (!isset($staticConfig->phases)) {
			// nothing here
			return;
		}

		// copy across what we have
		$this->phases = $staticConfig->phases;

		// now, process the special case(s) that we have
		if (isset($staticConfig->phases->namespaces)) {
			if (!is_array($staticConfig->phases->namespaces)) {
				throw new E5xx_InvalidConfig("the 'phases.namespaces' section of the config must either be an array, or it must be left out");
			}
		}
	}

	public function initProse($staticConfig)
	{
		// start with an empty list
		$this->prose = array();

		// where are we looking?
		if (isset($staticConfig->prose, $staticConfig->prose->namespaces)) {
			if (!is_array($staticConfig->prose->namespaces)) {
				throw new E5xx_InvalidConfig("the 'prose.namespaces' section of the config must either be an array, or it must be left out");
			}

			// copy over where to look for Prose classes
			$this->prose = $staticConfig->prose;
		}
	}

	public function initUser(StoryTeller $st)
	{
		// do we have a cached user?

		// our default provider of users
		$className = "DataSift\\Storyplayer\\UserLib\\GenericUserGenerator";

		// do we have a specific generator to load?
		if (isset($this->env->users, $this->env->users->generator)) {
			$className = $this->env->users->generator;
		}

		// create the generator
		$generator = new ConfigUserLoader(new $className());

		// get a user from the generator
		$this->user = $generator->getUser($st);

		// all done
	}

	protected function getHostIpAddress()
	{
		// step 1 - how many adapters do we have on this box?
		// @todo Maybe we want to move this somewhere more central later?
		$os = Os::getOs();
		$parser = IfconfigParser::fromDistributions($os->getPossibleClassNames());
		$netifaces = new netifaces($os, $parser);

		$adapters = $netifaces->listAdapters();
		if (empty($adapters)) {
			throw new Exception("unable to parse host machine network adapters list");
		}

		// step 2 - find an adapter that is most likely to have the IP address
		// that we want
		//
		// note: am not sure that the search list for OSX interfaces is
		// reliable :(

		try {
			$searchList = array("br0", "p2p1", "eth0", "en2", "en0", "en1", "wlan0");
			foreach ($searchList as $adapterToTest) {
				// skip over any adapters that don't exist on this machine
				if (!in_array($adapterToTest, $adapters)) {
					continue;
				}

				// we think the adapter is present
				//
				// does it have an IP address?
				try {
					$ipAddress = $netifaces->getIpAddress($adapterToTest);
				} catch(NetifacesException $e){
					// We couldn't get an IP address
					$ipAddress = null;
				}

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
			throw new NetifacesException("Unable to determine IP address");

		} catch (NetifacesException $e){
			throw new Exception("could not determine IP address of host machine");
		}
	}
}
